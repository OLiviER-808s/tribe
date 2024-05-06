<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'emoji',
        'label',
        'category_id',
        'parent_id'
    ];

    public function category()
    {
        return $this->belongsTo(TopicCategory::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'label' => $this->label,
            'emoji' => $this->emoji
        ];
    }
}
