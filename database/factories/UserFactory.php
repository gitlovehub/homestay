<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),

            'phone' => fake()->unique()->numerify('09########'),
            'address' => fake()->randomElement([
                'Hà Nội',
                'Đà Nẵng',
                'Đà Lạt',
                'TP. Hồ Chí Minh',
                'Nha Trang',
                'Hội An',
                'Sa Pa',
                'Phú Quốc',
            ]),

            'avatar' => null,

            'password' => static::$password ??= Hash::make('12345678'),

            'role' => 'user',
            'status' => fake()->randomElement([
                'active',
                'active',
                'active',
                'inactive',
            ]),

            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'status' => 'active',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}