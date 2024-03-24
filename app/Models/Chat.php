<?php

namespace App\Models;

use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy;

    protected $fillable = [
        'name',
        'conversation_id',
    ];

    public function members()
    {
        return $this->hasMany(ChatMember::class, 'chat_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id')->orderBy('created_at', 'desc');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'members' => $this->members->map(fn ($member) => $member->viewModel())
        ];
    }
}
