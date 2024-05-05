<?php

namespace Database\Factories;

use App\Models\TopicCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word(),
            'emoji' => fake()->emoji(),
            'category_id' => TopicCategory::all()->random()->id,
            'parent_id' => null
        ];
    }
}
