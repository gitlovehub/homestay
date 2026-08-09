<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingCancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class BookingController extends Controller
{
    /**
     * Hiển thị danh sách Booking.
     */
    public function index(Request $request): View
    {
        $allowedStatuses = [
            'pending',
            'confirmed',
            'checked_in',
            'completed',
            'cancelled',
            'no_show',
        ];

        $allowedPaymentStatuses = [
            'unpaid',
            'pending',
            'paid',
            'deposit_paid',
            'refund_pending',
            'partially_refunded',
            'refunded',
            'refund_failed',
            'failed',
            'cancelled',
        ];

        $allowedSorts = [
            'latest',
            'oldest',
            'total_desc',
            'total_asc',
            'check_in_asc',
            'check_in_desc',
        ];

        $status = in_array($request->input('status'), $allowedStatuses, true)
            ? $request->input('status')
            : null;

        $paymentStatus = in_array(
            $request->input('payment_status'),
            $allowedPaymentStatuses,
            true
        )
            ? $request->input('payment_status')
            : null;

        $sort = in_array($request->input('sort'), $allowedSorts, true)
            ? $request->input('sort')
            : 'latest';

        $query = Booking::query()
            ->with([
                'user',
                'room.homestay',
                'payment',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($bookingQuery) use ($search) {
                $bookingQuery
                    ->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhereHas('room', function ($roomQuery) use ($search) {
                        $roomQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('room.homestay', function ($homestayQuery) use ($search) {
                        $homestayQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status !== null) {
            $query->where('status', $status);
        }

        if ($paymentStatus !== null) {
            $query->where('payment_status', $paymentStatus);
        }

        match ($sort) {
            'oldest' => $query
                ->orderBy('created_at')
                ->orderBy('id'),

            'total_desc' => $query
                ->orderByDesc('total_price')
                ->orderByDesc('id'),

            'total_asc' => $query
                ->orderBy('total_price')
                ->orderBy('id'),

            'check_in_asc' => $query
                ->orderBy('check_in')
                ->orderBy('id'),

            'check_in_desc' => $query
                ->orderByDesc('check_in')
                ->orderByDesc('id'),

            default => $query
                ->orderByDesc('created_at')
                ->orderByDesc('id'),
        };

        $bookings = $query
            ->paginate(10)
            ->withQueryString();

        $statistics = [
            'total' => Booking::query()->count(),

            'pending' => Booking::query()
                ->where('status', 'pending')
                ->count(),

            'in_progress' => Booking::query()
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->count(),

            'completed' => Booking::query()
                ->where('status', 'completed')
                ->count(),
        ];

        return view(
            'admin.bookings.index',
            compact('bookings', 'statistics')
        );
    }

    /**
     * Hiển thị chi tiết Booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load([
            'user',
            'room.homestay',
            'payment',
            'payments',
        ]);

        return view(
            'admin.bookings.show',
            compact('booking')
        );
    }

    /**
     * Cập nhật trạng thái Booking.
     *
     * Admin hủy Booking => hoàn 100% số tiền khách đã thanh toán qua VNPAY.
     * No-show => không hoàn tiền.
     */
    public function updateStatus(
        Request $request,
        Booking $booking,
        BookingCancellationService $cancellationService
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'status' => [
                    'required',
                    'in:confirmed,checked_in,completed,cancelled,no_show',
                ],
                'cancellation_reason' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'status.required' => 'Vui lòng chọn trạng thái Booking.',
                'status.in' => 'Trạng thái Booking không hợp lệ.',
                'cancellation_reason.max' => 'Lý do hủy không được vượt quá 1000 ký tự.',
            ]
        );

        $newStatus = $validated['status'];

        $allowedTransitions = [
            'pending' => [
                'confirmed',
                'cancelled',
            ],

            'confirmed' => [
                'checked_in',
                'cancelled',
                'no_show',
            ],

            'checked_in' => [
                'completed',
            ],

            'completed' => [],
            'cancelled' => [],
            'no_show' => [],
        ];

        $currentStatus = $booking->status;

        $isAllowed = in_array(
            $newStatus,
            $allowedTransitions[$currentStatus] ?? [],
            true
        );

        if (!$isAllowed) {
            return back()->with(
                'error',
                'Không thể chuyển từ trạng thái hiện tại sang trạng thái đã chọn.'
            );
        }

        try {
            if ($newStatus === 'cancelled') {
                $result = $cancellationService->cancelByAdmin(
                    $booking,
                    (string) ($validated['cancellation_reason'] ?? ''),
                    (string) $request->ip(),
                    $this->refundCreatedBy()
                );

                return back()->with(
                    $result['refund_state'] === 'failed' ? 'error' : 'success',
                    $this->adminCancellationMessage($result)
                );
            }

            if ($newStatus === 'no_show') {
                $cancellationService->markNoShow($booking);

                return back()->with(
                    'success',
                    'Đã đánh dấu khách không đến nhận phòng. Đơn no-show không phát sinh hoàn tiền.'
                );
            }

            $booking->update([
                'status' => $newStatus,
            ]);

            $message = match ($newStatus) {
                'confirmed' => 'Đã xác nhận đơn đặt phòng.',
                'checked_in' => 'Đã cập nhật khách nhận phòng.',
                'completed' => 'Đơn đặt phòng đã hoàn thành.',
                default => 'Cập nhật trạng thái thành công.',
            };

            return back()->with('success', $message);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Không thể cập nhật Booking lúc này. Vui lòng thử lại.'
            );
        }
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

    private function adminCancellationMessage(array $result): string
    {
        $refundAmount = (int) ($result['refund_amount'] ?? 0);
        $formattedAmount = number_format($refundAmount, 0, ',', '.') . 'đ';

        return match ($result['refund_state'] ?? 'not_required') {
            'refunded' =>
                "Đã hủy Booking và hoàn {$formattedAmount} cho khách qua VNPAY.",

            'processing' =>
                "Đã hủy Booking. Yêu cầu hoàn {$formattedAmount} đang được VNPAY xử lý.",

            'failed' =>
                "Booking đã được hủy nhưng yêu cầu hoàn {$formattedAmount} chưa thành công. Hãy mở chi tiết giao dịch để thử hoàn lại.",

            default =>
                'Đã hủy Booking. Đơn chưa có khoản thanh toán cần hoàn.',
        };
    }
}
