<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Rate limiting
        $throttleKey = 'admin-login:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        $admin = Admin::where('email', $request->email)->first();

        // Check if admin exists
        if (!$admin) {
            RateLimiter::hit($throttleKey, 900); // 15 minutes
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Check if account is locked
        if ($admin->isLocked()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is temporarily locked. Please try again later.',
            ]);
        }

        // Check if account is active
        if (!$admin->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated.',
            ]);
        }

        // Attempt login
        if (!Hash::check($request->password, $admin->password)) {
            RateLimiter::hit($throttleKey, 900);
            $admin->recordFailedLogin();

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        // Clear rate limiter and reset failed attempts
        RateLimiter::clear($throttleKey);
        $admin->resetFailedLoginAttempts();

        // Login the admin
        Auth::guard('admin')->login($admin, $request->boolean('remember'));
        $request->session()->regenerate();
        session(['admin_last_activity' => time()]);

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Welcome back, ' . $admin->name . '!');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('admin_last_activity');

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        // TODO: Send password reset email

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    /**
     * Show admin profile page
     */
    public function profile()
    {
        return view('admin.profile', [
            'admin' => Auth::guard('admin')->user()
        ]);
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($admin->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($admin->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $admin->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update admin password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
