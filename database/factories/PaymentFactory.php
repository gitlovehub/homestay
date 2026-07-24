<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $booking = Booking::query()
            ->whereDoesntHave('payment')
            ->inRandomOrder()
            ->first();

        $paymentMethod = fake()->randomElement([
            'cash',
            'bank_transfer',
            'vnpay',
            'momo',
        ]);

        $status = match ($booking->payment_status) {
            'paid' => 'paid',
            'refunded' => 'refunded',
            default => fake()->randomElement([
                'pending',
                'failed',
            ]),
        };

        return [
            'booking_id' => $booking->id,

            'transaction_code' => 'GD-' . strtoupper(Str::random(10)),

            'payment_method' => $paymentMethod,

            'amount' => $booking->total_price,

            'status' => $status,

            'paid_at' => $status === 'paid'
                ? now()
                : null,

            'response_data' => null,
        ];
    }
}