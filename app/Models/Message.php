<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Traits\UsesFiles;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, UsesFiles, UsesUuid;

    protected $fillable = [
        'uuid',
        'user_id',
        'chat_id',
        'content',
        'reply_to_id',
        'status',
        'type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function replyToMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::MESSAGE_ATTACHMENTS);
    }

    public function viewModel(): array
    {
        $model = [
            'uuid' => $this->uuid,
            'chat_uuid' => $this->chat->uuid,
            'content' => $this->content,
            'status' => $this->status,
            'type' => $this->type,
            'sent_by' => $this->user->viewModel(),
            'sent_at' => $this->created_at,
            'files' => $this->formatFiles($this->getMedia(ConstMedia::MESSAGE_ATTACHMENTS)),
        ];

        if ($replyToMessage = $this->replyToMessage) {
            $model = array_merge($model, [
                'reply_to' => [
                    'content' => $replyToMessage->content,
                    'sent_by' => $replyToMessage->user->name,
                ],
            ]);
        }

        return $model;
    }
}
