<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        $user = auth()->user();

        // Check if user has admin access through is_admin flag or role-based permissions
        if (!$user->canAccessAdmin() || $user->status != 1) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
