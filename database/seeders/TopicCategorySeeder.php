<?php

namespace Database\Seeders;

use App\Models\TopicCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TopicCategory::factory()->createMany([
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
    }
}
