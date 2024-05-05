<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RequiresInterests
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->interests->count() < 1) {
            return Redirect::route('profile.interests');
        }

        return $next($request);
    }
}
