<?php

namespace Tests\Traits;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;

trait UsesTestHelpers
{
    private function setupConversation($active = true, $topic = null, $user = null, $limit = 4)
    {
        if (! $topic) {
            $topic = Topic::all()->random();
        }

        if (! $user) {
            $user = User::factory()->create();
        }

        $conversation = Conversation::factory()->create([
            'topic_id' => $topic->id,
            'created_by_id' => $user->id,
            'limit' => $limit,
            'active' => $active,
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
            'admin' => true,
        ]);

        foreach ($users as $user) {
            ChatMember::factory()->create([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'admin' => false,
            ]);
        }

        $chat->refresh();

        return $chat;
    }

    public function addMessages($chat, $user = null, $count = 5)
    {
        return Message::factory()
            ->count($count)
            ->state(new Sequence(fn (Sequence $sequence) => [
                'created_at' => Carbon::now()->addMinutes($sequence->index),
            ]))
            ->create([
                'chat_id' => $chat->id,
                'user_id' => $user->id ?? $chat->members->random()->user->id,
            ]);
    }

    public function addSearches($model, $count): void
    {
        $users = User::factory()->count($count)->create();

        foreach ($users as $user) {
            $model->addSearchRecord($user->id);
        }
    }
}
