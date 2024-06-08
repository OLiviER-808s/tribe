<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\ConstMedia;
use App\Traits\IsSearchable;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use Actionable, HasFactory, InteractsWithMedia, IsSearchable, Notifiable, UsesUuid;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
        'date_of_birth',
        'location',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'datetime',
    ];

    public $searchResultType = 'user';

    public function interests()
    {
        return $this->belongsToMany(Topic::class, 'user_interests');
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'created_by_id');
    }

    public function chatMemberships()
    {
        return $this->hasMany(ChatMember::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'created_by_id');
    }

    public function getUnreadChatsAttribute()
    {
        return $this->chatMemberships->where('unread_messages', '>', 0)
            ->map(fn ($member) => [
                'uuid' => $member->chat->uuid,
                'unreadMessages' => $member->unread_messages,
            ])
            ->values()
            ->all();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::PROFILE_PHOTO)->singleFile();
    }

    public function viewModel($withLocation = false, $withDateOfBirth = false)
    {
        $model = [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'photo' => $this->getFirstMedia(ConstMedia::PROFILE_PHOTO)?->getFullUrl() ?? ConstMedia::DEFAULT_PROFILE_PHOTO_PATH,
            'interests' => $this->interests->map(fn ($topic) => $topic->viewModel()),
        ];

        if ($withLocation) {
            $model['location'] = $this->location;
        }

        if ($withDateOfBirth) {
            $model['date_of_birth'] = $this->date_of_birth;
        }

        return $model;
    }
}
