<?php

namespace App\Http\Controllers;

use App\Models\ReportCategory;
use Illuminate\Http\Request;

class ReportCategoryController extends Controller
{
    public function index($type)
    {
        $categories = ReportCategory::where('type', $type)->get()->map(fn ($category) => $category->viewModel());

        return [
            'categories' => $categories,
        ];
    }
}
