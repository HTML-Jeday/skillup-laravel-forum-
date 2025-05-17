<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware to restrict access to admin and moderator users only
 */
class AdminModerMiddleware
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
        
        // Check if user has admin or moderator role
        if ($role !== 'admin' && $role !== 'moderator') {
            return abort(403, 'Unauthorized. Admin or moderator access required.');
        }
        
        return $next($request);
    }
}
