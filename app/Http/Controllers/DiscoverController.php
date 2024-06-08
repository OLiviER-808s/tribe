<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DiscoverController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userInterests = $user->interests->pluck('id')->toArray();

        $conversations = Conversation::where('active', true)
            ->whereDoesntHave('chat.members', function ($query) use ($user) {
                return $query->withTrashed()->where('user_id', $user->id);
            })
            ->with('chat.members')
            ->orderBy('created_at', 'desc');

        if ($request->input('topics')) {
            $topics = Topic::whereIn('label', explode(',', $request->input('topics')))->get()->pluck('id')->toArray();
            $conversations = $conversations->whereIn('topic_id', $topics);
        }

        $conversations = $conversations->get()
            ->sortByDesc(fn ($conversation) => $conversation->calculateRelevance($userInterests))
            ->paginate(15);

        if ($request->wantsJson()) {
            return ConversationResource::collection($conversations);
        }

        return Inertia::render('Discover', [
            'conversations' => ConversationResource::collection($conversations),
        ]);
    }
}
