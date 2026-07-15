<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Homestay>
 */
class HomestayFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Pine Hill Homestay',
            'Cloud Valley House',
            'Green Garden Homestay',
            'Sunset Riverside',
            'Mountain View Lodge',
            'Ocean Breeze House',
            'Little Forest Home',
            'Peaceful Lake Homestay',
            'Rose Garden Villa',
            'Happy Stay House',
        ]) . ' ' . fake()->unique()->numberBetween(100, 9999);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'owner_id' => User::query()->inRandomOrder()->value('id'),

            'name' => $name,
            'slug' => Str::slug($name),

            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement([
                'Đà Lạt',
                'Sapa',
                'Hội An',
                'Phú Quốc',
                'Nha Trang',
                'Mộc Châu',
            ]),

            'phone' => fake()->numerify('09########'),
            'description' => fake()->paragraph(3),

            'image' => 'https://picsum.photos/seed/' .
                fake()->unique()->numberBetween(1, 99999) .
                '/800/500',

            'status' => true,
        ];
    }
}