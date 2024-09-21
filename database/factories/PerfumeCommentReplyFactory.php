<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfumeCommentReply>
 */
class PerfumeCommentReplyFactory extends Factory
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
            'perfume_comment_id' => fake()->randomElement([1,2,3,4,5,6,7,8,9]),
            'reply' => fake()->text(50),

        ];
    }
}
