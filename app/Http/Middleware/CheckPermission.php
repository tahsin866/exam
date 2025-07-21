<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is admin
        if (!$user->isAdmin()) {
            abort(403, 'Access denied. Admin access required.');
        }

        // Check if user has the required permission
        if (!$user->hasDirectPermission($permission)) {
            abort(403, 'Access denied. You do not have the required permission: ' . $permission);
        }

        return $next($request);
    }
}
