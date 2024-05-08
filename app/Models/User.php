<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\ConstMedia;
use App\Traits\IsSearchable;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, UsesUuid, InteractsWithMedia, IsSearchable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public $searchResultType = 'user';

    public function interests()
    {
        return $this->belongsToMany(Topic::class, 'user_interests');
    }

    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'created_by_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'created_by_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(ConstMedia::PROFILE_PHOTO)->singleFile();
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'photo' => $this->getFirstMedia(ConstMedia::PROFILE_PHOTO)?->getFullUrl() ?? ConstMedia::DEFAULT_PROFILE_PHOTO_PATH,
            'interests' => $this->interests->map(fn ($topic) => $topic->viewModel())
        ];
    }
}
