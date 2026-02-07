<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class CustomerRegisterController extends Controller
{
    /**
     * Show registration page.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['nullable', 'string', 'regex:/^[6-9]\d{9}$/', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'newsletter' => ['nullable', 'boolean'],
        ], [
            'mobile.regex' => 'Please enter a valid 10-digit Indian mobile number.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'] ?? null,
            'password' => $validated['password'],
            'newsletter_subscribed' => $request->boolean('newsletter'),
            'is_active' => true,
        ]);

        event(new Registered($user));

        // Send welcome email
        Mail::to($user)->send(new WelcomeMail($user));

        Auth::login($user);

        $user->recordLogin();

        return redirect()->route('home')
            ->with('success', 'Welcome! Your account has been created successfully.');
    }
}
