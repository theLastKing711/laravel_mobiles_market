<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth('sanctum')->user()) { // if token is passed, and user valid using token
            // get the authenticated user using token from header
            // and db and sign him in,
            // to access him in controller using Auth::user()
            Auth::setUser(
                Auth::guard('sanctum')->user()
            );
        }

        // sign in failed using token, or is not passed in the header
        // guest can view the page and Auth::User returns null
        return $next($request);
    }
}
