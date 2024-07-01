<?php

namespace App\Http\Controllers;

use App\Constants\ConstMedia;
use App\Constants\ConstStatus;
use App\Constants\ConstTypes;
use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Http\Requests\StoreMessage;
use App\Http\Requests\TypingRequest;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function store($chatUuid, StoreMessage $request)
    {
        $user = Auth::user();
        $chat = Chat::where('uuid', $chatUuid)->whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->firstOrFail();

        $replyToMessage = Message::where('chat_id', $chat->id)->where('uuid', $request['reply_to_uuid'])->first();

        $message = DB::transaction(function () use ($request, $user, $chat, $replyToMessage) {
            $message = Message::create([
                'uuid' => $request['uuid'],
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'content' => $request['content'],
                'reply_to_id' => $replyToMessage?->id ?? null,
                'type' => ConstTypes::MESSAGE,
                'status' => ConstStatus::MESSAGE_SENT,
            ]);

            if ($request['files']) {
                foreach ($request['files'] as $file) {
                    $message->addMedia($file)->toMediaCollection(ConstMedia::MESSAGE_ATTACHMENTS);
                }
            }

            ChatMember::whereIn('uuid', $request['active_uuids'])->update([
                'last_read_message_id' => $message->id,
            ]);

            return $message;
        });

        broadcast(new MessageSent($message));
    }

    public function downloadAttachment($messageUuid, $fileUuid)
    {
        $message = Message::where('uuid', $messageUuid)
            ->whereHas('chat.members', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->firstOrFail();

        return $message->getMedia('*')->where('uuid', $fileUuid)->firstOrFail();
    }

    public function toggleTyping($chatUuid, TypingRequest $request): void
    {
        $chat = Chat::where('uuid', $chatUuid)->with('members')->firstOrFail();
        $member = $chat?->members->where('user_id', Auth::user()->id)->firstOrFail();

        broadcast(new UserTyping($member, $request['typing']))->toOthers();
    }
}
