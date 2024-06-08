<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait UsesCreatedBy
{
    protected static function bootUsesCreatedBy()
    {
        static::creating(function ($model) {
            if (! $model->created_by_id) {
                $model->created_by_id = Auth::user()->id;
            }
        });
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
