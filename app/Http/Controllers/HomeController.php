<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with('chat.members')->get()->map(function ($conversation) {
            return $conversation->viewModel();
        })->toArray();

        return Inertia::render('Home', [
            'conversations' => $conversations
        ]);
    }
}
