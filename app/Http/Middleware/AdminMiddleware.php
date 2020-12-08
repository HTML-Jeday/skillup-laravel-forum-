<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        $role = Auth::user()->role ?? 'guest';

        if ($role !== 'admin') {
            return response()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
        }
        //subcategory
//        if (Auth::user()->role !== 'admin') {
//            return response()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
//        }
        //topic
        //user or moderator clicked delete, update topic and get topic id
//
//        if (Auth::user()->role == 'user' || Auth::user()->role == 'moderator') {
//            $topic = \App\Models\Topic::query()->where('id', $id)->first();
//
//            if ($topic->author !== Auth::user()->id) {
//                if ($user->role == 'moderator' || $user->role == 'admin') {
//                    return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
//                }
//            }
//        }
        //messages
        //delete
//        if (Auth::user()->role == 'user' || Auth::user()->role == 'moderator') {
//            $message = \App\Models\Message::query()->where('id', $id)->first();
//
//            if ($message->author !== Auth::user()->id) {
//                if ($user->role == 'moderator' || $user->role == 'admin') {
//                    return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
//                }
//            }
//        }
        //update
//        $message = \App\Models\Message::query()->where('id', $id)->first();
//
//        if ($message->author !== Auth::user()->id) {
//            return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
//        }
        //users
        //delete
//        if (Auth::user()->role == 'moderator' || Auth::user()->role == 'user') {
//            $user = \App\Models\User::query()->where('id', $id)->first();
//            if (!Auth::user()->id == $user->id) {
//                if ($user->role == 'moderator' || $user->role == 'admin') {
//                    return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
//                }
//            }
//        }
        //update
//        if (Auth::user()->role == 'moderator' || Auth::user()->role == 'user' || Auth::user()->role == 'admin') {
//            $user = \App\Models\User::query()->where('id', $id)->first();
//            if (!Auth::user()->id == $user->id) {
//                return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
//            }
//        }






        return $next($request);
    }

}
