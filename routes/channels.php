<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatUuid}', function ($user, $chatUuid) {
    $chat = Chat::where('uuid', $chatUuid)->with('members')->first();
    $member = $chat?->members->where('user_id', $user->id)->first();

    if ($member) {
        return [ 'uuid' => $member->uuid, 'name' => $user->name, 'typing' => false ];
    }

    return false;
});
