<?php

namespace App\Models;

use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy;

    protected $fillable = [
        'title',
        'description',
        'limit',
        'active',
    ];

    public function chat()
    {
        return $this->hasOne(Chat::class, 'conversation_id');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'created_by' => $this->createdByUser->name,
            'members' => $this->chat->members->count(),
            'limit' => $this->limit
        ];
    }
}
