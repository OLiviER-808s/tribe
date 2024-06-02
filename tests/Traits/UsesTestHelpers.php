<?php

namespace Tests\Traits;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\TopicCategorySeeder;
use Database\Seeders\TopicSeeder;
use Illuminate\Support\Facades\Auth;

trait UsesTestHelpers
{
    private function setupConversation($topic, $active = true)
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create([
            'topic_id' => $topic->id,
            'created_by_id' => $user->id,
            'active' => $active
        ]);

        $chat = Chat::factory()->create([
            'conversation_id' => $conversation->id,
            'created_by_id' => $user->id,
        ]);

        ChatMember::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
        ]);

        $conversation->refresh();

        return $conversation;
    }

    public function setupChat($creator, ...$users)
    {
        $chat = Chat::factory()->create([
            'created_by_id' => $creator->id,
        ]);

        ChatMember::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $creator->id,
            'admin' => true
        ]);

        foreach ($users as $user) {
            ChatMember::factory()->create([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
            ]);
        }

        $chat->refresh();

        return $chat;
    }
}
