<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DiscoverController extends Controller
{
    public function index(Request $request)
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
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(15);

        if ($request->wantsJson()) {
            return ConversationResource::collection($conversations);
        }

        return Inertia::render('Discover', [
            'conversations' => ConversationResource::collection($conversations)
        ]);
    }
}
