<?php

namespace App\Events;

use App\Models\ChatAction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatActionExecuted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private ChatAction $action;

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . $this->action->chat->uuid);
    }

    public function broadcastAs()
    {
        return 'action-executed';
    }

    public function broadcastWith()
    {
        return $this->action->viewModel();
    }
}
