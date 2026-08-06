<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        ];

        $allowedPaymentStatuses = [
            'unpaid',
            'pending',
            'paid',
            'refunded',
            'failed',
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
        ]);

        return view(
            'admin.bookings.show',
            compact('booking')
        );
    }

    /**
     * Cập nhật trạng thái Booking.
     */
    public function updateStatus(
        Request $request,
        Booking $booking
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'status' => [
                    'required',
                    'in:confirmed,checked_in,completed,cancelled',
                ],
            ],
            [
                'status.required' => 'Vui lòng chọn trạng thái Booking.',
                'status.in' => 'Trạng thái Booking không hợp lệ.',
            ]
        );

        $newStatus = $validated['status'];

        /*
        |--------------------------------------------------------------------------
        | Quy trình trạng thái hợp lệ
        |--------------------------------------------------------------------------
        |
        | pending    → confirmed hoặc cancelled
        | confirmed  → checked_in hoặc cancelled
        | checked_in → completed
        | completed  → không được đổi
        | cancelled  → không được đổi
        |
        */

        $allowedTransitions = [
            'pending' => [
                'confirmed',
                'cancelled',
            ],

            'confirmed' => [
                'checked_in',
                'cancelled',
            ],

            'checked_in' => [
                'completed',
            ],

            'completed' => [],

            'cancelled' => [],
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

        $updateData = [
            'status' => $newStatus,
        ];

        if ($newStatus === 'cancelled') {
            $updateData['cancelled_at'] = now();
        }

        $booking->update($updateData);

        $message = match ($newStatus) {
            'confirmed' => 'Đã xác nhận đơn đặt phòng.',
            'checked_in' => 'Đã cập nhật khách nhận phòng.',
            'completed' => 'Đơn đặt phòng đã hoàn thành.',
            'cancelled' => 'Đã hủy đơn đặt phòng.',
            default => 'Cập nhật trạng thái thành công.',
        };

        return back()->with('success', $message);
    }
}