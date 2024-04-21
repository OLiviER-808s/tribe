<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::where('active', true)
            ->whereDoesntHave('chat.members', function ($query) use ($user) {
                return $query->withTrashed()->where('user_id', $user->id);
            })
            ->whereHas('tags', function ($query) use ($user) {
                return $query->whereIn('id', $user->tags->map(fn ($tag) => $tag->id)->toArray());
            })
            ->with('chat.members')
            ->get()
            ->map(fn ($conversation) => $conversation->viewModel())
            ->toArray();

        return Inertia::render('Home', [
            'conversations' => $conversations
        ]);
    }
}
