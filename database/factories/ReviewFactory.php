<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'rating' => fake()->numberBetween(3, 5),

            'title' => fake()->randomElement([
                'Rất hài lòng',
                'Đáng để trải nghiệm',
                'Dịch vụ tốt',
                'Phòng sạch đẹp',
                'Sẽ quay lại',
                'Kỳ nghỉ tuyệt vời',
                'Không gian yên tĩnh',
            ]),

            'content' => fake()->randomElement([
                'Phòng sạch sẽ, nhân viên nhiệt tình.',
                'Homestay đẹp hơn trong ảnh.',
                'Khung cảnh đẹp và mức giá hợp lý.',
                'Gia đình mình rất hài lòng.',
                'Sẽ giới thiệu cho bạn bè.',
                'Không gian thoáng mát và yên tĩnh.',
                'Dịch vụ chu đáo, tiện nghi đầy đủ.',
            ]),

            'status' => fake()->randomElement([
                'approved',
                'approved',
                'approved',
                'pending',
                'hidden',
            ]),
        ];
    }
}