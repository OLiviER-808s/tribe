<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversation;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function create()
    {
        return Inertia::render('Conversations/ConversationCreate');
    }

    public function store(StoreConversation $request)
    {
        DB::transaction(function () use ($request) {
            $conversation = Conversation::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'limit' => $request['limit'],
                'active' => true
            ]);

            $chat = Chat::create([
                'name' => $request['title'],
                'conversation_id' => $conversation->id
            ]);

            ChatMember::create([
                'user_id' => Auth::user()->id,
                'chat_id' => $chat->id,
                'admin' => true
            ]);
        });

        return to_route('home');
    }
}
