<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if admin is authenticated
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('admin.login');
        }

        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        // Check if admin account is active
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        // Check if admin account is locked
        if ($admin->isLocked()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account is temporarily locked due to multiple failed login attempts.');
        }

        // Check session timeout (15 minutes of inactivity for admin)
        $lastActivity = session('admin_last_activity');
        $timeout = 15 * 60; // 15 minutes

        if ($lastActivity && (time() - $lastActivity) > $timeout) {
            Auth::guard('admin')->logout();
            session()->forget('admin_last_activity');
            return redirect()->route('admin.login')
                ->with('error', 'Your session has expired due to inactivity.');
        }

        // Update last activity
        session(['admin_last_activity' => time()]);

        return $next($request);
    }
}
