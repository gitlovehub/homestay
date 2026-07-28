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
        $query = Booking::query()
            ->with([
                'user',
                'room.homestay',
            ]);

        // Tìm theo mã đơn, tên khách, email hoặc số điện thoại
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái Booking
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->input('payment_status')
            );
        }

        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.bookings.index',
            compact('bookings')
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