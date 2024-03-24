<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatUuid}', function ($user, $chatUuid) {
    return Chat::where('uuid', $chatUuid)->whereHas('members', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    });
});
