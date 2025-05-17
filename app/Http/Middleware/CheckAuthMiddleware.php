<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Response;

/**
 * Middleware to verify user authentication and existence
 */
class CheckAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is not authenticated, redirect to home page
        if (!Auth::check()) {
            return redirect('/')->with('error', 'You must be logged in to access this page');
        }

        // Verify that the authenticated user still exists in the database
        $user = Auth::user();
        $userExists = User::where('email', $user->email)->exists();

        if (!$userExists) {
            // Log the user out and show error message
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return response()->view('welcome', [
                'error' => 'Credentials are incorrect or account does not exist'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
