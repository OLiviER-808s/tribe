<?php

namespace App\Models;

use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy;

    protected $fillable = [
        'name',
        'conversation_id',
    ];

    public function members()
    {
        return $this->hasMany(ChatMember::class);
    }

    public function removedMembers()
    {
        return $this->hasMany(ChatMember::class)->onlyTrashed();
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

    public function viewModel($withUnreadMessages = true)
    {
        $authMember = $this->members->where('user_id', Auth::user()->id)->first();

        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'members' => $this->members->map(fn ($member) => $member->viewModel()),
            'unreadMessages' => $withUnreadMessages ? $authMember->unread_messages : null,
            'latestMessage' => $this->latestMessage ? [
                'content' => $this->latestMessage->content,
                'sent_at' => $this->latestMessage->created_at
            ] : null
        ];
    }
}
