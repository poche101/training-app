<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If user is logged in AND is an admin, let them proceed
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // 2. If they are a logged-in non-admin, redirect to member space
        if (Auth::check()) {
            return redirect()->route('member.dashboard')->with('error', 'You do not have administrative access.');
        }

        // 3. If they aren't logged in at all, kick them to login
        return redirect()->route('login');
    }
}
