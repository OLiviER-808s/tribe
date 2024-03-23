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
        $user = Auth::user();

        $chats = Chat::whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('members.user')
        ->get()
        ->map(fn ($chat) => $chat->viewModel())
        ->toArray();

        return Inertia::render('Chats/ChatsIndex', [
            'chats' => $chats
        ]);
    }
}
