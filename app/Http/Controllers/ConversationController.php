<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ConversationController extends Controller
{
    public function create()
    {
        return Inertia::render('Conversations/ConversationCreate');
    }
}
