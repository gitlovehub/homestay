<?php

namespace Database\Seeders;

use App\Models\Homestay;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::truncate();

        $homestays = Homestay::where('status', true)
            ->get();

        foreach ($homestays as $homestay) {
            Room::factory()
                ->count(rand(3, 6))
                ->create([
                    'homestay_id' => $homestay->id,
                ]);
        }
    }
}