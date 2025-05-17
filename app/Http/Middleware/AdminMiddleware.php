<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware to restrict access to admin users only
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated first
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page');
        }
        
        $role = Auth::user()->role ?? 'guest';
        
        // Check if user has admin role
        if ($role !== 'admin') {
            return abort(403, 'Unauthorized. Admin access required.');
        }
        
        return $next($request);
    }
}
