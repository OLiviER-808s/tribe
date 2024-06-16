<?php

namespace App\Traits;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Reportable
{
    public function reports(): MorphToMany
    {
        return $this->morphToMany(User::class, 'reportable');
    }

    public function getReportCountCategory()
    {
        return $this->reports->count();
    }
}
