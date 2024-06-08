<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\UserSetting;
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
        $parentIds = Auth::user()->interests->whereNotNull('parent_id')->pluck('parent_id')->unique()->toArray();
        $childIds = Topic::whereIn('parent_id', $parentIds)->pluck('id')->toArray();

        $categories = TopicCategory::with('topics')->with('topics', function ($query) use ($childIds) {
            return $query->whereNull('parent_id')->orWhereIn('id', $childIds);
        })->get();

        return Inertia::render('Settings/Profile', [
            'categories' => $categories->map(fn ($category) => $category->viewModel()),
        ]);
    }

    public function setTheme($theme)
    {
        $user = Auth::user();

        UserSetting::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'user_id' => $user->id,
            'theme' => $theme,
        ]);
    }
}
