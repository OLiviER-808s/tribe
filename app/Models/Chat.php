<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Chat extends Model implements HasMedia
{
    use HasFactory, UsesUuid, UsesCreatedBy, InteractsWithMedia;

    protected $fillable = [
        'name',
        'conversation_id',
    ];

    public function members()
    {
        return $this->hasMany(ChatMember::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function actions()
    {
        return $this->hasMany(ChatAction::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->orderBy('created_at', 'desc');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::CHAT_PHOTO)->singleFile();
    }

    public function viewModel($withUnreadMessages = true)
    {
        $authMember = $this->members->where('user_id', Auth::user()->id)->first();

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'members' => $this->members->map(fn ($member) => $member->viewModel()),
            'unreadMessages' => $withUnreadMessages ? $authMember->unread_messages : null,
            'archived' => $authMember->archived ?? false,
            'latestMessage' => $this->latestMessage ? [
                'content' => $this->latestMessage->content,
                'sent_at' => $this->latestMessage->created_at
            ] : null
        ];
    }
}
