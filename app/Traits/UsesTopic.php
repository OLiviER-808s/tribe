<?php

namespace App\Traits;

use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

trait UsesTopic
{
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function calculateRelavance($userInterests)
    {
        $relavenceScore = 0;

        $parentTopic = $this->topic;
        while ($parentTopic->parent_id !== null) {
            if (in_array($parentTopic->id, $userInterests)) {
                $relavenceScore += 2; // Assign higher score for direct match
            } elseif (in_array($parentTopic->parent_id, $userInterests)) {
                $relavenceScore += 1; // Assign lower score for indirect match
            }

            $parentTopic = $parentTopic->parent;
        }

        if (! $this->topic->parent_id && in_array($parentTopic->id, $userInterests)) {
            $relavenceScore += 2;
        }

        return $relavenceScore;
    }
}