<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name
        ];
    }
}
