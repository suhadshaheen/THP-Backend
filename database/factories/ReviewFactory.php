<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bid_id' => fake()->numberBetween(1, 20),
            'reviewer_id' =>User::where('role_id', 3)->inRandomOrder()->first()?->id,
            'rating' => fake()->numberBetween(1, 5),
            'review_text' => fake()->text(),
        ];
    }
}
