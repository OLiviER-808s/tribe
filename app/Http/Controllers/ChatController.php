<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        return Inertia::render('Chats/ChatsIndex', [
            'chats' => $this->getUserChats()->get()->map(fn ($chat) => $chat->viewModel())->toArray()
        ]);
    }

    public function show($chatUuid)
    {
        $chats = $this->getUserChats();
        $chat = $chats->where('uuid', $chatUuid)->with('messages.user')->firstOrFail();

        return Inertia::render('Chats/Chat', [
            'chats' => $this->getUserChats()->get()->map(fn ($chat) => $chat->viewModel())->toArray(),
            'chat' => $chat->viewModel(),
            'messages' => $chat->messages->map(fn ($message) => $message->viewModel())->toArray()
        ]);
    }

    private function getUserChats()
    {
        return Chat::whereHas('members', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })
        ->with('members.user');
    }
}
