<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $from = fake()->dateTimeBetween('+1 days', '+30 days');
        $to = fake()->dateTimeBetween($from, '+40 days');

        return [
            'owner_id' => User::factory(),
            'sitter_id' => User::factory(),
            'dog_name' => fake()->firstName(),
            'dog_size' => fake()->randomElement(['small', 'medium', 'large']),
            'from_date' => $from->format('Y-m-d'),
            'to_date' => $to->format('Y-m-d'),
            'message' => fake()->sentence(),
            'status' => 'pending',
        ];
    }
}
