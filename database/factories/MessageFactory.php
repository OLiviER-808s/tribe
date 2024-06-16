<?php

namespace Database\Factories;

use App\Constants\ConstTypes;
use App\Constants\ConstStatus;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'chat_id' => Chat::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'content' => fake()->sentence(),
            'status' => ConstStatus::MESSAGE_SENT,
            'type' => ConstTypes::MESSAGE,
        ];
    }
}
