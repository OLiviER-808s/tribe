<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RequiresUsername
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()->username) {
            return Redirect::route('profile.create');
        }

        return $next($request);
    }
}
