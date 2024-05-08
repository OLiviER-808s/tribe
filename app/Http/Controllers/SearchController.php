<?php

namespace App\Http\Controllers;

use App\Constants\ConstStatus;
use App\Jobs\NewSearchTerm;
use App\Models\CommonSearch;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::where('active', true)
            ->where('title', 'LIKE', '%' . $request->input('query') . '%')
            ->orWhere('description', 'LIKE', '%' . $request->input('query') . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::where('status', ConstStatus::USER_ACTIVE)
            ->where('name', 'LIKE', $request->input('query') . '%')
            ->orWhere('username', 'LIKE', $request->input('query') . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        $terms = CommonSearch::where('search_term', 'LIKE', $request->input('query') . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        $results = $terms
            ->merge($users)
            ->merge($conversations)
            ->sortByDesc('search_count')
            ->map(fn ($model) => [ ...$model->viewModel(), 'resultType' => $model->searchResultType ])
            ->paginate(15);

        if ($request->wantsJson()) {
            return [
                'results' => $results
            ];
        }

        NewSearchTerm::dispatch($request->input('query'), Auth::user()->id);

        return Inertia::render('Search', [
            'searchQuery' => $request->input('query'),
            'results' => $results,
        ]);
    }
}
