<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\ConstMedia;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, UsesUuid, HasTags, InteractsWithMedia;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
            'photo' => $this->getFirstMedia(ConstMedia::PROFILE_PHOTO)?->getFullUrl() ?? ConstMedia::DEFAULT_PROFILE_PHOTO_PATH,
            'interests' => $this->tags->map(fn ($tag) => $tag->name)
        ];
    }
}
