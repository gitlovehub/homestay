<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create(Room $room)
    {
        $room->load('homestay');

        abort_unless(
            $room->status === 'available'
            && $room->homestay
            && $room->homestay->status,
            404
        );

        return view('bookings.create', compact('room'));
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();

        $room = Room::query()
            ->with('homestay')
            ->findOrFail($data['room_id']);

        if (
            $room->status !== 'available'
            || !$room->homestay
            || !$room->homestay->status
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'room_id' => 'Phòng hiện không thể đặt.',
                ]);
        }

        if ($data['number_of_guests'] > $room->capacity) {
            return back()
                ->withInput()
                ->withErrors([
                    'number_of_guests' =>
                        "Phòng chỉ chứa tối đa {$room->capacity} người.",
                ]);
        }

        $hasConflict = Booking::query()
            ->where('room_id', $room->id)
            ->whereIn('status', [
                'pending',
                'confirmed',
                'checked_in',
            ])
            ->where(function ($query) use ($data) {
                $query
                    ->where('check_in', '<', $data['check_out'])
                    ->where('check_out', '>', $data['check_in']);
            })
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->withErrors([
                    'check_in' =>
                        'Phòng đã được đặt trong khoảng thời gian này.',
                ]);
        }

        $checkIn = Carbon::parse($data['check_in']);
        $checkOut = Carbon::parse($data['check_out']);

        $numberOfNights = $checkIn->diffInDays($checkOut);
        $roomPrice = $room->price_per_night;
        $subtotal = $roomPrice * $numberOfNights;
        $serviceFee = 0;
        $discountAmount = 0;
        $totalPrice = $subtotal + $serviceFee - $discountAmount;

        $booking = Booking::create([
            'booking_code' => $this->generateBookingCode(),

            'user_id' => auth()->id(),
            'room_id' => $room->id,
            'promotion_id' => null,

            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],

            'check_in' => $checkIn,
            'check_out' => $checkOut,

            'number_of_guests' => $data['number_of_guests'],
            'number_of_nights' => $numberOfNights,

            'room_price' => $roomPrice,
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,

            'note' => $data['note'] ?? null,

            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with(
                'success',
                'Đặt phòng thành công. Booking đang chờ xác nhận.'
            );
    }

    public function history()
    {
        $bookings = Booking::query()
            ->with([
                'room.homestay',
                'reviews:id,booking_id,status',
            ])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_unless(
            $booking->user_id === auth()->id(),
            403
        );

        $booking->load([
            'room.homestay',
            'promotion',
            'payment',
        ]);

        return view('bookings.show', compact('booking'));
    }

    private function generateBookingCode(): string
    {
        do {
            $code = 'BK-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));
        } while (
            Booking::query()
                ->where('booking_code', $code)
                ->exists()
        );

        return $code;
    }
}