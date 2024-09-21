<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfumeImage>
 */
class PerfumeImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'perfume_id' => fake()->randomElement([1,2,3,4,5,6,7,8]),
            'image_path' => fake()->filePath(),
            'alt' => fake()->streetName(),
            'extension' => fake()->fileExtension(),
            'size' => fake()->numberBetween(5000)

        ];
    }
}
