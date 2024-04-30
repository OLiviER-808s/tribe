<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatMember>
 */
class ChatMemberFactory extends Factory
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
            'admin' => fake()->boolean(),
            'archived' => fake()->boolean(),
            'last_read_message_id' => null,
            'created_at' => Carbon::now()
        ];
    }
}
