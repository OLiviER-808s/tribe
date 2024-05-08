<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        } else if (! $user->username) {
            return Redirect::route('profile.create');
        } else if ($user->interests->count() < 1) {
            return Redirect::route('profile.interests');
        } else {
            return Redirect::route('discover');
        }
    }
}
