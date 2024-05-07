<?php

namespace App\Jobs;

use App\Models\ChatMember;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReadMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $memberUuids;
    
    public function __construct($message, $memberUuids)
    {
        $this->message = $message;
        $this->memberUuids = $memberUuids;
    }

    public function handle(): void
    {
        ChatMember::whereIn('uuid', $this->memberUuids)->update([
            'last_read_message_id' => $this->message->id,
        ]);
    }
}
