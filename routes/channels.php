<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('chat.{chatUuid}', function ($user, $chatUuid) {
    $chat = Chat::where('uuid', $chatUuid)->with('members')->first();
    $member = $chat?->members->where('user_id', $user->id)->first();

    if ($member) {
        return [ 'uuid' => $member->uuid, 'name' => $user->name, 'typing' => false ];
    }

    return false;
});

Broadcast::channel('user.{userUuid}', function ($user, $userUuid) {
    return $user->uuid === $userUuid;
});
