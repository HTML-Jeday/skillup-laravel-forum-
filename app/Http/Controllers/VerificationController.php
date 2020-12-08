<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verification;
use \Illuminate\Http\Response;

class VerificationController extends Controller {

    public function index(Request $request) {
        $hash = $request->input('hash');
        $verification = Verification::query()->where('hash', $hash)->first();

        if (!$verification) {
            return response()->json(['error' => 'user has been verificated or not registered'], Response::HTTP_BAD_REQUEST);
        }
        $user = User::query()->where('id', $verification->user_id)->first();

        $user->verified = 1;
        $user->save();
        $verification->delete();

        Auth::login($user);

        return redirect('/');
    }

}
