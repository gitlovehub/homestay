<?php

namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\HomestayImage;
use Illuminate\Database\Seeder;

class HomestayImageSeeder extends Seeder
{
    public function run(): void
    {
        Homestay::query()->each(function (Homestay $homestay) {
            $imageCount = fake()->numberBetween(3, 6);

            for ($i = 1; $i <= $imageCount; $i++) {
                HomestayImage::factory()->create([
                    'homestay_id' => $homestay->id,
                    'sort_order' => $i,
                    'is_primary' => $i === 1,
                ]);
            }
        });
    }
}