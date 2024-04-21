<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatAction;
use App\Models\ChatMember;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $this->readMessages($chat);

        return Inertia::render('Chats/Chat', [
            'chats' => $chats->get()->map(fn ($chat) => $chat->viewModel()),
            'chat' => $chat->viewModel(false),
            'messages' => $chat->messages->map(fn ($message) => $message->viewModel()),
            'actions' => $chat->actions->map(fn ($action) => $action->viewModel()),
        ]);
    }

    public function leave($chatUuid)
    {
        $user = Auth::user();
        $member = ChatMember::where('user_id', $user->id)
            ->whereHas('chat', function ($query) use ($chatUuid) {
                $query->where('uuid', $chatUuid);
            })
            ->with('chat')
            ->firstOrFail();

        DB::transaction(function () use ($member, $user) {
            ChatAction::create([
                'chat_id' => $member->chat->id,
                'text' => $user->name . ' left the chat'
            ]);

            $member->delete();
        });

        return to_route('chats');
    }

    public function removeMember($chatUuid, $memberUuid)
    {
        $user = Auth::user();
        $chat = Chat::where('uuid', $chatUuid)
            ->whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('admin', true);
            })
            ->with('members.user')
            ->firstOrFail();
        
        $member = $chat->members->where('uuid', $memberUuid)->firstOrFail();
        
        DB::transaction(function () use ($chat, $member, $user) {
            ChatAction::create([
                'chat_id' => $chat->id,
                'text' => $user->name . ' removed ' . $member->user->name . ' from the chat'
            ]);

            $member->delete();
        });
    }

    public function assignAdmin($chatUuid, $memberUuid)
    {
        $user = Auth::user();
        $chat = Chat::where('uuid', $chatUuid)
            ->whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('admin', true);
            })
            ->with('members.user')
            ->firstOrFail();
        
        $member = $chat->members->where('uuid', $memberUuid)->firstOrFail();
        
        $member->admin = true;
        $member->save();
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

    private function readMessages($chat)
    {
        $authMember = $chat->members->where('user_id', Auth::user()->id)->first();

        $authMember->last_read_message_id = $chat->latestMessage?->id;
        $authMember->save();
    }
}
