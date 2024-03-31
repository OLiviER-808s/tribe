<?php

namespace Database\Seeders;

use App\Models\TagCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagCategorySeeder extends Seeder
{
    public function run(): void
    {
        TagCategory::factory()->createMany([
            [
                'name' => 'Hobbies'
            ],
            [
                'name' => 'Entertainment'
            ],
            [
                'name' => 'Sport'
            ],
            [
                'name' => 'News'
            ],
        ]);

        TagCategory::where('name', 'Hobbies')->first()->attachTags([
            'Bowling',
            'Programming',
            'Fashion',
            'Cooking',
            'Fitness',
            'Travel',
            'Bodybuilding',
            'Sneakers'
        ]);

        TagCategory::where('name', 'Entertainment')->first()->attachTags([
            'Videogames',
            'Music',
            'Movies',
            'Reading'
        ]);

        TagCategory::where('name', 'Sport')->first()->attachTags([
            'Basketball',
            'Football'
        ]);

        TagCategory::where('name', 'News')->first()->attachTags([
            'Business',
            'Politics'
        ]);
    }
}
