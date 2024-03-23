<?php

namespace App\Models;

use App\Traits\UsesCreatedBy;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory, UsesUuid, UsesCreatedBy;
}
