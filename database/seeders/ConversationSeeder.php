<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $conversations = Conversation::factory()->count(100)->create([
                'active' => true
            ]);
    
            $tags = Tag::all();
    
            foreach ($conversations as $conversation) {
                $conversation->attachTag($tags->random()->name);

                $chat = Chat::factory()->create([
                    'conversation_id' => $conversation->id,
                    'created_by_id' => $conversation->created_by_id,
                    'name' => $conversation->title
                ]);

                ChatMember::factory()->create([
                    'chat_id' => $chat->id,
                    'user_id' => $chat->created_by_id,
                    'admin' => true
                ]);
            }
        });
    }
}