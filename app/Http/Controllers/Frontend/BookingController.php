<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingCancellationService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;

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

        $paymentOption = (string) $request->input(
            'payment_option',
            Booking::PAYMENT_OPTION_VNPAY_FULL
        );

        if (!in_array(
            $paymentOption,
            [
                Booking::PAYMENT_OPTION_VNPAY_FULL,
                Booking::PAYMENT_OPTION_CASH_DEPOSIT,
            ],
            true
        )) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment_option' =>
                        'Phương thức thanh toán không hợp lệ.',
                ]);
        }

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

        $checkIn = Carbon::parse(
            $data['check_in'],
            'Asia/Ho_Chi_Minh'
        );
        $checkOut = Carbon::parse(
            $data['check_out'],
            'Asia/Ho_Chi_Minh'
        );

        /*
         * Đơn đặt trước ngày check-in từ 30 ngày trở lên phải
         * thanh toán toàn bộ qua VNPAY. Không chỉ ẩn lựa chọn ở UI,
         * backend vẫn chặn để tránh sửa request thủ công.
         */
        $requiresFullVnpay = $checkIn
            ->copy()
            ->startOfDay()
            ->greaterThanOrEqualTo(
                now('Asia/Ho_Chi_Minh')
                    ->startOfDay()
                    ->addDays(
                        Booking::FULL_VNPAY_REQUIRED_FROM_DAYS
                    )
            );

        if (
            $paymentOption
                === Booking::PAYMENT_OPTION_CASH_DEPOSIT
            && $requiresFullVnpay
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'payment_option' =>
                        'Đơn đặt trước từ 30 ngày trở lên phải thanh toán toàn bộ qua VNPAY.',
                ]);
        }

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
            'payment_option' => $paymentOption,
            'refund_amount' => 0,
        ]);

        return redirect()
            ->route('bookings.payment.show', $booking)
            ->with(
                'success',
                $paymentOption === Booking::PAYMENT_OPTION_CASH_DEPOSIT
                    ? 'Đặt phòng thành công. Vui lòng thanh toán cọc 10% qua VNPAY để giữ chỗ.'
                    : 'Đặt phòng thành công. Vui lòng thanh toán toàn bộ qua VNPAY để hoàn tất giữ chỗ.'
            );
    }

    public function history(Request $request)
    {
        $allowedFilters = [
            'pending',
            'need_payment',
            'confirmed',
            'checked_in',
            'completed',
            'cancelled',
            'needs_review',
        ];

        $filter = (string) $request->query('filter', '');

        if (!in_array($filter, $allowedFilters, true)) {
            $filter = '';
        }

        $bookings = Booking::query()
            ->with([
                'room.homestay',
                'reviews:id,booking_id,status,review_number,rating,title,content,edited_at',
            ])
            ->where('user_id', auth()->id())
            ->when(
                in_array(
                    $filter,
                    [
                        'pending',
                        'confirmed',
                        'checked_in',
                        'completed',
                        'cancelled',
                    ],
                    true
                ),
                fn ($query) => $query->where('status', $filter)
            )
            ->when(
                $filter === 'needs_review',
                fn ($query) => $query
                    ->where('status', 'completed')
                    ->whereDoesntHave('reviews')
            )
            ->when(
                $filter === 'need_payment',
                fn ($query) => $query->whereIn(
                    'payment_status',
                    [
                        'unpaid',
                        'failed',
                    ]
                )
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'bookings.history',
            compact('bookings')
        );
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

    public function cancel(
        Request $request,
        Booking $booking,
        BookingCancellationService $cancellationService
    ): RedirectResponse {
        abort_unless(
            (int) $booking->user_id === (int) auth()->id(),
            403
        );

        $validated = $request->validate(
            [
                'cancellation_reason' => [
                    'required',
                    'string',
                    'min:5',
                    'max:500',
                ],
            ],
            [
                'cancellation_reason.required' =>
                    'Vui lòng nhập lý do hủy đặt phòng.',
                'cancellation_reason.min' =>
                    'Lý do hủy phải có ít nhất 5 ký tự.',
                'cancellation_reason.max' =>
                    'Lý do hủy không được vượt quá 500 ký tự.',
            ]
        );

        try {
            $result = $cancellationService->cancelByCustomer(
                $booking,
                $validated['cancellation_reason'],
                (string) $request->ip(),
                (string) (auth()->user()?->name ?? 'Khach hang')
            );
        } catch (RuntimeException $exception) {
            return back()->with(
                'error',
                $exception->getMessage()
            );
        }

        $refundAmount = (int) $result['refund_amount'];
        $refundState = $result['refund_state'];

        $message = match (true) {
            $refundAmount <= 0
                && $result['payment_option']
                    === Booking::PAYMENT_OPTION_CASH_DEPOSIT
                => 'Hủy đặt phòng thành công. Tiền cọc 10% không được hoàn lại.',

            $refundAmount <= 0
                => 'Hủy đặt phòng thành công. Đơn này không phát sinh tiền hoàn.',

            $refundState === 'refunded'
                => 'Hủy đặt phòng thành công. Đã gửi hoàn '
                    . number_format($refundAmount, 0, ',', '.')
                    . 'đ qua VNPAY.',

            $refundState === 'processing'
                => 'Hủy đặt phòng thành công. Khoản hoàn '
                    . number_format($refundAmount, 0, ',', '.')
                    . 'đ đang được VNPAY xử lý.',

            default
                => 'Đơn đã được hủy nhưng yêu cầu hoàn tiền chưa xử lý thành công. Vui lòng liên hệ hỗ trợ.',
        };

        return redirect()
            ->route('bookings.show', $booking)
            ->with(
                $refundState === 'failed' ? 'error' : 'success',
                $message
            );
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