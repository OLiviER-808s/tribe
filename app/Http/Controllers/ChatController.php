<?php

namespace App\Http\Controllers;

use App\Constants\ConstChatActions;
use App\Constants\ConstMedia;
use App\Constants\ConstMessageTypes;
use App\Http\Requests\StoreChat;
use App\Http\Resources\MessageResource;
use App\Jobs\ReadMessage;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use App\Traits\UsesChatActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ChatController extends Controller
{
    use UsesChatActions;

    public function index(Request $request): array|Response
    {
        $chats = $this->getUserChats();

        if ($request->wantsJson()) {
            $chats = $chats->where('name', 'LIKE', '%' . $request->input('search') . '%');

            return [
                'chats' => $chats->get()->map(fn ($chat) => $chat->listViewModel())
            ];
        }

        return Inertia::render('Chats/ChatsIndex', [
            'chats' => $chats->get()->map(fn ($chat) => $chat->listViewModel())
        ]);
    }

    public function show($chatUuid, Request $request): AnonymousResourceCollection|Response
    {
        $chatsQuery = $this->getUserChats();
        $chats = clone $chatsQuery;

        $chat = $chatsQuery->where('uuid', $chatUuid)->with('messages.user')->firstOrFail();
        $messages = Message::where('chat_id', $chat->id)->orderBy('created_at', 'desc')->cursorPaginate(30);

        if ($request->wantsJson()) {
            return MessageResource::collection($messages);
        }

        ReadMessage::dispatch($messages->first(), [ $chat->members->where('user_id', Auth::user()->id)->first()->uuid ]);

        return Inertia::render('Chats/Chat', [
            'chats' => $chats->get()->map(fn ($chat) => $chat->listViewModel()),
            'chat' => $chat->viewModel(false),
            'messages' => MessageResource::collection($messages),
        ]);
    }

    public function update($chatUuid, StoreChat $request): RedirectResponse
    {
        $user = Auth::user();
        $chat = Chat::where('uuid', $chatUuid)
            ->whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('admin', true);
            })
            ->firstOrFail();

        DB::transaction(function () use ($chat, $user, $request) {
            if ($request['name'] != $chat->name) {
                $chat->name = $request['name'];
                $chat->save();

                $this->newChatAction($chat->id, 'changed the chat name to "' . $request['name'] . '"');
            }

            if ($request['photo']) {
                $chat->addMedia($request['photo'])->toMediaCollection(ConstMedia::CHAT_PHOTO);
                $this->newChatAction($chat->id, ConstChatActions::PHOTO_CHANGED);
            }
        });

        return to_route('chat.show', [
            'uuid' => $chat->uuid
        ]);
    }

    public function leave($chatUuid): RedirectResponse
    {
        $user = Auth::user();
        $member = ChatMember::where('user_id', $user->id)
            ->whereHas('chat', function ($query) use ($chatUuid) {
                $query->where('uuid', $chatUuid);
            })
            ->with('chat')
            ->firstOrFail();

        DB::transaction(function () use ($member, $user) {
            $member->delete();
            $this->newChatAction($member->chat->id, ConstChatActions::USER_LEFT);
        });

        return to_route('chats');
    }

    public function archive($chatUuid): RedirectResponse
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

    public function unarchive($chatUuid): RedirectResponse
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

    public function removeMember($chatUuid, $memberUuid): void
    {
        $member = $this->adminGetMember($chatUuid, $memberUuid);

        DB::transaction(function () use ($member) {
            $member->delete();
            $this->newChatAction($member->chat->id, 'removed ' . $member->user->name . ' from the chat');
        });
    }

    public function assignAdmin($chatUuid, $memberUuid): void
    {
        $member = $this->adminGetMember($chatUuid, $memberUuid);

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

    private function adminGetMember($chatUuid, $memberUuid)
    {
        $user = Auth::user();
        $chat = Chat::where('uuid', $chatUuid)
            ->whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('admin', true);
            })
            ->firstOrFail();

        return ChatMember::where('uuid', $memberUuid)
            ->where('chat_id', $chat->id)
            ->where('admin', false)
            ->firstOrFail();
    }
}
