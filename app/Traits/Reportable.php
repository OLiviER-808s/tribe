<?php

namespace App\Traits;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Reportable
{
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function resolveReports(): void
    {
        foreach ($this->reports as $report) {
            $report->resolved = true;
            $report->save();
        }
    }
}
