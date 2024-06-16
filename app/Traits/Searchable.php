<?php

namespace App\Traits;

use App\Models\User;

trait Searchable
{
    public function userSearches()
    {
        return $this->morphToMany(User::class, 'searchable');
    }

    public function getSearchCountAttribute()
    {
        return $this->userSearches->count();
    }

    public function addSearchRecord($user)
    {
        $this->userSearches()->syncWithoutDetaching([$user]);
    }
}
