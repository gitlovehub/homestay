<?php

namespace Database\Factories;

use App\Models\HomestayImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class HomestayImageFactory extends Factory
{
    protected $model = HomestayImage::class;

    public function definition(): array
    {
        return [
            'image_path' => fake()->randomElement([
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200',
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200',
                'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200',
                'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200',
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200',
                'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=1200',
                'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=1200',
                'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=1200',
            ]),

            'alt_text' => fake()->randomElement([
                'Toàn cảnh Homestay',
                'Phòng ngủ của Homestay',
                'Phòng khách của Homestay',
                'Khu vực sân vườn',
                'Ban công có không gian thoáng mát',
                'Khu vực hồ bơi',
                'Khu vực bếp',
            ]),

            'sort_order' => 0,
            'is_primary' => false,
        ];
    }
}