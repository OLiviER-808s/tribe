<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Http\Requests\StoreMessage;
use App\Http\Requests\TypingRequest;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function store($chatUuid, StoreMessage $request)
    {
        $user = Auth::user();

        $chat = Chat::where('uuid', $chatUuid)->whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->firstOrFail();

        $message = Message::create([
            'uuid' => $request['uuid'],
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'content' => $request['content']
        ]);

        broadcast(new MessageSent($message));

        ChatMember::whereIn('uuid', $request['active_uuids'])->update([
            'last_read_message_id' => $message->id
        ]);
    }

    public function toggleTyping($chatUuid, TypingRequest $request)
    {
        $chat = Chat::where('uuid', $chatUuid)->with('members')->firstOrFail();
        $member = $chat?->members->where('user_id', Auth::user()->id)->firstOrFail();

        broadcast(new UserTyping($member, $request['typing']))->toOthers();
    }
}
