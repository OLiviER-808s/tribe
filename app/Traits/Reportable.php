<?php

namespace App\Traits;

use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
