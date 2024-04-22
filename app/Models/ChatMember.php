<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMember extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'chat_id',
        'admin',
        'last_read_message_id'
    ];

    protected $casts = [
        'admin' => 'boolean',
        'archived' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(Message::class, 'last_read_message_id');
    }

    public function getUnreadMessagesAttribute()
    {
        return Message::where('chat_id', $this->chat->id)
            ->whereNot('user_id', $this->user->id)
            ->where('created_at', '>', $this->lastReadMessage?->created_at ?? $this->chat->created_at)
            ->count();
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'user_uuid' => $this->user->uuid,
            'name' => $this->user->name,
            'admin' => $this->admin,
            'photo' => $this->user->getFirstMedia(ConstMedia::PROFILE_PHOTO)?->getFullUrl() ?? ConstMedia::DEFAULT_PROFILE_PHOTO_PATH,
        ];
    }
}
