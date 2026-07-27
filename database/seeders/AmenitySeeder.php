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
            ['name' => 'Wi-Fi miễn phí', 'icon' => '🌐'],
            ['name' => 'Điều hòa', 'icon' => '❄️'],
            ['name' => 'Bãi đỗ xe', 'icon' => '🚗'],
            ['name' => 'Hồ bơi', 'icon' => '🏊'],
            ['name' => 'Nhà bếp', 'icon' => '♨️'],
            ['name' => 'Máy giặt', 'icon' => '🧺'],
            ['name' => 'Bữa sáng', 'icon' => '🍜'],
            ['name' => 'Ban công', 'icon' => '🌇'],
            ['name' => 'Tivi', 'icon' => '📺'],
            ['name' => 'Tủ lạnh', 'icon' => '🧊'],
            ['name' => 'Sân vườn', 'icon' => '🏕️'],
            ['name' => 'Bình nóng lạnh', 'icon' => '💧'],
            ['name' => 'Cho phép thú cưng', 'icon' => '🐾'],
            ['name' => 'Phòng không hút thuốc', 'icon' => '🚭'],
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