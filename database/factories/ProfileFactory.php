<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 20),
            'User_Photo' => fake()->imageUrl(640, 480, 'people', true),
            'bio' => fake()->text(),
            'skills' => json_encode([
                'skill1' => fake()->word(),
                'skill2' => fake()->word(),
                'skill3' => fake()->word(),
            ]),

        ];
    }
}
