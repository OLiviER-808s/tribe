<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    protected function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (! $model->uuid) {
                $model->uuid = Str::uuid();
            }
        });
    }
}