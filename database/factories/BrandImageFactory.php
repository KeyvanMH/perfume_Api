<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrandImage>
 */
class BrandImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_id' => random_int(1,10),
            'image_path' => fake()->filePath(),
            'alt' => fake()->text(30),
            'extension' => fake()->mimeType(),
            'size' => '20000'
        ];
    }
}
