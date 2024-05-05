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
        $userInterests = $user->interests->pluck('id')->toArray();

        $conversations = Conversation::where('active', true)
            ->whereIn('topic_id', $userInterests)
            ->whereDoesntHave('chat.members', function ($query) use ($user) {
                return $query->withTrashed()->where('user_id', $user->id);
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
