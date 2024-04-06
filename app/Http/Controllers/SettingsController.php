<?php

namespace App\Http\Controllers;

use App\Models\TagCategory;
use Illuminate\Http\Request;
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
}
