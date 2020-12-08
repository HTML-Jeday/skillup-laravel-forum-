<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthorizeMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (!Auth::check()) {
            return reponse()->json(['error' => 'please log in'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

}
