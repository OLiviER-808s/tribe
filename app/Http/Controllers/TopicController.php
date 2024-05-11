<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

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
}
