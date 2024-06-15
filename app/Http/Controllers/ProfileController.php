<?php

namespace App\Http\Controllers;

use App\Constants\ConstFlashMessages;
use App\Constants\ConstMedia;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\StoreProfileInterests;
use App\Jobs\AddSearchRecord;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        AddSearchRecord::dispatch($user, Auth::user()->id);

        return Inertia::render('Profile/Profile', [
            'profile' => $user->viewModel(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Auth/SetProfileInfo');
    }

    public function interests()
    {
        $categories = TopicCategory::with('topics')->with('topics', function ($query) {
            return $query->whereNull('parent_id');
        })->get();

        return Inertia::render('Auth/SetInterests', [
            'categories' => $categories->map(fn ($category) => $category->viewModel()),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'name' => $request['name'],
            'username' => $request['username'],
            'bio' => $request['bio'],
            'location' => $request['location'],
            'date_of_birth' => $request['dob']
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

        DB::transaction(function () use ($user) {
            $placeholderUserId = User::where('username', 'tribe_user')->firstOrFail()->id;

            $user->conversations()->update([
                'created_by_id' => $placeholderUserId,
            ]);
            $user->chats()->update([
                'created_by_id' => $placeholderUserId,
            ]);
            $user->messages()->update([
                'user_id' => $placeholderUserId,
            ]);

            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/register');
    }

    public function updateInterests(StoreProfileInterests $request)
    {
        $topics = Topic::whereIn('uuid', $request['interests'])->get();
        $request->user()->interests()->sync($topics);

        return Redirect::route($request['next_route']);
    }
}
