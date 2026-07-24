<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Wi-Fi miễn phí', 'icon' => 'wifi'],
            ['name' => 'Điều hòa', 'icon' => 'air-conditioner'],
            ['name' => 'Bãi đỗ xe', 'icon' => 'parking'],
            ['name' => 'Hồ bơi', 'icon' => 'pool'],
            ['name' => 'Nhà bếp', 'icon' => 'kitchen'],
            ['name' => 'Máy giặt', 'icon' => 'washing-machine'],
            ['name' => 'Bữa sáng', 'icon' => 'breakfast'],
            ['name' => 'Ban công', 'icon' => 'balcony'],
            ['name' => 'Tivi', 'icon' => 'tv'],
            ['name' => 'Tủ lạnh', 'icon' => 'refrigerator'],
            ['name' => 'Sân vườn', 'icon' => 'garden'],
            ['name' => 'Bình nóng lạnh', 'icon' => 'water-heater'],
            ['name' => 'Cho phép thú cưng', 'icon' => 'pet'],
            ['name' => 'Phòng không hút thuốc', 'icon' => 'no-smoking'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                [
                    'slug' => Str::slug($amenity['name']),
                ],
                [
                    'name' => $amenity['name'],
                    'icon' => $amenity['icon'],
                    'description' => 'Tiện nghi ' . $amenity['name'],
                    'status' => true,
                ]
            );
        }
    }
}