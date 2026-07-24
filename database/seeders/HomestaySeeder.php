<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Homestay;
use Illuminate\Database\Seeder;

class HomestaySeeder extends Seeder
{
    public function run(): void
    {
        $homestays = Homestay::factory()
            ->count(30)
            ->create();

        $amenityIds = Amenity::query()->pluck('id');

        foreach ($homestays as $homestay) {
            $randomAmenityIds = $amenityIds
                ->shuffle()
                ->take(random_int(4, 8))
                ->all();

            $homestay->amenities()->sync($randomAmenityIds);
        }
    }
}