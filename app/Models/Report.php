<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reportables';

    protected $fillable = [
        'reportable_type',
        'reportable_id',
        'user_id',
        'category_id',
    ];
}
