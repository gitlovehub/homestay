<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            AmenitySeeder::class,
            HomestaySeeder::class,
            RoomSeeder::class,
            PromotionSeeder::class,
            BookingSeeder::class,
            PaymentSeeder::class,
            ReviewSeeder::class,
            HomestayImageSeeder::class,
        ]);
    }
}