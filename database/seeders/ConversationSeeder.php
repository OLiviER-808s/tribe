<?php

namespace Database\Seeders;

use App\Models\Conversation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversations = Conversation::factory()->count(100)->create([
            'active' => true
        ]);

        $tags = Tag::all();

        foreach ($conversations as $conversation) {
            $conversation->attachTag($tags->random()->name);
        }
    }
}
