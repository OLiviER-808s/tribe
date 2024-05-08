<?php

namespace App\Models;

use App\Traits\IsSearchable;
use App\Traits\UsesCreatedBy;
use App\Traits\UsesTopic;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy, UsesTopic, IsSearchable;

    protected $fillable = [
        'title',
        'description',
        'topic_id',
        'limit',
        'active',
    ];

    public $casts = [
        'active' => 'boolean'
    ];

    public $searchResultType = 'conversation';

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function userSearches()
    {
        return $this->morphToMany(User::class, 'searchable');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'created_by' => $this->createdByUser->username,
            'created_at' => $this->created_at,
            'active' => $this->active,
            'members' => $this->active ? $this->chat->members->count() : $this->limit,
            'limit' => $this->limit,
            'topic' => $this->topic->viewModel()
        ];
    }
}
