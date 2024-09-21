<?php

namespace Database\Factories;

use App\Models\Perfume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfumeComment>
 */
class PerfumeCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->randomElement([1,2,3,4,5,6,7,8,9]),
            'perfume_id' => Perfume::factory()->create(),
            'comment' => fake()->text(59),
        ];
    }
}
