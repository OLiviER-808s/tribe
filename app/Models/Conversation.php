<?php

namespace App\Models;

use App\Traits\UsesCreatedBy;
use App\Traits\UsesTopic;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy, UsesTopic;

    protected $fillable = [
        'title',
        'description',
        'topic_id',
        'limit',
        'active',
    ];

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'created_by' => $this->createdByUser->username,
            'members' => $this->chat->members->count(),
            'limit' => $this->limit,
            'topic' => $this->topic->viewModel()
        ];
    }
}
