<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogSecurityEvents
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log security events for certain conditions
        $this->logAccessAttempt($request, $response);

        return $response;
    }

    private function logAccessAttempt(Request $request, Response $response)
    {
        $user = Auth::user();
        
        // Log failed authentication attempts (403, 404 responses)
        if (in_array($response->getStatusCode(), [403, 404])) {
            Log::warning('Access denied', [
                'user_id' => $user?->id,
                'user_type' => $user?->user_type,
                'email' => $user?->email,
                'route' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status_code' => $response->getStatusCode(),
                'timestamp' => now()->toISOString(),
                'session_id' => session()->getId(),
            ]);
        }

        // Log successful admin panel access
        if ($user && $user->isAdmin() && str_starts_with($request->path(), 'admin')) {
            Log::info('Admin panel access', [
                'user_id' => $user->id,
                'email' => $user->email,
                'route' => $request->route()?->getName(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'timestamp' => now()->toISOString(),
            ]);
        }
    }
}
