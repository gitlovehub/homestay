<?php

namespace App\Http\Controllers\Admin;

use App\Services\VnpayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\BookingCancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PaymentController extends Controller
{
    /**
     * Danh sách giao dịch thanh toán.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $paymentMethod = $request->input('payment_method');
        $bankCode = trim((string) $request->input('bank_code'));
        $sort = $request->input('sort');

        $paymentMethods = $this->paymentMethods();

        $allowedStatuses = [
            'pending',
            'paid',
            'failed',
            'refunded',
            'cancelled',
        ];

        $allowedSorts = [
            'oldest',
            'amount_desc',
            'amount_asc',
        ];

        if (!array_key_exists((string) $paymentMethod, $paymentMethods)) {
            $paymentMethod = null;
        }

        if (!in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = null;
        }

        $query = Payment::query()
            ->with([
                'booking.room.homestay',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('transaction_code', 'like', "%{$search}%")
                            ->orWhere('gateway_transaction_code', 'like', "%{$search}%")
                            ->orWhere('refund_transaction_code', 'like', "%{$search}%")
                            ->orWhere('refund_request_id', 'like', "%{$search}%")
                            ->orWhere('bank_code', 'like', "%{$search}%")
                            ->orWhere('payment_method', 'like', "%{$search}%")
                            ->orWhere('response_code', 'like', "%{$search}%")
                            ->orWhere('transaction_status', 'like', "%{$search}%")
                            ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                                $bookingQuery
                                    ->where('booking_code', 'like', "%{$search}%")
                                    ->orWhere('customer_name', 'like', "%{$search}%")
                                    ->orWhere('customer_email', 'like', "%{$search}%")
                                    ->orWhere('customer_phone', 'like', "%{$search}%");
                            });
                    });
                }
            )
            ->when(
                $paymentMethod !== null,
                fn($query) => $query->where('payment_method', $paymentMethod)
            )
            ->when(
                $status !== null,
                fn($query) => $query->where('status', $status)
            )
            ->when(
                $bankCode !== '',
                fn($query) => $query->where('bank_code', $bankCode)
            );

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at')->orderBy('id');
                break;

            case 'amount_desc':
                $query->orderByDesc('amount')->orderByDesc('id');
                break;

            case 'amount_asc':
                $query->orderBy('amount')->orderByDesc('id');
                break;

            default:
                $query->orderByDesc('created_at')->orderByDesc('id');
                break;
        }

        $payments = $query
            ->paginate(10)
            ->withQueryString();

        $bankCodes = Payment::query()
            ->whereNotNull('bank_code')
            ->where('bank_code', '!=', '')
            ->distinct()
            ->orderBy('bank_code')
            ->pluck('bank_code');

        $statistics = [
            'total' => Payment::query()->count(),

            'paid' => Payment::query()
                ->where('status', 'paid')
                ->count(),

            'pending' => Payment::query()
                ->where('status', 'pending')
                ->count(),

            'total_paid_amount' => Payment::query()
                ->whereIn('status', ['paid', 'refunded'])
                ->sum('amount'),

            // Dùng refunded_amount để tính đúng cả hoàn một phần.
            'total_refunded_amount' => Payment::query()
                ->sum('refunded_amount'),
        ];

        return view(
            'admin.payments.index',
            compact(
                'payments',
                'paymentMethods',
                'bankCodes',
                'statistics'
            )
        );
    }

    /**
     * Chi tiết một giao dịch.
     */
    public function show(Payment $payment): View
    {
        $payment->load([
            'booking.room.homestay',
        ]);

        $paymentMethods = $this->paymentMethods();

        $paymentMethod = $paymentMethods[$payment->payment_method]
            ?? [
                'label' => 'Không xác định',
                'uses_gateway' => false,
                'uses_bank' => false,
            ];

        return view(
            'admin.payments.show',
            compact(
                'payment',
                'paymentMethod'
            )
        );
    }

    /**
     * Kiểm tra lại trạng thái hoàn tiền tại VNPAY.
     */
    public function checkRefundStatus(
        Request $request,
        Payment $payment,
        VnpayService $vnpayService
    ): RedirectResponse {
        try {
            $payment->load('booking');

            $booking = $payment->booking;

            if (!$booking) {
                throw new RuntimeException(
                    'Không tìm thấy Booking của giao dịch.'
                );
            }

            if ($payment->payment_method !== 'vnpay') {
                throw new RuntimeException(
                    'Chỉ giao dịch VNPAY mới có thể kiểm tra hoàn tiền.'
                );
            }

            if ($booking->status !== 'cancelled') {
                throw new RuntimeException(
                    'Booking chưa ở trạng thái đã hủy.'
                );
            }

            if (
                !in_array(
                    $payment->refund_status,
                    ['pending', 'processing'],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Giao dịch hiện không ở trạng thái chờ hoàn tiền.'
                );
            }

            $result =
                $vnpayService->queryRefundStatus(
                    $payment,
                    (string) $request->ip()
                );

            DB::transaction(
                function () use ($payment, $result): void {

                    $lockedPayment =
                        Payment::query()
                            ->with('booking')
                            ->lockForUpdate()
                            ->findOrFail(
                                $payment->id
                            );

                    $lockedBooking =
                        $lockedPayment->booking;

                    if (!$lockedBooking) {
                        throw new RuntimeException(
                            'Không tìm thấy Booking.'
                        );
                    }

                    $refundData =
                        is_array(
                            $lockedPayment
                                ->refund_response_data
                        )
                        ? $lockedPayment
                            ->refund_response_data
                        : [];

                    $refundData['last_query'] =
                        $result['payload'];

                    $refundData['last_query_checked_at'] =
                        now(
                            'Asia/Ho_Chi_Minh'
                        )->toIso8601String();

                    /*
                     * ĐÃ HOÀN
                     */
                    if (
                        $result['state']
                        === 'refunded'
                    ) {
                        $refundTarget = (int) (
                            $lockedBooking
                                ->refund_amount
                            ?? 0
                        );

                        $queryAmount =
                            (int) (
                                $result['amount']
                                ?? 0
                            );

                        $confirmedAmount =
                            $queryAmount > 0
                            ? $queryAmount
                            : $refundTarget;

                        $confirmedAmount = min(
                            $confirmedAmount,
                            (int) $lockedPayment
                                ->amount
                        );

                        $confirmedAmount = max(
                            (int) $lockedPayment
                                ->refunded_amount,
                            $confirmedAmount
                        );

                        $refundedAt =
                            now(
                                'Asia/Ho_Chi_Minh'
                            );

                        $payDate =
                            (string) (
                                $result['pay_date']
                                ?? ''
                            );

                        if (
                            preg_match(
                                '/^\d{14}$/',
                                $payDate
                            )
                        ) {
                            try {
                                $refundedAt =
                                    Carbon::createFromFormat(
                                        'YmdHis',
                                        $payDate,
                                        'Asia/Ho_Chi_Minh'
                                    );
                            } catch (Throwable) {
                                // Giữ thời gian hiện tại.
                            }
                        }

                        $paymentUpdates = [
                            'refund_status' =>
                                'refunded',

                            'refunded_amount' =>
                                $confirmedAmount,

                            'refund_response_data' =>
                                $refundData,

                            'refunded_at' =>
                                $refundedAt,
                        ];

                        if (
                            !empty(
                            $result[
                                'transaction_no'
                            ]
                        )
                        ) {
                            $paymentUpdates[
                                'refund_transaction_code'
                            ] =
                                $result[
                                    'transaction_no'
                                ];
                        }

                        if (
                            $confirmedAmount
                            >= (int) $lockedPayment
                                ->amount
                        ) {
                            $paymentUpdates[
                                'status'
                            ] = 'refunded';
                        }

                        $lockedPayment->update(
                            $paymentUpdates
                        );

                        $bookingPaymentStatus =
                            $refundTarget > 0
                            && $confirmedAmount
                            >= $refundTarget
                            ? (
                                $refundTarget
                                >= (int) $lockedPayment
                                    ->amount
                                ? 'refunded'
                                : 'partially_refunded'
                            )
                            : 'refund_pending';

                        $lockedBooking->update([
                            'payment_status' =>
                                $bookingPaymentStatus,
                        ]);

                        return;
                    }

                    /*
                     * VẪN ĐANG XỬ LÝ
                     */
                    if (
                        $result['state']
                        === 'processing'
                    ) {
                        $lockedPayment->update([
                            'refund_status' =>
                                'processing',

                            'refund_response_data' =>
                                $refundData,
                        ]);

                        $lockedBooking->update([
                            'payment_status' =>
                                'refund_pending',
                        ]);

                        return;
                    }

                    /*
                     * THẤT BẠI / BỊ TỪ CHỐI
                     */
                    $lockedPayment->update([
                        'refund_status' =>
                            'failed',

                        'refund_response_data' =>
                            $refundData,
                    ]);

                    $lockedBooking->update([
                        'payment_status' =>
                            'refund_failed',
                    ]);
                }
            );

            return back()->with(
                $result['state'] === 'failed'
                ? 'error'
                : 'success',

                match ($result['state']) {
                    'refunded' =>
                    'VNPAY xác nhận giao dịch đã được hoàn tiền thành công.',

                    'processing' =>
                    'VNPAY vẫn đang xử lý yêu cầu hoàn tiền.',

                    default =>
                    'Yêu cầu hoàn tiền chưa thành công.',
                }
            );

        } catch (RuntimeException $exception) {
            return back()->with(
                'error',
                $exception->getMessage()
            );

        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Không thể kiểm tra trạng thái hoàn tiền lúc này.'
            );
        }
    }

    /**
     * Thử gửi lại yêu cầu hoàn tiền VNPAY khi lần trước thất bại.
     */
    public function retryRefund(
        Request $request,
        Payment $payment,
        BookingCancellationService $cancellationService
    ): RedirectResponse {
        try {
            $result = $cancellationService->retryRefund(
                $payment,
                (string) $request->ip(),
                $this->refundCreatedBy()
            );

            $amount = number_format(
                (int) ($result['refund_amount'] ?? 0),
                0,
                ',',
                '.'
            ) . 'đ';

            return back()->with(
                $result['refund_state'] === 'failed' ? 'error' : 'success',
                match ($result['refund_state'] ?? 'failed') {
                    'refunded' => "Đã hoàn {$amount} qua VNPAY thành công.",
                    'processing' => "Yêu cầu hoàn {$amount} đang được VNPAY xử lý.",
                    default => "Chưa thể hoàn {$amount}. Vui lòng kiểm tra lại giao dịch và thử lại.",
                }
            );
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Không thể gửi lại yêu cầu hoàn tiền lúc này.'
            );
        }
    }

    /**
     * Cấu hình giao diện theo từng phương thức thanh toán.
     */
    private function paymentMethods(): array
    {
        return [
            'cash' => [
                'label' => 'Tiền mặt',
                'uses_gateway' => false,
                'uses_bank' => false,
            ],

            'bank_transfer' => [
                'label' => 'Banking',
                'uses_gateway' => false,
                'uses_bank' => true,
            ],

            'vnpay' => [
                'label' => 'VNPAY',
                'uses_gateway' => true,
                'uses_bank' => true,
            ],

            'momo' => [
                'label' => 'MoMo',
                'uses_gateway' => true,
                'uses_bank' => false,
            ],
        ];
    }

    private function refundCreatedBy(): string
    {
        $user = auth()->user();

        return (string) (
            $user?->email
            ?: $user?->name
            ?: 'HomeStayGo Admin'
        );
    }
}
