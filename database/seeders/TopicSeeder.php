<?php

namespace Database\Seeders;

use App\Models\Topic;
use App\Models\TopicCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Topic::factory()->createMany([
            [
                'label' => 'Bowling',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
            ],
            [
                'label' => 'Programming',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
            ],
            [
                'label' => 'Fashion',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
            ],
            [
                'label' => 'Cooking',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
            ],
            [
                'label' => 'Fitness',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
            ],
            [
                'label' => 'Travel',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
            ],
            [
                'label' => 'Videogames',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
            ],
            [
                'label' => 'Music',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
            ],
            [
                'label' => 'Movies',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
            ],
            [
                'label' => 'Reading',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
            ],
            [
                'label' => 'Basketball',
                'category_id' => TopicCategory::where('name', 'Sport')->first()->id,
            ],
            [
                'label' => 'Soccer',
                'category_id' => TopicCategory::where('name', 'Sport')->first()->id,
            ],
            [
                'label' => 'Business',
                'category_id' => TopicCategory::where('name', 'News')->first()->id,
            ],
            [
                'label' => 'Politics',
                'category_id' => TopicCategory::where('name', 'News')->first()->id,
            ],
        ]);

        Topic::factory()->createMany([
            [
                'label' => 'Bodybuilding',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
                'parent_id' => Topic::where('label', 'Fitness')->first()->id
            ],
            [
                'label' => 'Sneakers',
                'category_id' => TopicCategory::where('name', 'Hobbies')->first()->id,
                'parent_id' => Topic::where('label', 'Fashion')->first()->id
            ],
            [
                'label' => 'NBA',
                'category_id' => TopicCategory::where('name', 'Sport')->first()->id,
                'parent_id' => Topic::where('label', 'Basketball')->first()->id
            ],
            [
                'label' => 'Hip-Hop',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
                'parent_id' => Topic::where('label', 'Music')->first()->id
            ],
            [
                'label' => 'Rock',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
                'parent_id' => Topic::where('label', 'Music')->first()->id
            ],
            [
                'label' => 'Pop',
                'category_id' => TopicCategory::where('name', 'Entertainment')->first()->id,
                'parent_id' => Topic::where('label', 'Music')->first()->id
            ],
        ]);
    }
}
