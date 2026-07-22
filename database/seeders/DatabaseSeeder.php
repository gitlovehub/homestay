<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\Homestay;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tạo tài khoản mẫu
        |--------------------------------------------------------------------------
        */

        $admin = User::factory()->create([
            'name' => 'Admin Homestay',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        User::factory(9)->create();

        /*
        |--------------------------------------------------------------------------
        | Tạo danh mục Homestay
        |--------------------------------------------------------------------------
        */

        $categoryNames = [
            'Nhà nguyên căn',
            'Villa',
            'Bungalow',
            'Căn hộ',
            'Nhà gỗ',
            'Phòng riêng',
        ];

        foreach ($categoryNames as $categoryName) {
            Category::query()->create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => 'Danh mục ' . $categoryName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tạo tiện nghi
        |--------------------------------------------------------------------------
        */

        $amenityNames = [
            'Wi-Fi miễn phí',
            'Điều hòa',
            'Bãi đỗ xe',
            'Hồ bơi',
            'Nhà bếp',
            'Máy giặt',
            'Bữa sáng',
            'Ban công',
            'BBQ',
            'Xe đạp miễn phí',
            'Đưa đón sân bay',
            'Thang máy',
        ];

        foreach ($amenityNames as $amenityName) {
            Amenity::query()->create([
                'name' => $amenityName,
                'icon' => null,
                'description' => 'Tiện nghi ' . $amenityName,
                'status' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tạo Homestay giả
        |--------------------------------------------------------------------------
        */

        Homestay::factory(50)->create([
            'owner_id' => $admin->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Gắn tiện nghi ngẫu nhiên cho từng Homestay
        |--------------------------------------------------------------------------
        */

        $amenityIds = Amenity::query()->pluck('id');

        Homestay::query()->each(function (Homestay $homestay) use ($amenityIds) {
            $randomAmenityIds = $amenityIds
                ->shuffle()
                ->take(random_int(3, 6))
                ->all();

            $homestay->amenities()->attach($randomAmenityIds);
        });
        
        $this->call([
            RoomSeeder::class,
        ]);
    }
}