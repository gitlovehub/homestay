<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Homestay>
 */
class HomestayFactory extends Factory
{
    public function definition(): array
    {
        $city = fake()->randomElement([
            'Đà Lạt',
            'Đà Nẵng',
            'Hội An',
            'Sa Pa',
            'Nha Trang',
            'Phú Quốc',
            'Hà Nội',
            'TP. Hồ Chí Minh',
        ]);

        $name = fake()->randomElement([
            'Green Hill Villa',
            'Cloud House',
            'Sunshine Homestay',
            'Dream Valley',
            'Misty Forest',
            'Lavender House',
            'Rose Garden',
            'Blue Ocean Villa',
            'Moonlight Homestay',
            'Happy Farm House',
            'Lake View Homestay',
            'Sky House',
            'Mountain Home',
            'Forest Retreat',
            'Peaceful Garden',
        ]) . ' ' . fake()->unique()->numberBetween(1, 9999);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'),

            'owner_id' => User::query()
                ->where('role', 'admin')
                ->orWhere('role', 'user')
                ->inRandomOrder()
                ->value('id'),

            'name' => $name,
            'slug' => Str::slug($name),

            'address' => fake()->streetAddress(),
            'city' => $city,
            'phone' => fake()->numerify('09########'),

            'description' => fake()->randomElement([
                'Không gian yên tĩnh, gần trung tâm và phù hợp cho gia đình.',
                'Homestay có thiết kế hiện đại, đầy đủ tiện nghi.',
                'Không gian thoáng mát, gần các địa điểm du lịch nổi tiếng.',
                'Phù hợp cho nhóm bạn, cặp đôi và chuyến nghỉ dưỡng ngắn ngày.',
                'Khu nghỉ dưỡng có cảnh quan đẹp và dịch vụ chu đáo.',
            ]),

            'base_price' => fake()->randomElement([
                350000,
                450000,
                550000,
                750000,
                900000,
                1200000,
                1500000,
                2000000,
                2500000,
                3500000,
                5000000,
            ]),

            'latitude' => fake()->latitude(8, 23),
            'longitude' => fake()->longitude(102, 110),

            'check_in_time' => '14:00:00',
            'check_out_time' => '12:00:00',

            'policy' => fake()->randomElement([
                'Không hút thuốc trong phòng. Không gây ồn sau 22 giờ.',
                'Không mang theo vật nuôi nếu chưa được chủ nhà đồng ý.',
                'Giữ gìn tài sản và vệ sinh chung trong thời gian lưu trú.',
            ]),

            'thumbnail' => null,
            'image' => null,

            'status' => true,
        ];
    }
}