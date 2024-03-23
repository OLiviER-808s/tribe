<?php

namespace App\Models;

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
}
