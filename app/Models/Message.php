<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, UsesUuid, InteractsWithMedia;

    protected $fillable = [
        'uuid',
        'user_id',
        'chat_id',
        'content',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::MESSAGE_PHOTOS);
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'content' => $this->content,
            'status' => $this->status,
            'sent_by' => $this->user->viewModel(),
            'sent_at' => $this->created_at
        ];
    }
}
