<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $topics = Topic::where('label', 'LIKE', '%' . $request->input('search') . '%')
            ->get()
            ->map(fn ($topic) => $topic->viewModel(true, true));

        return [
            'topics' => $topics
        ];
    }

    public function getChildren($uuid)
    {
        $topic = Topic::where('uuid', $uuid)->with('children')->firstOrFail();

        return [
            'topics' => $topic->children?->map(fn ($topic) => $topic->viewModel())
        ];
    }
}
