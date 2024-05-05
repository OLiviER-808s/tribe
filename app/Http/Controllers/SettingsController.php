<?php

namespace App\Http\Controllers;

use App\Models\TagCategory;
use App\Models\TopicCategory;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/SettingsIndex');
    }

    public function account()
    {
        return Inertia::render('Settings/Account');
    }

    public function profile()
    {
        $categories = TopicCategory::all();

        return Inertia::render('Settings/Profile', [
            'categories' => $categories->map(fn ($category) => $category->viewModel())
        ]);
    }

    public function setTheme($theme)
    {
        $user = Auth::user();

        UserSettings::updateOrCreate([
            'user_id' => $user->id
        ], [
            'user_id' => $user->id,
            'theme' => $theme
        ]);
    }
}
