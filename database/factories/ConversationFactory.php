<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'topic_id' => Topic::all()->random()->id,
            'limit' => fake()->numberBetween(2, 10),
            'active' => fake()->boolean(),
            'created_by_id' => User::all()->random()->id,
            'created_at' => Carbon::now(),
        ];
    }
}
