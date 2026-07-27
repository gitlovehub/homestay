<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Nhà nguyên căn',
            'Biệt thự',
            'Căn hộ',
            'Nhà gỗ',
            'Nhà sàn',
            'Khu nghỉ dưỡng',
            'Biệt thự nghỉ dưỡng',
            'Nhà vườn',
            'Nhà phố',
            'Penthouse',
            'Nhà nghỉ',
            'Resort',
            'Villa ven biển',
        ];

        foreach ($categories as $categoryName) {
            Category::updateOrCreate(
                [
                    'slug' => Str::slug($categoryName),
                ],
                [
                    'name' => $categoryName,
                    'description' => 'Danh mục ' . $categoryName,
                    'status' => true,
                ]
            );
        }
    }
}