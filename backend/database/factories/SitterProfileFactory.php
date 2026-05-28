<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SitterProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bio' => fake()->paragraph(),
            'city' => fake()->city(),
            'zip' => fake()->postcode(),
            'lat' => fake()->latitude(47, 55),   // Deutschland-Bereich
            'lng' => fake()->longitude(6, 15),
            'care_type' => fake()->randomElement(['private', 'pension']),
            'price_halfday' => fake()->randomFloat(2, 10, 40),
            'price_fullday' => fake()->randomFloat(2, 20, 70),
            'dog_sizes' => fake()->randomElements(['small', 'medium', 'large'], 2),
            'is_active' => true,
        ];
    }
}
