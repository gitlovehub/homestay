<?php

namespace Database\Factories;

use App\Models\Homestay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        $roomType = fake()->randomElement([
            'Phòng đơn',
            'Phòng đôi',
            'Phòng gia đình',
            'Phòng cao cấp',
            'Phòng hạng sang',
            'Nhà gỗ'
        ]);

        $capacity = match ($roomType) {
            'Phòng đơn' => 1,
            'Phòng đôi' => 2,
            'Phòng gia đình' => fake()->numberBetween(4, 6),
            'Phòng cao cấp' => fake()->numberBetween(2, 3),
            'Phòng hạng sang' => fake()->numberBetween(2, 4),
            'Nhà gỗ' => fake()->numberBetween(2, 5),
        };

        return [
            'homestay_id' => Homestay::query()
                ->inRandomOrder()
                ->value('id'),

            'name' => $roomType . ' ' . fake()->numberBetween(101, 999),

            'room_code' => strtoupper(fake()->unique()->bothify('R###??')),

            'room_type' => $roomType,

            'description' => fake()->randomElement([
                'Phòng sạch sẽ, thoáng mát và đầy đủ tiện nghi.',
                'Không gian rộng rãi, phù hợp cho kỳ nghỉ ngắn ngày.',
                'Phòng có thiết kế hiện đại và ánh sáng tự nhiên.',
                'Phòng yên tĩnh, phù hợp cho gia đình và nhóm bạn.',
            ]),

            'image' => null,

            'price_per_night' => fake()->randomElement([
                300000,
                450000,
                600000,
                800000,
                1000000,
                1200000,
                1500000,
                1800000,
                2200000,
                3000000,
            ]),

            'capacity' => $capacity,

            'number_of_beds' => match ($roomType) {
                'Phòng đơn' => 1,
                'Phòng đôi' => 1,
                'Phòng gia đình' => fake()->numberBetween(2, 3),
                'Phòng cao cấp' => fake()->numberBetween(1, 2),
                'Phòng hạng sang' => fake()->numberBetween(1, 2),
                'Nhà gỗ' => fake()->numberBetween(1, 3),
            },

            'area' => fake()->randomFloat(2, 18, 80),

            'status' => fake()->randomElement([
                'available',
                'available',
                'available',
                'maintenance',
                'inactive',
            ]),
        ];
    }
}