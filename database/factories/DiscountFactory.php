<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'percent' => fake()->randomFloat(4,0,100),
            'amount' => fake()->numberBetween(0,99999999),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
            'end_date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
            'status' => fake()->randomElement(['active', 'inactive', 'expired']),
        ];
    }
}
