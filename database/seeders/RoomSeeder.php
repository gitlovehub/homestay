<?php

namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Homestay::query()->each(function (Homestay $homestay) {
            Room::factory()
                ->count(4)
                ->create([
                    'homestay_id' => $homestay->id,
                ]);
        });
    }
}