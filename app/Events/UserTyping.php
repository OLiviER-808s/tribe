<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\ChatMember;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ChatMember $member;
    private bool $typing;

    public function __construct($member, $typing)
    {
        $this->dontBroadcastToCurrentUser();
        $this->member = $member;
        $this->typing = $typing;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('chat.' . $this->member->chat->uuid),
        ];
    }

    public function broadcastAs()
    {
        return 'user-typing';
    }

    public function broadcastWith()
    {
        return [
            'member_uuid' => $this->member->uuid,
            'typing' => $this->typing
        ];
    }
}
