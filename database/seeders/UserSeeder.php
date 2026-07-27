<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'Quản trị viên',
                'phone' => '0901234567',
                'address' => 'Hà Nội',
                'avatar' => null,
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'user@gmail.com',
            ],
            [
                'name' => 'Trần Văn Tèo',
                'phone' => '0912345678',
                'address' => 'Đà Nẵng',
                'avatar' => null,
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::factory()
            ->count(48)
            ->create();
    }
}