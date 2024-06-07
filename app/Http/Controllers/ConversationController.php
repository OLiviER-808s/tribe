<?php

namespace App\Http\Controllers;

use App\Constants\ConstChatActions;
use App\Constants\ConstMessageTypes;
use App\Http\Requests\StoreConversation;
use App\Jobs\AddSearchRecord;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Topic;
use App\Traits\UsesChatActions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ConversationController extends Controller
{
    use UsesChatActions;

    public function show($uuid)
    {
        $conversation = Conversation::where('uuid', $uuid)->firstOrFail();

        AddSearchRecord::dispatch($conversation, Auth::user()->id);

        return Inertia::render('Conversations/Conversation', [
            'conversation' => $conversation->viewModel(),
            'canJoin' => !$conversation->chat->members->where('user_id', Auth::user()->id)->first()
        ]);
    }

    public function create()
    {
        return Inertia::render('Conversations/ConversationCreate');
    }

    public function store(StoreConversation $request)
    {
        $user = Auth::user();
        $topic = Topic::where('uuid', $request['topic'])->firstOrFail();

        $chat = DB::transaction(function () use ($request, $user, $topic) {
            $conversation = Conversation::create([
                'title' => $request['title'],
                'description' => $request['description'],
                'topic_id' => $topic->id,
                'limit' => $request['limit'],
                'active' => true
            ]);

            $chat = Chat::create([
                'name' => $request['title'],
                'conversation_id' => $conversation->id
            ]);

            ChatMember::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'admin' => true,
            ]);

            $this->newChatAction($chat->id, ConstChatActions::CHAT_CREATED);

            return $chat;
        });

        return to_route('chat.show', [
            'uuid' => $chat->uuid
        ]);
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
                'last_read_message_id' => $chat->latestMessage?->id ?? null,
                'admin' => false
            ]);

            $this->newChatAction($chat->id, ConstChatActions::USER_JOINED);
        });

        return to_route('chat.show', [
            'uuid' => $chat->uuid
        ]);
    }
}
