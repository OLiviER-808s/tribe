<?php

namespace App\Http\Controllers;

use App\Models\TagCategory;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function account()
    {
        $categories = TagCategory::with('tags')->get();

        return Inertia::render('Settings/Account', [
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
