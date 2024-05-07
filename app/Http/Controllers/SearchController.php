<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::where('active', true)
            ->where('title', 'LIKE', '%' . $request->input('query') . '%')
            ->orWhere('description', 'LIKE', '%' . $request->input('query') . '%')
            ->get()
            ->map(fn ($conversation) => [...$conversation->viewModel(), 'resultType' => 'conversation'])
            ->toArray();

        $users = User::where('name', 'LIKE', $request->input('query') . '%')
            ->orWhere('username', 'LIKE', $request->input('query') . '%')
            ->get()
            ->map(fn ($user) => [...$user->viewModel(), 'resultType' => 'user'])
            ->toArray();

        $results = collect(array_merge($conversations, $users))->paginate(10);

        if ($request->wantsJson()) {
            return [
                'results' => $results
            ];
        }

        return Inertia::render('Search', [
            'searchQuery' => $request->input('query'),
            'results' => $results,
        ]);
    }
}
