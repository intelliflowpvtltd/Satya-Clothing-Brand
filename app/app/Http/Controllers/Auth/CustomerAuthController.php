<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    /**
     * Show login page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Rate limiting
        $key = 'login-attempts:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->withInput($request->only('email'));
        }

        // Check if input is email or mobile
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $credentials = [
            $field => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if user is active
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact support.',
                ])->withInput($request->only('email'));
            }

            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Record login
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->recordLogin();

            // Redirect to intended page or home
            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back, ' . Auth::user()->display_name . '!');
        }

        RateLimiter::hit($key, 300); // 5 minutes

        return back()->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
