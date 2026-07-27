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
        $name = fake()->randomElement([
            'Homestay Bình Yên',
            'Nhà Gỗ Ven Rừng',
            'Villa Đồi Thông',
            'Homestay Mây Trắng',
            'Nhà Bên Hồ',
            'Homestay Hoa Hồng',
            'Villa Hoàng Hôn',
            'Nhà Gió Biển',
            'Homestay Xanh',
            'Nhà Trên Đồi',
            'Homestay An Nhiên',
            'Villa Bình Minh',
            'Nhà Lá',
            'Homestay Thung Lũng',
            'Nhà Ven Suối',
            'Homestay Hướng Biển',
            'Villa Mộc Châu',
            'Nhà Nghỉ Gia Đình',
            'Homestay Sương Sớm',
            'Villa Hoa Ban',
            'Nhà Gỗ Thông Xanh',
            'Homestay Đồi Cỏ',
            'Villa Phố Núi',
            'Homestay Gió Núi',
            'Nhà Vườn Bình An',
            'Homestay Cánh Đồng',
            'Villa Ánh Dương',
            'Homestay Bên Sông',
            'Nhà Gỗ Cao Nguyên',
            'Homestay Biển Xanh',
            'Villa Hạnh Phúc',
            'Homestay Thiên Nhiên',
            'Nhà Tre Việt',
            'Villa Rừng Thông',
            'Homestay Mặt Hồ',
            'Nhà Gỗ An Yên',
            'Homestay Phố Cổ',
            'Villa Mùa Thu',
            'Homestay Hoa Cỏ',
            'Nhà Bình Minh',
            'Homestay Gió Lộng',
            'Villa Bãi Biển',
            'Homestay Cát Trắng',
            'Nhà Ven Đồi',
            'Homestay Mộc',
            'Villa Thanh Bình',
            'Homestay Lá Xanh',
            'Nhà Hướng Núi',
            'Homestay Đêm Sao',
            'Villa Thiên Đường',
        ]) . ' ' . fake()->unique()->numberBetween(100, 9999);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'owner_id' => User::query()->inRandomOrder()->value('id'),

            'name' => $name,
            'slug' => Str::slug($name),

            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement([
                'Đà Lạt',
                'Sapa',
                'Hội An',
                'Phú Quốc',
                'Mộc Châu',
                'Nha Trang',
                'Đà Nẵng',
                'Vũng Tàu',
                'Hà Nội',
                'Hồ Chí Minh',
                'Ninh Bình',
                'Hà Giang',
                'Quy Nhơn',
                'Tam Đảo',
                'Cần Thơ',
                'Huế',
            ]),

            'phone' => fake()->numerify('09########'),
            'description' => fake()->paragraph(3),

            'image' => 'https://picsum.photos/seed/' .
                fake()->unique()->numberBetween(1, 99999) .
                '/800/500',

            'status' => true,
        ];
    }
}