<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chats/ChatsIndex', [
            'chats' => $this->getUserChats()->get()->map(fn ($chat) => $chat->viewModel())
        ]);
    }

    public function show($chatUuid)
    {
        $chatsQuery = $this->getUserChats();
        $chats = clone $chatsQuery;

        $chat = $chatsQuery->where('uuid', $chatUuid)->with('messages.user')->firstOrFail();

        return Inertia::render('Chats/Chat', [
            'chats' => $chats->get()->map(fn ($chat) => $chat->viewModel()),
            'chat' => $chat->viewModel(false),
            'messages' => $chat->messages->map(fn ($message) => $message->viewModel()),
            'actions' => $chat->actions->map(fn ($action) => $action->viewModel()),
        ]);
    }

    private function getUserChats()
    {
        return Chat::whereHas('members', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
        ->addSelect(['latest_message_created_at' => Message::select('created_at')
            ->whereColumn('chat_id', 'chats.id')
            ->latest()
            ->take(1)
        ])
        ->orderBy('latest_message_created_at', 'desc')
        ->with(['members', 'latestMessage']);
    }
}
