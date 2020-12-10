<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use \Illuminate\Http\Response;

class CheckAuthMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::check()) {

            $user = Auth::user();

            $findUser = User::query()->where('email', $user->email)->first();

            if (!$findUser) {
                Auth::logout();
                return response()->view('welcome', ['error' => 'Creadentials are incorrect or account does not exist'], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }

}
