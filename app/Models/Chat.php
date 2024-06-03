<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Constants\ConstMessageTypes;
use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function members(): HasMany
    {
        return $this->hasMany(ChatMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)
            ->where('type', ConstMessageTypes::MESSAGE)
            ->orderBy('created_at', 'desc');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::CHAT_PHOTO)->singleFile();
    }

    public function viewModel(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'photo' => $this->getFirstMedia(ConstMedia::CHAT_PHOTO)?->getFullUrl() ?? null,
            'members' => $this->members->map(fn ($member) => $member->viewModel())->toArray(),
        ];
    }

    public function listViewModel(): array
    {
        $authMember = $this->members->where('user_id', Auth::user()->id)->first();

        return [
            ...$this->viewModel(),
            'unreadMessages' => $authMember->unread_messages,
            'archived' => $authMember->archived ?? false,
            'latestMessage' => $this->latestMessage ? [
                'content' => $this->latestMessage->content,
                'sent_at' => $this->latestMessage->created_at
            ] : null
        ];
    }
}
