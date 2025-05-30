<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'description' => fake()->text(),
            'location' => fake()->address(),
            'category' => fake()->randomElement(['IT', 'Marketing', 'Finance', 'Healthcare']),
            'job_requirements' => json_encode([
                'requirement1' => fake()->word(),
                'requirement2' => fake()->word(),
                'requirement3' => fake()->word(),
            ]),
            'job_owner_id' =>User::where('role_id', 3)->inRandomOrder()->first()?->id,
            'deadline' => fake()->dateTimeBetween('now', '+1 month'),
            'posting_date' => now(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'JobPhoto' => fake()->imageUrl(640, 480, 'business', true),
            'budget' => fake()->numberBetween(100, 2000),
        ];
    }
}
