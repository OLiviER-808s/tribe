<?php

namespace App\Models;

use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Conversation extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy, HasTags;

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

    public function topic()
    {
        return $this->belongsTo(Topic::class);
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
