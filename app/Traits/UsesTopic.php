<?php

namespace App\Traits;

use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait UsesTopic
{
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function calculateRelevance($userInterests)
    {
        $relevanceScore = 0;

        // Direct Match
        if (in_array($this->topic->id, $userInterests)) {
            $relevanceScore += $this->topic->level + 4;
        }

        // Parent Match
        $parents = $this->topic->parents;
        $linkedParent = $parents?->whereIn('id', $userInterests)->first();

        if ($linkedParent) {
            $relevanceScore += $linkedParent->level + 2;
        }

        // Adjacent Match
        $lastParent = $parents?->last();
        $linkedChild = $lastParent?->allChildren()->whereIn('id', $userInterests)->first();

        if ($linkedChild) {
            $relevanceScore += $linkedChild->level;
        }

        // Score decreases every 2 days
        $today = Carbon::now();
        $relevanceScore -= $today->diffInDays($this->created_at, true) / 2;

        return $relevanceScore;
    }
}
