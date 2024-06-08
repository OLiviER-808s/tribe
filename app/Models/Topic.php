<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Actionable;

class Topic extends Model
{
    use Actionable, HasFactory, UsesUuid;

    protected $fillable = [
        'emoji',
        'label',
        'category_id',
        'parent_id',
        'level',
        'active',
        'requested_by_id',
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

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_id');
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

    public function refreshLevel()
    {
        $this->level = $this->parent ? $this->parent->level + 1 : 1;
        $this->save();
    }

    public function viewModel($withCategory = false, $withParent = false): array
    {
        $model = [
            'uuid' => $this->uuid,
            'label' => $this->label,
        ];

        if ($withCategory && $this->category) {
            $model['category'] = [
                'uuid' => $this->category->uuid,
                'name' => $this->category->name,
            ];
        }

        if ($withParent && $this->parent) {
            $model['parent'] = [
                'uuid' => $this->parent->uuid,
                'label' => $this->parent->label,
            ];
        }

        return $model;
    }
}
