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
        $roomTypes = [
            'Phòng đơn',
            'Phòng đôi',
            'Phòng tiêu chuẩn',
            'Phòng cao cấp',
            'Phòng gia đình',
            'Phòng VIP',
        ];

        $roomType = fake()->randomElement($roomTypes);

        $price = match ($roomType) {
            'Phòng đơn' => fake()->randomElement([
                250000,
                300000,
                350000,
                400000,
            ]),

            'Phòng đôi' => fake()->randomElement([
                450000,
                500000,
                550000,
                600000,
            ]),

            'Phòng tiêu chuẩn' => fake()->randomElement([
                650000,
                700000,
                750000,
                800000,
            ]),

            'Phòng cao cấp' => fake()->randomElement([
                900000,
                1000000,
                1100000,
                1300000,
            ]),

            'Phòng gia đình' => fake()->randomElement([
                1500000,
                1700000,
                1900000,
                2200000,
            ]),

            'Phòng VIP' => fake()->randomElement([
                2500000,
                2800000,
                3000000,
                3500000,
            ]),

            default => 500000,
        };

        $capacity = match ($roomType) {
            'Phòng đơn' => 1,
            'Phòng đôi' => 2,
            'Phòng tiêu chuẩn' => fake()->numberBetween(1, 2),
            'Phòng cao cấp' => fake()->numberBetween(2, 3),
            'Phòng gia đình' => fake()->numberBetween(4, 6),
            'Phòng VIP' => fake()->numberBetween(2, 4),
            default => 2,
        };

        return [
            'homestay_id' => Homestay::query()
                ->inRandomOrder()
                ->value('id'),

            'name' => 'Phòng ' . fake()->unique()->numberBetween(101, 9999),

            'room_type' => $roomType,

            'price' => $price,

            'capacity' => $capacity,

            'status' => true,
        ];
    }
}