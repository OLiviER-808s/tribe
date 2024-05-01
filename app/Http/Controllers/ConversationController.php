<?php

namespace App\Http\Controllers;

use App\Constants\ConstMessageTypes;
use App\Http\Requests\StoreConversation;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\TagCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function create()
    {
        $categories = TagCategory::with('tags')->get();

        return Inertia::render('Conversations/ConversationCreate', [
            'categories' => $categories->map(fn ($category) => $category->viewModel())
        ]);
    }

    public function store(StoreConversation $request)
    {
        DB::transaction(function () use ($request) {
            $user = Auth::user();

            $conversation = Conversation::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'limit' => $request['limit'] + 1,
                'active' => true
            ]);
            $conversation->syncTags([ $request['category'] ]);

            $chat = Chat::create([
                'name' => $request['title'],
                'conversation_id' => $conversation->id
            ]);

            ChatMember::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'admin' => true
            ]);

            Message::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'content' => 'created the chat',
                'type' => ConstMessageTypes::ACTION
            ]);
        });

        return to_route('home');
    }

    public function join($conversationUuid)
    {
        $user = Auth::user();

        $conversation = Conversation::where('uuid', $conversationUuid)
            ->where('active', true)
            ->whereDoesntHave('chat.members', function ($query) use ($user) {
                $query->withTrashed()->where('user_id', $user->id);
            })
            ->with('chat.members')
            ->firstOrFail();
        $chat = $conversation->chat;

        DB::transaction(function () use ($conversation, $chat, $user) {
            if ($chat->members->count() + 1 === $conversation->limit) {
                $conversation->active = false;
                $conversation->save();
            }

            ChatMember::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'admin' => false
            ]);

            Message::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'content' => 'joined the chat',
                'type' => ConstMessageTypes::ACTION
            ]);
        });

        return to_route('chat.show', [
            'uuid' => $chat->uuid
        ]);
    }
}
