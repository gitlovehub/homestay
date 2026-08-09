<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class BookingCancellationService
{
    public function __construct(
        private readonly VnpayService $vnpayService
    ) {
    }

    public function cancelByCustomer(
        Booking $booking,
        string $reason,
        string $ipAddress,
        string $createdBy
    ): array {
        $prepared = DB::transaction(function () use ($booking, $reason): array {
            $lockedBooking = Booking::query()
                ->lockForUpdate()
                ->findOrFail($booking->id);

            if (
                !in_array(
                    $lockedBooking->status,
                    ['pending', 'confirmed'],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Trạng thái hiện tại của đơn không cho phép hủy.'
                );
            }

            if (
                !$lockedBooking->check_in
                || $lockedBooking->check_in
                    ->copy()
                    ->startOfDay()
                    ->lessThanOrEqualTo(
                        now('Asia/Ho_Chi_Minh')->startOfDay()
                    )
            ) {
                throw new RuntimeException(
                    'Đơn đã đến ngày nhận phòng nên không thể tự hủy.'
                );
            }

            $lockedBooking->payments()
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                ]);

            $paidPayment = $lockedBooking->payments()
                ->where('payment_method', 'vnpay')
                ->where('status', 'paid')
                ->latest('id')
                ->first();

            $refundPercentage = 0;
            $refundAmount = 0;

            if ($paidPayment) {
                $refundPercentage =
                    $lockedBooking
                        ->customerCancellationRefundPercentage();

                $refundAmount = (int) round(
                    (int) $paidPayment->amount
                    * $refundPercentage
                    / 100
                );
            }

            $paymentStatus = $lockedBooking->payment_status;

            if (!$paidPayment) {
                $paymentStatus = 'cancelled';
            } elseif ($refundAmount > 0) {
                $paymentStatus = 'refund_pending';
            }

            $lockedBooking->update([
                'status' => 'cancelled',
                'cancellation_reason' => trim($reason),
                'cancelled_at' => now('Asia/Ho_Chi_Minh'),
                'refund_amount' => $refundAmount,
                'payment_status' => $paymentStatus,
            ]);

            if ($paidPayment && $refundAmount > 0) {
                $paidPayment->update([
                    'refund_status' => 'pending',
                ]);
            }

            return [
                'booking_id' => $lockedBooking->id,
                'payment_id' => $paidPayment?->id,
                'payment_amount' => $paidPayment
                    ? (int) $paidPayment->amount
                    : 0,
                'refund_percentage' => $refundPercentage,
                'refund_amount' => $refundAmount,
                'payment_option' => $lockedBooking->payment_option,
            ];
        });

        if (
            !$prepared['payment_id']
            || $prepared['refund_amount'] <= 0
        ) {
            return $prepared + [
                'refund_state' => 'not_required',
            ];
        }

        $payment = Payment::query()
            ->with('booking')
            ->findOrFail($prepared['payment_id']);

        try {
            $refund = $this->vnpayService->refundPayment(
                $payment,
                $prepared['refund_amount'],
                $ipAddress,
                $createdBy
            );
        } catch (Throwable $exception) {
            Log::error(
                'Không thể gửi yêu cầu hoàn tiền VNPAY.',
                [
                    'booking_id' => $prepared['booking_id'],
                    'payment_id' => $prepared['payment_id'],
                    'message' => $exception->getMessage(),
                    'exception' => $exception,
                ]
            );

            DB::transaction(function () use ($prepared): void {
                Payment::query()
                    ->whereKey($prepared['payment_id'])
                    ->update([
                        'refund_status' => 'failed',
                    ]);

                Booking::query()
                    ->whereKey($prepared['booking_id'])
                    ->update([
                        'payment_status' => 'refund_failed',
                    ]);
            });

            return $prepared + [
                'refund_state' => 'failed',
            ];
        }

        DB::transaction(function () use ($prepared, $refund): void {
            $lockedPayment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($prepared['payment_id']);

            $lockedBooking = Booking::query()
                ->lockForUpdate()
                ->findOrFail($prepared['booking_id']);

            $paymentUpdates = [
                'refund_status' => $refund['state'],
                'refund_request_id' => $refund['request_id'],
                'refund_transaction_code' =>
                    $refund['transaction_no'],
                'refund_response_data' => $refund['payload'],
            ];

            if ($refund['state'] === 'refunded') {
                $paymentUpdates['refunded_amount'] =
                    (int) $lockedPayment->refunded_amount
                    + $prepared['refund_amount'];

                $paymentUpdates['refunded_at'] =
                    now('Asia/Ho_Chi_Minh');
            }

            $lockedPayment->update($paymentUpdates);

            $bookingPaymentStatus = match ($refund['state']) {
                'refunded' =>
                $prepared['refund_amount']
                >= $prepared['payment_amount']
                ? 'refunded'
                : 'partially_refunded',
                'processing' => 'refund_pending',
                default => 'refund_failed',
            };

            $lockedBooking->update([
                'payment_status' => $bookingPaymentStatus,
            ]);
        });

        return $prepared + [
            'refund_state' => $refund['state'],
            'refund_response_code' => $refund['response_code'],
        ];
    }

    public function cancelByAdmin(
        Booking $booking,
        string $reason,
        string $ipAddress,
        string $createdBy
    ): array {
        $prepared = DB::transaction(function () use ($booking, $reason): array {

            $lockedBooking = Booking::query()
                ->lockForUpdate()
                ->findOrFail($booking->id);

            if (
                !in_array(
                    $lockedBooking->status,
                    ['pending', 'confirmed'],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Chỉ có thể hủy đơn đang chờ xác nhận hoặc đã xác nhận.'
                );
            }

            // Hủy các giao dịch đang chờ
            $lockedBooking->payments()
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                ]);

            // Tìm giao dịch VNPay đã thanh toán
            $paidPayment = $lockedBooking->payments()
                ->where('payment_method', 'vnpay')
                ->where('status', 'paid')
                ->latest('id')
                ->first();

            $refundAmount = 0;

            if ($paidPayment) {
                $refundAmount = max(
                    0,
                    (int) $paidPayment->amount
                    - (int) $paidPayment->refunded_amount
                );
            }

            $paymentStatus = $lockedBooking->payment_status;

            if (!$paidPayment) {
                $paymentStatus = 'cancelled';
            } elseif ($refundAmount > 0) {
                $paymentStatus = 'refund_pending';
            }

            $cancelReason = trim($reason);

            if ($cancelReason === '') {
                $cancelReason =
                    'Homestay/Quản trị viên chủ động hủy đơn.';
            }

            $lockedBooking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $cancelReason,
                'cancelled_at' => now('Asia/Ho_Chi_Minh'),
                'refund_amount' => $refundAmount,
                'payment_status' => $paymentStatus,
            ]);

            if ($paidPayment && $refundAmount > 0) {
                $paidPayment->update([
                    'refund_status' => 'pending',
                ]);
            }

            return [
                'booking_id' => $lockedBooking->id,

                'payment_id' => $paidPayment?->id,

                'payment_amount' => $paidPayment
                    ? (int) $paidPayment->amount
                    : 0,

                'refund_percentage' => $paidPayment ? 100 : 0,

                'refund_amount' => $refundAmount,

                'payment_option' => $lockedBooking->payment_option,
            ];
        });

        // Không có tiền cần hoàn
        if (
            !$prepared['payment_id']
            || $prepared['refund_amount'] <= 0
        ) {
            return $prepared + [
                'refund_state' => 'not_required',
            ];
        }

        $payment = Payment::query()
            ->with('booking')
            ->findOrFail($prepared['payment_id']);

        try {
            $refund = $this->vnpayService->refundPayment(
                $payment,
                $prepared['refund_amount'],
                $ipAddress,
                $createdBy
            );
        } catch (Throwable $exception) {

            Log::error(
                'Không thể gửi yêu cầu hoàn tiền VNPAY.',
                [
                    'booking_id' => $prepared['booking_id'],
                    'payment_id' => $prepared['payment_id'],
                    'message' => $exception->getMessage(),
                ]
            );

            DB::transaction(function () use ($prepared): void {

                Payment::query()
                    ->whereKey($prepared['payment_id'])
                    ->update([
                        'refund_status' => 'failed',
                    ]);

                Booking::query()
                    ->whereKey($prepared['booking_id'])
                    ->update([
                        'payment_status' => 'refund_failed',
                    ]);
            });

            return $prepared + [
                'refund_state' => 'failed',
            ];
        }

        DB::transaction(function () use ($prepared, $refund): void {

            $lockedPayment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($prepared['payment_id']);

            $lockedBooking = Booking::query()
                ->lockForUpdate()
                ->findOrFail($prepared['booking_id']);

            $newRefundedAmount =
                (int) $lockedPayment->refunded_amount;

            $paymentUpdates = [
                'refund_status' => $refund['state'],

                'refund_request_id' =>
                    $refund['request_id'],

                'refund_transaction_code' =>
                    $refund['transaction_no'],

                'refund_response_data' =>
                    $refund['payload'],
            ];

            if ($refund['state'] === 'refunded') {

                $newRefundedAmount +=
                    $prepared['refund_amount'];

                $paymentUpdates['refunded_amount'] =
                    $newRefundedAmount;

                $paymentUpdates['refunded_at'] =
                    now('Asia/Ho_Chi_Minh');

                if (
                    $newRefundedAmount
                    >= (int) $lockedPayment->amount
                ) {
                    $paymentUpdates['status'] = 'refunded';
                }
            }

            $lockedPayment->update($paymentUpdates);

            $bookingPaymentStatus = match (
            $refund['state']
            ) {
                'refunded' =>
                $newRefundedAmount
                >= (int) $lockedPayment->amount
                ? 'refunded'
                : 'partially_refunded',

                'processing' => 'refund_pending',

                default => 'refund_failed',
            };

            $lockedBooking->update([
                'payment_status' => $bookingPaymentStatus,
            ]);
        });

        return $prepared + [
            'refund_state' => $refund['state'],

            'refund_response_code' =>
                $refund['response_code'],
        ];
    }

    public function retryRefund(
        Payment $payment,
        string $ipAddress,
        string $createdBy
    ): array {
        $prepared = DB::transaction(function () use ($payment): array {

            $lockedPayment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($payment->id);

            $lockedBooking = Booking::query()
                ->lockForUpdate()
                ->findOrFail($lockedPayment->booking_id);

            if ($lockedBooking->status !== 'cancelled') {
                throw new RuntimeException(
                    'Booking chưa ở trạng thái đã hủy.'
                );
            }

            if ($lockedPayment->payment_method !== 'vnpay') {
                throw new RuntimeException(
                    'Chỉ có thể hoàn lại giao dịch VNPAY.'
                );
            }

            if ($lockedPayment->status !== 'paid') {
                throw new RuntimeException(
                    'Giao dịch hiện không đủ điều kiện để hoàn tiền.'
                );
            }

            if (
                in_array(
                    $lockedPayment->refund_status,
                    ['pending', 'processing'],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Yêu cầu hoàn tiền đang được VNPAY xử lý.'
                );
            }

            $refundTarget = min(
                (int) $lockedBooking->refund_amount,
                (int) $lockedPayment->amount
            );

            $alreadyRefunded =
                (int) $lockedPayment->refunded_amount;

            $refundAmount = max(
                0,
                $refundTarget - $alreadyRefunded
            );

            if ($refundAmount <= 0) {
                throw new RuntimeException(
                    'Giao dịch không còn số tiền cần hoàn.'
                );
            }

            $lockedPayment->update([
                'refund_status' => 'pending',
            ]);

            $lockedBooking->update([
                'payment_status' => 'refund_pending',
            ]);

            return [
                'booking_id' => $lockedBooking->id,
                'payment_id' => $lockedPayment->id,
                'payment_amount' => (int) $lockedPayment->amount,
                'refund_amount' => $refundAmount,
                'refund_target' => $refundTarget,
            ];
        });

        $payment = Payment::query()
            ->with('booking')
            ->findOrFail($prepared['payment_id']);

        try {
            $refund = $this->vnpayService->refundPayment(
                $payment,
                $prepared['refund_amount'],
                $ipAddress,
                $createdBy
            );
        } catch (Throwable $exception) {

            Log::error(
                'Không thể gửi lại yêu cầu hoàn tiền VNPAY.',
                [
                    'booking_id' => $prepared['booking_id'],
                    'payment_id' => $prepared['payment_id'],
                    'message' => $exception->getMessage(),
                ]
            );

            DB::transaction(function () use ($prepared): void {

                Payment::query()
                    ->whereKey($prepared['payment_id'])
                    ->update([
                        'refund_status' => 'failed',
                    ]);

                Booking::query()
                    ->whereKey($prepared['booking_id'])
                    ->update([
                        'payment_status' => 'refund_failed',
                    ]);
            });

            return $prepared + [
                'refund_state' => 'failed',
            ];
        }

        DB::transaction(function () use ($prepared, $refund): void {

            $lockedPayment = Payment::query()
                ->lockForUpdate()
                ->findOrFail($prepared['payment_id']);

            $lockedBooking = Booking::query()
                ->lockForUpdate()
                ->findOrFail($prepared['booking_id']);

            $newRefundedAmount =
                (int) $lockedPayment->refunded_amount;

            $paymentUpdates = [
                'refund_status' => $refund['state'],

                'refund_request_id' =>
                    $refund['request_id'],

                'refund_transaction_code' =>
                    $refund['transaction_no'],

                'refund_response_data' =>
                    $refund['payload'],
            ];

            if ($refund['state'] === 'refunded') {

                $newRefundedAmount +=
                    $prepared['refund_amount'];

                $newRefundedAmount = min(
                    $newRefundedAmount,
                    (int) $lockedPayment->amount
                );

                $paymentUpdates['refunded_amount'] =
                    $newRefundedAmount;

                $paymentUpdates['refunded_at'] =
                    now('Asia/Ho_Chi_Minh');

                if (
                    $newRefundedAmount
                    >= (int) $lockedPayment->amount
                ) {
                    $paymentUpdates['status'] = 'refunded';
                }
            }

            $lockedPayment->update($paymentUpdates);

            $bookingPaymentStatus = match (
            $refund['state']
            ) {
                'refunded' =>
                $newRefundedAmount
                >= $prepared['refund_target']
                ? (
                    $prepared['refund_target']
                    >= (int) $lockedPayment->amount
                    ? 'refunded'
                    : 'partially_refunded'
                )
                : 'refund_pending',

                'processing' =>
                'refund_pending',

                default =>
                'refund_failed',
            };

            $lockedBooking->update([
                'payment_status' =>
                    $bookingPaymentStatus,
            ]);
        });

        return $prepared + [
            'refund_state' => $refund['state'],
            'refund_response_code' =>
                $refund['response_code'],
        ];
    }
}
