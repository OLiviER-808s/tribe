<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function account()
    {
        return Inertia::render('Settings/Account');
    }
}
