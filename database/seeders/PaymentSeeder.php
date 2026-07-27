<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $bookingCount = Booking::query()->count();

        Payment::factory()
            ->count($bookingCount)
            ->create();
    }
}