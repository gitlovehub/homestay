<?php

namespace Database\Factories;

use App\Models\Promotion;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $room = Room::query()
            ->where('status', 'available')
            ->inRandomOrder()
            ->first();

        $user = User::query()
            ->where('role', 'user')
            ->inRandomOrder()
            ->first();

        $promotion = fake()->boolean(35)
            ? Promotion::query()
                ->where('status', true)
                ->inRandomOrder()
                ->first()
            : null;

        $checkIn = fake()->dateTimeBetween('-2 months', '+2 months');
        $numberOfNights = fake()->numberBetween(1, 7);

        $checkOut = (clone $checkIn)->modify("+{$numberOfNights} days");

        $roomPrice = $room->price_per_night;
        $subtotal = $roomPrice * $numberOfNights;
        $serviceFee = (int) round($subtotal * 0.05);

        $discountAmount = 0;

        if ($promotion && $subtotal >= $promotion->min_order_value) {
            if ($promotion->discount_type === 'percent') {
                $discountAmount = (int) round(
                    $subtotal * ($promotion->discount_value / 100)
                );

                if ($promotion->max_discount !== null) {
                    $discountAmount = min(
                        $discountAmount,
                        $promotion->max_discount
                    );
                }
            }

            if ($promotion->discount_type === 'fixed') {
                $discountAmount = min(
                    (int) $promotion->discount_value,
                    $subtotal
                );
            }
        }

        $totalPrice = max(
            0,
            $subtotal + $serviceFee - $discountAmount
        );

        $status = fake()->randomElement([
            'pending',
            'confirmed',
            'confirmed',
            'completed',
            'completed',
            'cancelled',
        ]);

        $paymentStatus = match ($status) {
            'completed' => 'paid',
            'cancelled' => fake()->randomElement([
                'pending',
                'refunded',
            ]),
            default => fake()->randomElement([
                'pending',
                'paid',
            ]),
        };

        return [
            'booking_code' => 'DP-' . strtoupper(Str::random(8)),

            'user_id' => $user->id,
            'room_id' => $room->id,
            'promotion_id' => $promotion?->id,

            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,

            'check_in' => $checkIn,
            'check_out' => $checkOut,

            'number_of_guests' => fake()->numberBetween(
                1,
                max(1, $room->capacity)
            ),

            'number_of_nights' => $numberOfNights,

            'room_price' => $roomPrice,
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,

            'note' => fake()->optional(40)->randomElement([
                'Khách muốn nhận phòng sớm nếu có thể.',
                'Ưu tiên phòng yên tĩnh.',
                'Khách đi cùng trẻ nhỏ.',
                'Cần hỗ trợ chỗ đỗ xe.',
                'Khách muốn phòng có ban công.',
            ]),

            'cancellation_reason' => $status === 'cancelled'
                ? fake()->randomElement([
                    'Khách thay đổi kế hoạch.',
                    'Khách đặt nhầm ngày.',
                    'Khách không thể tiếp tục chuyến đi.',
                    'Khách muốn đổi sang loại phòng khác.',
                ])
                : null,

            'cancelled_at' => $status === 'cancelled'
                ? now()
                : null,

            'status' => $status,
            'payment_status' => $paymentStatus,
        ];
    }
}