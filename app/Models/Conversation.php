<?php

namespace App\Models;

use App\Traits\Reportable;
use App\Traits\Searchable;
use App\Traits\UsesChat;
use App\Traits\UsesCreatedBy;
use App\Traits\UsesTopic;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class Conversation extends Model
{
    use Actionable, HasFactory, Reportable, Searchable, UsesChat, UsesCreatedBy, UsesTopic, UsesUuid;

    protected $fillable = [
        'title',
        'description',
        'topic_id',
        'limit',
        'active',
    ];

    public $casts = [
        'active' => 'boolean',
    ];

    public $searchResultType = 'conversation';

    public function viewModel(): array
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
            'topic' => $this->topic->viewModel(),
        ];
    }
}
