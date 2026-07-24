<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::query()
            ->where('status', 'completed')
            ->with('room')
            ->get();

        foreach ($bookings as $booking) {
            Review::factory()->create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'homestay_id' => $booking->room->homestay_id,
                'review_number' => 1,
            ]);

            if (fake()->boolean(30)) {
                Review::factory()->create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'homestay_id' => $booking->room->homestay_id,
                    'review_number' => 2,
                    'rating' => fake()->numberBetween(4, 5),
                    'title' => 'Đánh giá sau khi được hỗ trợ',
                    'content' => fake()->randomElement([
                        'Sau khi được hỗ trợ, mình cảm thấy hài lòng hơn.',
                        'Quản trị viên đã xử lý vấn đề rất nhanh.',
                        'Vấn đề đã được giải quyết và trải nghiệm tốt hơn.',
                        'Dịch vụ hỗ trợ tốt nên mình đánh giá lại tích cực hơn.',
                    ]),
                    'status' => 'approved',
                ]);
            }
        }
    }
}