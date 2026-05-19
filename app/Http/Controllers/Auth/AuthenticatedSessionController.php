<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // 1. Inline validation replaces the missing form request file
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 2. Attempt authentication using credentials and check for "remember me"
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 3. Secure the session
        $request->session()->regenerate();

        // 4. Fetch the logged-in user to evaluate role routing maps
        $user = Auth::user();

        // ─── FIXED REDIRECT LOGIC ──────────────────────────────────────────
        // Check if the user is an admin by checking their database role column
        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        // Fallback for regular users (Using a raw string path so it never crashes)
        return redirect()->intended('/');
        // ───────────────────────────────────────────────────────────────────
    }

    public function destroy(Request $request)
    {
        // Log out the user from the web guard
        Auth::guard('web')->logout();

        // Flush and secure session values
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
