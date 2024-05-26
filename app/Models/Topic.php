<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Topic extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'emoji',
        'label',
        'category_id',
        'parent_id',
        'level'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TopicCategory::class, 'category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }

    public function allParents(): Collection
    {
        $parents = collect();
        $parent = $this->parent;

        while ($parent) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents->sortDesc('level');
    }

    public function allChildren()
    {
        $children = collect();

        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->allChildren());
        }

        return $children->sortDesc('level');
    }

    public function viewModel($withCategory = false, $withParent = false): array
    {
        $model = [
            'uuid' => $this->uuid,
            'label' => $this->label,
            'emoji' => $this->emoji,
        ];

        if ($withCategory && $this->category) {
            $model['category'] = [
                'uuid' => $this->category->uuid,
                'name' => $this->category->name
            ];
        }

        if ($withParent && $this->parent) {
            $model['parent'] = [
                'uuid' => $this->parent->uuid,
                'label' => $this->parent->label
            ];
        }

        return $model;
    }
}
