<?php

namespace App\Http\Controllers\Auth;

use App\Constants\ConstMedia;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email', 'https://www.googleapis.com/auth/user.birthday.read'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'email' => $googleUser->email,
        ], [
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'date_of_birth' => $this->getDateOfBirth($googleUser)
        ]);

        Auth::login($user);

        return Redirect::route('discover');
    }

    private function getDateOfBirth($user)
    {
        $client = new Client();
        $response = $client->get('https://people.googleapis.com/v1/people/me?personFields=birthdays', [
            'headers' => [
                'Authorization' => 'Bearer ' . $user->token,
            ],
        ]);

        $profile = json_decode($response->getBody()->getContents(), true);
        $birthdays = $profile['birthdays'] ?? null;
        $birthday = $birthdays[0]['date'] ?? null;

        if ($birthday) {
            $dateOfBirth = $birthday['year'] . '-' . $birthday['month'] . '-' . $birthday['day'];
        } else {
            $dateOfBirth = null;
        }

        return Carbon::parse($dateOfBirth);
    }
}
