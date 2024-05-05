<?php

namespace App\Http\Controllers;

use App\Constants\ConstMedia;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\StoreProfileInterests;
use App\Models\TagCategory;
use App\Models\Topic;
use App\Models\TopicCategory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/SetProfileInfo');
    }

    public function interests()
    {
        $categories = TopicCategory::all();

        return Inertia::render('Auth/SetInterests', [
            'categories' => $categories->map(fn ($category) => $category->viewModel())
        ]);
    }

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'username' => $request['username'],
            'bio' => $request['bio']
        ]);
        $user->save();

        if ($request['photo']) {
            $user->addMedia($request['photo'])->toMediaCollection(ConstMedia::PROFILE_PHOTO);
        }

        return Redirect::route($request['next_route']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateInterests(StoreProfileInterests $request)
    {
        $topics = Topic::whereIn('uuid', $request['interests'])->get();
        $request->user()->interests()->sync($topics);

        return Redirect::route($request['next_route']);
    }
}
