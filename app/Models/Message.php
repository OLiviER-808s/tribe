<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Traits\UsesFiles;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, UsesUuid, InteractsWithMedia, UsesFiles;

    protected $fillable = [
        'uuid',
        'user_id',
        'chat_id',
        'content',
        'status',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::MESSAGE_ATTACHMENTS);
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'chat_uuid' => $this->chat->uuid,
            'content' => $this->content,
            'status' => $this->status,
            'type' => $this->type,
            'sent_by' => $this->user->viewModel(),
            'sent_at' => $this->created_at,
            'files' => $this->formatFiles($this->getMedia(ConstMedia::MESSAGE_ATTACHMENTS))
        ];
    }
}
