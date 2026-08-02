<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Services\VnpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class VnpayController extends Controller
{
    /**
     * VNPAY chuyển trình duyệt người dùng về đây.
     *
     * Return URL chỉ kiểm tra chữ ký và hiển thị kết quả.
     * Không cập nhật trạng thái thanh toán tại phương thức này.
     */
    public function returnUrl(
        Request $request,
        VnpayService $vnpayService
    ): View {
        $vnpayData = $vnpayService->onlyVnpayData(
            $request->query()
        );

        $validSignature = $vnpayService->verifySignature(
            $vnpayData
        );

        $transactionRef = (string) (
            $vnpayData['vnp_TxnRef'] ?? ''
        );

        $payment = null;

        if ($transactionRef !== '') {
            $payment = Payment::query()
                ->with([
                    'booking.room.homestay',
                ])
                ->where(
                    'transaction_ref',
                    $transactionRef
                )
                ->first();
        }

        $resultStatus = $this->resolveReturnStatus(
            $validSignature,
            $payment,
            $vnpayData
        );

        $resultMessage = $this->returnMessage(
            $resultStatus,
            (string) (
                $vnpayData['vnp_ResponseCode']
                ?? ''
            )
        );

        return view(
            'payments.result',
            [
                'payment' => $payment,
                'vnpayData' => $vnpayData,
                'validSignature' => $validSignature,
                'resultStatus' => $resultStatus,
                'resultMessage' => $resultMessage,
            ]
        );
    }

    /**
     * VNPAY gọi server-to-server vào đây để thông báo kết quả.
     *
     * Đây mới là nơi cập nhật payment và booking.
     */
    public function ipn(
        Request $request,
        VnpayService $vnpayService
    ): JsonResponse {
        try {
            $vnpayData = $vnpayService->onlyVnpayData(
                $request->query()
            );

            if ($vnpayData === []) {
                return $this->ipnResponse(
                    '99',
                    'Invalid request'
                );
            }

            if (
                ! $vnpayService->verifySignature(
                    $vnpayData
                )
            ) {
                return $this->ipnResponse(
                    '97',
                    'Invalid signature'
                );
            }

            if (
                (string) (
                    $vnpayData['vnp_TmnCode'] ?? ''
                )
                !== (string) config(
                    'services.vnpay.tmn_code'
                )
            ) {
                return $this->ipnResponse(
                    '97',
                    'Invalid terminal code'
                );
            }

            $transactionRef = (string) (
                $vnpayData['vnp_TxnRef'] ?? ''
            );

            $payment = Payment::query()
                ->where(
                    'transaction_ref',
                    $transactionRef
                )
                ->first();

            if (! $payment) {
                return $this->ipnResponse(
                    '01',
                    'Order not found'
                );
            }

            $vnpayAmount = (int) (
                $vnpayData['vnp_Amount'] ?? 0
            );

            /*
             * VNPAY gửi số tiền đã nhân 100.
             */
            if (
                $vnpayAmount
                !== ((int) $payment->amount * 100)
            ) {
                return $this->ipnResponse(
                    '04',
                    'Invalid amount'
                );
            }

            $response = DB::transaction(
                function () use (
                    $payment,
                    $vnpayData
                ): array {
                    $lockedPayment = Payment::query()
                        ->lockForUpdate()
                        ->findOrFail($payment->id);

                    /*
                     * IPN có thể được VNPAY gửi lại.
                     * Không xử lý lần hai nếu giao dịch
                     * đã có kết quả cuối cùng.
                     */
                    if (
                        $lockedPayment->status
                        !== Payment::STATUS_PENDING
                    ) {
                        return [
                            'RspCode' => '02',
                            'Message' =>
                                'Order already confirmed',
                        ];
                    }

                    $booking = Booking::query()
                        ->lockForUpdate()
                        ->findOrFail(
                            $lockedPayment->booking_id
                        );

                    $responseCode = (string) (
                        $vnpayData['vnp_ResponseCode']
                        ?? ''
                    );

                    $transactionStatus = (string) (
                        $vnpayData[
                            'vnp_TransactionStatus'
                        ]
                        ?? ''
                    );

                    $success =
                        $responseCode === '00'
                        && $transactionStatus === '00';

                    $newStatus = $success
                        ? Payment::STATUS_PAID
                        : $this->failureStatus(
                            $responseCode
                        );

                    $lockedPayment->update([
                        'status' => $newStatus,

                        'vnp_transaction_no' =>
                            $vnpayData[
                                'vnp_TransactionNo'
                            ]
                            ?? null,

                        'vnp_response_code' =>
                            $responseCode !== ''
                                ? $responseCode
                                : null,

                        'vnp_transaction_status' =>
                            $transactionStatus !== ''
                                ? $transactionStatus
                                : null,

                        'vnp_bank_code' =>
                            $vnpayData[
                                'vnp_BankCode'
                            ]
                            ?? null,

                        'vnp_card_type' =>
                            $vnpayData[
                                'vnp_CardType'
                            ]
                            ?? null,

                        'vnp_pay_date' =>
                            $vnpayData[
                                'vnp_PayDate'
                            ]
                            ?? null,

                        'response_data' =>
                            $vnpayData,

                        'paid_at' =>
                            $success ? now() : null,

                        'processed_at' => now(),
                    ]);

                    if (
                        $success
                        && $booking->status === 'pending'
                    ) {
                        $booking->update([
                            'status' => 'confirmed',
                        ]);
                    }

                    $lockedPayment
                        ->histories()
                        ->create([
                            'actor_id' => null,

                            'actor_type' =>
                                PaymentHistory::ACTOR_VNPAY,

                            'event' => $success
                                ? PaymentHistory::EVENT_SUCCESS
                                : (
                                    $newStatus
                                    === Payment::STATUS_CANCELLED
                                        ? PaymentHistory::EVENT_CANCELLED
                                        : PaymentHistory::EVENT_FAILED
                                ),

                            'from_status' =>
                                Payment::STATUS_PENDING,

                            'to_status' =>
                                $newStatus,

                            'note' => $success
                                ? 'VNPAY xác nhận giao dịch thành công.'
                                : 'VNPAY xác nhận giao dịch không thành công.',

                            'payload' =>
                                $vnpayData,
                        ]);

                    /*
                     * RspCode 00 nghĩa là hệ thống merchant
                     * đã ghi nhận kết quả, kể cả giao dịch
                     * thanh toán thành công hay thất bại.
                     */
                    return [
                        'RspCode' => '00',
                        'Message' => 'Confirm Success',
                    ];
                }
            );

            return response()->json(
                $response,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $exception) {
            Log::error(
                'VNPAY IPN processing error.',
                [
                    'message' =>
                        $exception->getMessage(),

                    'query' =>
                        $request->query(),
                ]
            );

            return $this->ipnResponse(
                '99',
                'Unknown error'
            );
        }
    }

    /**
     * Xác định trạng thái hiển thị ở Return URL.
     */
    private function resolveReturnStatus(
        bool $validSignature,
        ?Payment $payment,
        array $vnpayData
    ): string {
        if (! $validSignature) {
            return 'invalid';
        }

        if (! $payment) {
            return 'not_found';
        }

        /*
         * Ưu tiên trạng thái đã được IPN ghi vào DB.
         */
        if ($payment->status === Payment::STATUS_PAID) {
            return 'success';
        }

        if (
            $payment->status
            === Payment::STATUS_CANCELLED
        ) {
            return 'cancelled';
        }

        if (
            $payment->status
            === Payment::STATUS_FAILED
        ) {
            return 'failed';
        }

        if (
            $payment->status
            === Payment::STATUS_EXPIRED
        ) {
            return 'expired';
        }

        $responseCode = (string) (
            $vnpayData['vnp_ResponseCode'] ?? ''
        );

        $transactionStatus = (string) (
            $vnpayData['vnp_TransactionStatus']
            ?? ''
        );

        /*
         * Trình duyệt có thể về Return URL trước khi
         * IPN cập nhật DB. Khi đó chỉ hiển thị đang xác nhận.
         */
        if (
            $responseCode === '00'
            && $transactionStatus === '00'
        ) {
            return 'pending';
        }

        if ($responseCode === '24') {
            return 'cancelled';
        }

        return 'failed';
    }

    /**
     * Ánh xạ mã phản hồi thất bại.
     *
     * Mã 24 thường là khách hàng hủy giao dịch.
     */
    private function failureStatus(
        string $responseCode
    ): string {
        return $responseCode === '24'
            ? Payment::STATUS_CANCELLED
            : Payment::STATUS_FAILED;
    }

    /**
     * Nội dung tiếng Việt cho trang kết quả.
     */
    private function returnMessage(
        string $status,
        string $responseCode
    ): string {
        return match ($status) {
            'success' =>
                'Thanh toán thành công. Booking của bạn đã được xác nhận.',

            'pending' =>
                'VNPAY đã trả kết quả thành công. Hệ thống đang chờ IPN xác nhận giao dịch.',

            'cancelled' =>
                'Bạn đã hủy giao dịch thanh toán VNPAY.',

            'expired' =>
                'Giao dịch đã hết thời gian thanh toán.',

            'invalid' =>
                'Chữ ký phản hồi không hợp lệ. Hệ thống không ghi nhận giao dịch.',

            'not_found' =>
                'Không tìm thấy giao dịch thanh toán tương ứng.',

            default =>
                'Thanh toán không thành công.'
                . (
                    $responseCode !== ''
                        ? ' Mã phản hồi: '
                            . $responseCode
                            . '.'
                        : ''
                ),
        };
    }

    /**
     * Phản hồi chuẩn cho server VNPAY.
     */
    private function ipnResponse(
        string $code,
        string $message
    ): JsonResponse {
        return response()->json(
            [
                'RspCode' => $code,
                'Message' => $message,
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}