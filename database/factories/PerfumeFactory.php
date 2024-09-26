<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perfume>
 */
class PerfumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_id' => fake()->randomElement([1,2,3,4,5]),
            'category_id' => fake()->randomElement([1,2,3,4]),
//            'discount_id' => fake()->randomElement([NULL,fake()->randomElement([1,2,3,4,5,6,7,8])]),
            'name' => fake()->name(),
            "price" => fake()->numberBetween(0,99999999),
            'volume' => fake()->numberBetween(10,150),
            'quantity' => fake()->numberBetween(0,500),
            'sold' => fake()->numberBetween(0,500),
            'description' => fake()->realText(),
            'slug' => fake()->slug(),
            'warranty' => fake()->randomElement([fake()->text(20),NULL]),
            'gender' => fake()->randomElement(['male','female','sport']),
            'is_active' => fake()->randomElement([TRUE,FALSE]),
        ];
    }
}
