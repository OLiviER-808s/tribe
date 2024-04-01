<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class TagCategory extends Model
{
    use HasFactory, UsesUuid, HasTags;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'tags' => $this->tags->map(fn ($tag) => $tag->name)
        ];
    }
}