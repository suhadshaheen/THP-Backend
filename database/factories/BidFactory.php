<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bid>
 */
class BidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bid_amount' => fake()->numberBetween(100, 2000),
            'work_time_line' => fake()->dateTimeBetween('now', '+1 month'),
            'job_id' => fake()->numberBetween(1, 20),
            'Freelancer_id' =>User::where('role_id', 2)->inRandomOrder()->first()?->id,
            'status' => fake()->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
