<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function create()
    {
        return Inertia::render('Conversations/ConversationCreate');
    }

    public function store(StoreConversation $request)
    {
        
    }
}
