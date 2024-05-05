<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TopicCategorySeeder::class,
            TopicSeeder::class,
            UserSeeder::class,
            ConversationSeeder::class,
        ]);
    }
}
