<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Searchable
{
    public function userSearches(): MorphToMany
    {
        return $this->morphToMany(User::class, 'searchable');
    }

    public function getSearchCountAttribute()
    {
        return $this->userSearches->count();
    }

    public function addSearchRecord($user): void
    {
        $this->userSearches()->syncWithoutDetaching([$user]);
    }
}
