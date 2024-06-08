<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $conversations = Conversation::factory()
                ->count(100)
                ->state(new Sequence(fn (Sequence $sequence) => [
                    'created_at' => Carbon::now()->addDays($sequence->index),
                ]))
                ->create([
                    'active' => true,
                ]);

            foreach ($conversations as $conversation) {
                $chat = Chat::factory()->create([
                    'conversation_id' => $conversation->id,
                    'created_by_id' => $conversation->created_by_id,
                    'name' => $conversation->title,
                    'created_at' => $conversation->created_at,
                ]);

                ChatMember::factory()->create([
                    'chat_id' => $chat->id,
                    'user_id' => $chat->created_by_id,
                    'admin' => true,
                    'created_at' => $conversation->created_at,
                ]);
            }
        });
    }
}
