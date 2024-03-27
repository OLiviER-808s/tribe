<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'user_id',
        'chat_id',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'user_uuid' => $this->user->uuid,
            'content' => $this->content,
            'sent_by' => $this->user->viewModel(),
        ];
    }
}
