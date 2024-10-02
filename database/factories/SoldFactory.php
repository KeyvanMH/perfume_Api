<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PerfumeSold>
 */
class SoldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_type' => 'perfume',
            'product_id' => 1,
            'user_id' => 1,
            'number' => 1,
            'price' => 1,
            'price_with_discount' => 1,
            'final_price' => 1,
            'delivery_price' => 1,
            'is_delivered' => 1,
        ];
    }
}
