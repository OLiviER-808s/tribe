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
}
