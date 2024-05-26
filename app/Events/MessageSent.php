<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $userChannels = $this->message->chat->members->map(function ($member) {
            return new PrivateChannel('user.' . $member->user->uuid);
        })->toArray();

        return [
            ...$userChannels,
            new PresenceChannel('chat.' . $this->message->chat->uuid)
        ];
    }

    public function broadcastAs()
    {
        return 'message-sent';
    }

    public function broadcastWith()
    {
        return $this->message->viewModel();
    }
}
