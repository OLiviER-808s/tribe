<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicCategory extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function topics()
    {
        return $this->hasMany(Topic::class, 'category_id');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'topics' => $this->topics->whereNull('parent_id')->map(fn ($topic) => $topic->viewModel())
        ];
    }
}
