<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\VnpayService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Hiển thị trang thanh toán của một đơn đặt phòng.
     */
    public function show(
        Booking $booking
    ): View|RedirectResponse {
        $this->ensureBookingBelongsToCurrentUser(
            $booking
        );

        $booking->load([
            'room.homestay',
            'payment',
        ]);

        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('bookings.show', $booking)
                ->with(
                    'error',
                    'Đơn đặt phòng đã bị hủy nên không thể thanh toán.'
                );
        }

        if ($booking->payment_status === 'paid') {
            return redirect()
                ->route('bookings.show', $booking)
                ->with(
                    'success',
                    'Đơn đặt phòng này đã được thanh toán.'
                );
        }

        return view(
            'payments.create',
            compact('booking')
        );
    }

    /**
     * Tạo giao dịch VNPAY và chuyển người dùng sang cổng thanh toán.
     */
    public function createVnpay(
        Request $request,
        Booking $booking,
        VnpayService $vnpayService
    ): RedirectResponse {
        $this->ensureBookingBelongsToCurrentUser(
            $booking
        );

        $validated = $request->validate(
            [
                'bank_code' => [
                    'nullable',
                    'string',
                    'in:VNPAYQR,VNBANK,INTCARD',
                ],
            ],
            [
                'bank_code.in' =>
                    'Phương thức thanh toán VNPAY không hợp lệ.',
            ]
        );

        $bankCode = $validated['bank_code'] ?? null;

        try {
            $paymentUrl = DB::transaction(
                function () use (
                    $booking,
                    $bankCode,
                    $request,
                    $vnpayService
                ): string {
                    $lockedBooking = Booking::query()
                        ->lockForUpdate()
                        ->findOrFail($booking->id);

                    $this->ensureBookingCanBePaid(
                        $lockedBooking
                    );

                    $now = now();

                    /*
                     * Đánh dấu các giao dịch chờ đã hết hạn là thất bại.
                     */
                    $lockedBooking
                        ->payments()
                        ->where(
                            'payment_method',
                            'vnpay'
                        )
                        ->where(
                            'status',
                            'pending'
                        )
                        ->whereNotNull(
                            'expired_at'
                        )
                        ->where(
                            'expired_at',
                            '<=',
                            $now
                        )
                        ->update([
                            'status' => 'failed',
                        ]);

                    /*
                     * Tái sử dụng giao dịch chờ còn hạn nếu có.
                     */
                    $payment = $lockedBooking
                        ->payments()
                        ->where(
                            'payment_method',
                            'vnpay'
                        )
                        ->where(
                            'status',
                            'pending'
                        )
                        ->whereNotNull(
                            'expired_at'
                        )
                        ->where(
                            'expired_at',
                            '>',
                            $now
                        )
                        ->latest('id')
                        ->first();

                    if ($payment) {
                        $payment->update([
                            'bank_code' => $bankCode,
                            'amount' =>
                                $lockedBooking->total_price,
                        ]);
                    } else {
                        $payment = $lockedBooking
                            ->payments()
                            ->create([
                                'transaction_code' =>
                                    $this->generateTransactionCode(
                                        $lockedBooking
                                    ),

                                'payment_method' => 'vnpay',
                                'bank_code' => $bankCode,

                                'amount' =>
                                    $lockedBooking->total_price,

                                'status' => 'pending',

                                'expired_at' => $now
                                    ->copy()
                                    ->addMinutes(
                                        max(
                                            1,
                                            (int) config(
                                                'services.vnpay.expire_minutes',
                                                15
                                            )
                                        )
                                    ),
                            ]);
                    }

                    $lockedBooking->update([
                        'payment_status' => 'pending',
                    ]);

                    return $vnpayService
                        ->createPaymentUrl(
                            $payment->fresh('booking'),
                            (string) $request->ip(),
                            $bankCode
                        );
                }
            );

            return redirect()->away(
                $paymentUrl
            );
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $exception->getMessage()
                );
        } catch (Throwable $exception) {
            Log::error(
                'Không thể tạo giao dịch VNPAY.',
                [
                    'booking_id' => $booking->id,
                    'user_id' => auth()->id(),
                    'message' =>
                        $exception->getMessage(),
                    'exception' => $exception,
                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Không thể kết nối VNPAY lúc này. Vui lòng thử lại.'
                );
        }
    }

    /**
     * VNPAY chuyển trình duyệt người dùng về Return URL.
     *
     * Return URL chỉ xác minh và hiển thị kết quả.
     * Trạng thái giao dịch chính thức được cập nhật qua IPN.
     */
    public function handleReturn(
        Request $request,
        VnpayService $vnpayService
    ): View {
        $payload = $request->query();

        try {
            $signatureValid =
                $vnpayService->verifySignature(
                    $payload
                );
        } catch (Throwable $exception) {
            Log::error(
                'Không thể xác minh VNPAY Return.',
                [
                    'message' =>
                        $exception->getMessage(),
                    'exception' => $exception,
                ]
            );

            $signatureValid = false;
        }

        $transactionCode = trim(
            (string) (
                $payload['vnp_TxnRef'] ?? ''
            )
        );

        $payment = Payment::query()
            ->with([
                'booking.room.homestay',
            ])
            ->where(
                'transaction_code',
                $transactionCode
            )
            ->first();

        $returnedAmount =
            $vnpayService
                ->convertVnpayAmountToVnd(
                    $payload['vnp_Amount']
                    ?? null
                );

        $amountValid = $payment
            && $returnedAmount
                === (int) $payment->amount;

        $vnpayReportedSuccess =
            $vnpayService->isSuccessful(
                $payload
            );

        /*
         * Cơ chế dự phòng:
         * Nếu Return URL có chữ ký hợp lệ, đúng mã giao dịch,
         * đúng số tiền và VNPAY báo thành công thì cập nhật giao dịch.
         *
         * IPN vẫn được giữ làm luồng xác nhận server-to-server.
         */
        if (
            $signatureValid
            && $payment
            && $amountValid
            && $vnpayReportedSuccess
            && $payment->status === 'pending'
        ) {
            DB::transaction(
                function () use (
                    $payment,
                    $payload,
                    $vnpayService
                ): void {
                    $lockedPayment = Payment::query()
                        ->with('booking')
                        ->lockForUpdate()
                        ->find($payment->id);

                    if (
                        !$lockedPayment
                        || $lockedPayment->status !== 'pending'
                    ) {
                        return;
                    }

                    $lockedPayment->update([
                        'gateway_transaction_code' =>
                            $payload['vnp_TransactionNo']
                            ?? null,

                        'bank_code' =>
                            $payload['vnp_BankCode']
                            ?? $lockedPayment->bank_code,

                        'response_code' =>
                            (string) (
                                $payload['vnp_ResponseCode']
                                ?? ''
                            ),

                        'transaction_status' =>
                            (string) (
                                $payload['vnp_TransactionStatus']
                                ?? ''
                            ),

                        'status' => 'paid',

                        'paid_at' =>
                            $this->parseVnpayPayDate(
                                $payload['vnp_PayDate']
                                ?? null
                            ),

                        'response_data' =>
                            $vnpayService->responseData(
                                $payload
                            ),
                    ]);

                    $lockedPayment->booking->update([
                        'payment_status' => 'paid',
                    ]);
                }
            );

            $payment->refresh();
            $payment->load('booking.room.homestay');
        }

        $resultStatus = match (true) {
            !$signatureValid =>
                'invalid_signature',

            !$payment =>
                'not_found',

            !$amountValid =>
                'invalid_amount',

            $vnpayReportedSuccess
                && $payment->status === 'paid'
                => 'success',

            $vnpayReportedSuccess =>
                'processing',

            default =>
                'failed',
        };

        return view(
            'payments.result',
            [
                'payment' => $payment,

                'resultStatus' =>
                    $resultStatus,

                'responseCode' =>
                    $payload['vnp_ResponseCode']
                    ?? null,

                'transactionStatus' =>
                    $payload[
                        'vnp_TransactionStatus'
                    ] ?? null,

                'gatewayTransactionCode' =>
                    $payload[
                        'vnp_TransactionNo'
                    ] ?? null,

                'bankCode' =>
                    $payload['vnp_BankCode']
                    ?? null,
            ]
        );
    }

    /**
     * VNPAY gọi server-to-server để xác nhận giao dịch.
     */
    public function handleIpn(
        Request $request,
        VnpayService $vnpayService
    ): JsonResponse {
        $payload = $request->query();

        try {
            if (
                !$vnpayService->verifySignature(
                    $payload
                )
            ) {
                return $this->ipnResponse(
                    '97',
                    'Invalid signature'
                );
            }

            $transactionCode = trim(
                (string) (
                    $payload['vnp_TxnRef']
                    ?? ''
                )
            );

            $payment = Payment::query()
                ->where(
                    'transaction_code',
                    $transactionCode
                )
                ->first();

            if (!$payment) {
                return $this->ipnResponse(
                    '01',
                    'Order not found'
                );
            }

            $returnedAmount =
                $vnpayService
                    ->convertVnpayAmountToVnd(
                        $payload['vnp_Amount']
                        ?? null
                    );

            if (
                $returnedAmount
                !== (int) $payment->amount
            ) {
                return $this->ipnResponse(
                    '04',
                    'Invalid amount'
                );
            }

            $updated = DB::transaction(
                function () use (
                    $payment,
                    $payload,
                    $vnpayService
                ): bool {
                    $lockedPayment =
                        Payment::query()
                            ->with('booking')
                            ->lockForUpdate()
                            ->findOrFail(
                                $payment->id
                            );

                    /*
                     * Không cập nhật lại giao dịch đã được xử lý.
                     */
                    if (
                        $lockedPayment->status
                        !== 'pending'
                    ) {
                        return false;
                    }

                    $isSuccessful =
                        $vnpayService
                            ->isSuccessful(
                                $payload
                            );

                    $responseCode = (string) (
                        $payload[
                            'vnp_ResponseCode'
                        ] ?? ''
                    );

                    $transactionStatus =
                        (string) (
                            $payload[
                                'vnp_TransactionStatus'
                            ] ?? ''
                        );

                    $lockedPayment->update([
                        'gateway_transaction_code' =>
                            $payload[
                                'vnp_TransactionNo'
                            ] ?? null,

                        'bank_code' =>
                            $payload[
                                'vnp_BankCode'
                            ]
                            ?? $lockedPayment
                                ->bank_code,

                        'response_code' =>
                            $responseCode,

                        'transaction_status' =>
                            $transactionStatus,

                        'status' =>
                            $isSuccessful
                                ? 'paid'
                                : 'failed',

                        'paid_at' =>
                            $isSuccessful
                                ? $this
                                    ->parseVnpayPayDate(
                                        $payload[
                                            'vnp_PayDate'
                                        ] ?? null
                                    )
                                : null,

                        'response_data' =>
                            $vnpayService
                                ->responseData(
                                    $payload
                                ),
                    ]);

                    $lockedPayment
                        ->booking
                        ->update([
                            'payment_status' =>
                                $isSuccessful
                                    ? 'paid'
                                    : 'failed',
                        ]);

                    return true;
                }
            );

            if (!$updated) {
                return $this->ipnResponse(
                    '02',
                    'Order already confirmed'
                );
            }

            return $this->ipnResponse(
                '00',
                'Confirm Success'
            );
        } catch (Throwable $exception) {
            Log::error(
                'Lỗi xử lý VNPAY IPN.',
                [
                    'transaction_code' =>
                        $payload[
                            'vnp_TxnRef'
                        ] ?? null,

                    'message' =>
                        $exception->getMessage(),

                    'exception' => $exception,
                ]
            );

            return $this->ipnResponse(
                '99',
                'Unknown error'
            );
        }
    }

    /**
     * Kiểm tra đơn thuộc về người dùng đang đăng nhập.
     */
    private function ensureBookingBelongsToCurrentUser(
        Booking $booking
    ): void {
        abort_unless(
            (int) $booking->user_id
                === (int) auth()->id(),
            403
        );
    }

    /**
     * Kiểm tra đơn có đủ điều kiện thanh toán.
     */
    private function ensureBookingCanBePaid(
        Booking $booking
    ): void {
        if ($booking->status === 'cancelled') {
            throw new RuntimeException(
                'Đơn đặt phòng đã bị hủy nên không thể thanh toán.'
            );
        }

        if (
            !in_array(
                $booking->payment_status,
                [
                    'unpaid',
                    'pending',
                    'failed',
                ],
                true
            )
        ) {
            throw new RuntimeException(
                'Trạng thái hiện tại của đơn không cho phép thanh toán.'
            );
        }

        if ((int) $booking->total_price <= 0) {
            throw new RuntimeException(
                'Số tiền thanh toán của đơn không hợp lệ.'
            );
        }
    }

    /**
     * Tạo mã tham chiếu giao dịch HomeStayGo.
     */
    private function generateTransactionCode(
        Booking $booking
    ): string {
        do {
            $transactionCode =
                'VNP'
                . now('Asia/Ho_Chi_Minh')
                    ->format('YmdHis')
                . $booking->id
                . strtoupper(
                    Str::random(6)
                );
        } while (
            Payment::query()
                ->where(
                    'transaction_code',
                    $transactionCode
                )
                ->exists()
        );

        return $transactionCode;
    }

    /**
     * Chuyển ngày thanh toán VNPAY thành Carbon.
     */
    private function parseVnpayPayDate(
        mixed $payDate
    ): Carbon {
        if (
            is_string($payDate)
            && preg_match(
                '/^\d{14}$/',
                $payDate
            )
        ) {
            try {
                return Carbon::createFromFormat(
                    'YmdHis',
                    $payDate,
                    'Asia/Ho_Chi_Minh'
                );
            } catch (Throwable) {
                // Dùng thời gian hiện tại nếu ngày trả về không hợp lệ.
            }
        }

        return now(
            'Asia/Ho_Chi_Minh'
        );
    }

    /**
     * Trả response đúng định dạng VNPAY IPN.
     */
    private function ipnResponse(
        string $code,
        string $message
    ): JsonResponse {
        return response()->json([
            'RspCode' => $code,
            'Message' => $message,
        ]);
    }
}