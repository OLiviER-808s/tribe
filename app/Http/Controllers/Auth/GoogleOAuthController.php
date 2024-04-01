<?php

namespace App\Http\Controllers\Auth;

use App\Constants\ConstMedia;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'google_id' => $googleUser->id,
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ]);

        $media = $user->getFirstMedia(ConstMedia::PROFILE_PHOTO);

        if (! $media) {
            $user->addMedia($googleUser->picture)->toMediaCollection(ConstMedia::PROFILE_PHOTO);
        }
     
        Auth::login($user);
     
        return redirect('/dashboard');
    }
}
