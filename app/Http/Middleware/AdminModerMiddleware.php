<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminModerMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        $role = Auth::user()->role ?? 'guest';

        if ($role !== 'admin' && $role !== 'moderator') {
            return abort(404);
        }
        return $next($request);
    }

}
