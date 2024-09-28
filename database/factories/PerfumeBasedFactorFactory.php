<?php

namespace Database\Factories;

use App\Models\Factor;
use App\Models\Perfume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfumeBasedFactor>
 */
class PerfumeBasedFactorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stock = rand(100,500);
        return [
            'factor_id' => fake()->randomElement([rand(1,3),Factor::factory()->create()]),
            'perfume_id' => fake()->randomElement([Perfume::factory()->create(),rand(1,10)]),
            'name' => fake()->text(40),
            'volume' => rand(20,100),
            'price' => rand(1000000,4000000),
            'stock' => $stock,
            'description' => fake()->realText(200),
            'sold' => $stock - rand(0,$stock),
            'is_active' => fake()->randomElement([true,true,true,false,true]),
            'slug' => fake()->slug(),
            'warranty'  => fake()->text(40),
            'gender' => fake()->randomElement(['male','female','sport'])
        ];
    }
}
