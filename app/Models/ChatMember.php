<?php

namespace App\Models;

use App\Constants\ConstMedia;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'user_id',
        'chat_id',
        'admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
