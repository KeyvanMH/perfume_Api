<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'logo_path' => fake()->filePath(),
            'link' => fake()->text(20),
            'description' => fake()->realText(200),
            'title' => fake()->title(),
            'slug' => fake()->slug(),
        ];
    }
}
