<?php

namespace App\Http\Controllers;

use App\Constants\ConstMedia;
use App\Constants\ConstMessageTypes;
use App\Http\Requests\StoreChat;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
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
        $chats = $this->getUserChats()->get()->map(fn ($chat) => $chat->viewModel());

        return Inertia::render('Chats/ChatsIndex', [
            'chats' => $chats
        ]);
    }

    public function show($chatUuid, Request $request)
    {
        $chatsQuery = $this->getUserChats();
        $chats = clone $chatsQuery;

        $chat = $chatsQuery->where('uuid', $chatUuid)->with('messages.user')->firstOrFail();
        $messages = Message::where('chat_id', $chat->id)->orderBy('created_at', 'desc')->cursorPaginate(30);

        if ($request->wantsJson()) {
            return MessageResource::collection($messages);
        }

        $this->readMessages($chat);

        return Inertia::render('Chats/Chat', [
            'chats' => $chats->get()->map(fn ($chat) => $chat->viewModel()),
            'chat' => $chat->viewModel(false),
            'messages' => MessageResource::collection($messages),
        ]);
    }

    public function update($chatUuid, StoreChat $request)
    {
        $user = Auth::user();
        $chat = Chat::where('uuid', $chatUuid)
            ->whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('admin', true);
            })
            ->firstOrFail();

        DB::transaction(function () use ($chat, $user, $request) {
            if ($request['name'] != $chat->name) {
                Message::create([
                    'user_id' => $user->id,
                    'chat_id' => $chat->id,
                    'content' => 'changed the chat name to "' . $request['name'] . '"',
                    'type' => ConstMessageTypes::ACTION
                ]);

                $chat->name = $request['name'];
                $chat->save();
            }

            if ($request['photo']) {
                Message::create([
                    'user_id' => $user->id,
                    'chat_id' => $chat->id,
                    'content' => 'changed the chat photo',
                    'type' => ConstMessageTypes::ACTION
                ]);

                $chat->addMedia($request['photo'])->toMediaCollection(ConstMedia::CHAT_PHOTO);
            }
        });

        return to_route('chat.show', [
            'uuid' => $chat->uuid
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
            Message::create([
                'user_id' => $user->id,
                'chat_id' => $member->chat->id,
                'content' => 'left the chat',
                'type' => ConstMessageTypes::ACTION
            ]);

            $member->delete();
        });

        return to_route('chats');
    }

    public function archive($chatUuid)
    {
        $member = ChatMember::where('user_id', Auth::user()->id)
            ->whereHas('chat', function ($query) use ($chatUuid) {
                $query->where('uuid', $chatUuid);
            })
            ->firstOrFail();

        $member->archived = true;
        $member->save();

        return to_route('chats');
    }

    public function unarchive($chatUuid)
    {
        $member = ChatMember::where('user_id', Auth::user()->id)
            ->whereHas('chat', function ($query) use ($chatUuid) {
                $query->where('uuid', $chatUuid);
            })
            ->firstOrFail();

        $member->archived = false;
        $member->save();

        return to_route('chat.show', [
            'uuid' => $member->chat->uuid
        ]);
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
            Message::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'content' => 'removed ' . $member->user->name . ' from the chat',
                'type' => ConstMessageTypes::ACTION
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
