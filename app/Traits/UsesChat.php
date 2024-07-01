<?php

namespace App\Traits;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait UsesChat
{
    public function chat(): MorphOne
    {
        return $this->morphOne(Chat::class, 'attachable');
    }
}
