<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       do {
        $sender = User::inRandomOrder()->first();
        $receiver = User::inRandomOrder()->first();

        $valid = match ([$sender->role_id, $receiver->role_id]) {
            [2,3],[2,3]=> true,
            default => false,
        };
    } while (
        $sender->id === $receiver->id || !$valid
    );
        return [
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'content' => fake()->sentence(),
        'TimeForMessage' => fake()->dateTimeBetween('now', '+1 month'),
        ];

    }
}
