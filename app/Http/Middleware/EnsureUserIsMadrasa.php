<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureUserIsMadrasa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is madrasa type
        if (!$user->isMadrasaUser()) {
            // Log unauthorized access attempt
            Log::warning('Admin user attempted to access madrasa route', [
                'user_id' => $user->id,
                'user_type' => $user->user_type,
                'route' => $request->route()->getName(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // If it's an AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. This area is restricted to madrasa users only.',
                    'redirect' => route('admin.dashboard')
                ], 403);
            }

            // For regular requests, return 404 to hide the existence of the route
            abort(404);
        }

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
