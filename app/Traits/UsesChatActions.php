<?php

namespace App\Traits;

use App\Constants\ConstMessageTypes;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

trait UsesChatActions
{
    private function newChatAction($chatId, $content): void
    {
        $action = Message::create([
            'user_id' => Auth::user()->id,
            'chat_id' => $chatId,
            'content' => $content,
            'type' => ConstMessageTypes::ACTION
        ]);

        broadcast(new MessageSent($action));
    }
}
