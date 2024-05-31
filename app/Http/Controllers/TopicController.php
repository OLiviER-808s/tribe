<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopic;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public function index(Request $request): array
    {
        $topics = Topic::where('active', true)
            ->where('label', 'LIKE', '%' . $request->input('search') . '%')
            ->take(10)
            ->get();

        return [
            'topics' => $topics->map(fn ($topic) => $topic->viewModel(true, true))
        ];
    }

    public function store(StoreTopic $request): array
    {
        $topic = Topic::create([
            'label' => $request['label'],
            'active' => false,
            'requested_by_id' => Auth::user()->id
        ]);

        return [
            'topic' => $topic->viewModel()
        ];
    }

    public function getChildren($uuid): array
    {
        $topic = Topic::where('uuid', $uuid)->with('children')->firstOrFail();

        return [
            'topics' => $topic->children?->map(fn ($topic) => $topic->viewModel())
        ];
    }
}
