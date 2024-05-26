<?php

namespace Tests\Traits;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use App\Models\Topic;
use App\Models\User;
use Database\Seeders\TopicCategorySeeder;
use Database\Seeders\TopicSeeder;

trait UsesBasicTestSetup
{
    private User $testUser;
    private User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TopicCategorySeeder::class);
        $this->seed(TopicSeeder::class);

        $this->testUser = User::factory()->create();
        $this->testUser->interests()->saveMany(Topic::inRandomOrder()->take(5)->get());

        $this->otherUser = User::factory()->create();
        $this->otherUser->interests()->saveMany(Topic::inRandomOrder()->take(5)->get());

        $this->be($this->testUser);

        $this->extraSetup();
    }

    protected function extraSetup() {}

    private function setupConversation($topic)
    {
        $conversation = Conversation::factory()->create([
            'topic_id' => $topic->id,
            'created_by_id' => $this->otherUser->id,
            'active' => true
        ]);

        $chat = Chat::factory()->create([
            'conversation_id' => $conversation->id,
            'created_by_id' => $this->otherUser->id,
        ]);

        ChatMember::factory()->create([
            'chat_id' => $chat->id,
            'user_id' => $this->otherUser->id,
        ]);

        $conversation->refresh();

        return $conversation;
    }
}
