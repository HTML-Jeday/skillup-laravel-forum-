<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use \App\Http\Requests\RegisterUserRequest;
use \App\Http\Requests\LoginUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Verification;
use \Illuminate\Http\Response;
use \Illuminate\Support\Facades\Mail;
use \App\Mail\VerificationMail;
use \Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

/**
 * Description of AuthController
 *
 * @author linux
 */
class AuthController extends Controller {

    public function login(LoginUserRequest $request) {

        $validated = $request->validated();

        $user = User::query()->where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()->with(['error' => 'Creadentials are incorrect or account does not exist']);
        }

        $auth = Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']]);

        return back()->with(['success' => 'you have been loggedin']);
    }

    public function register(RegisterUserRequest $request) {

        $validated = $request->validated();

        $user = new User();

        $user->email = $validated['email'];
        $user->name = $validated['name'];
        $user->password = Hash::make($validated['password']);

        if (User::query()->where('email', $user->email)->first()) {
            return back()->with(['error' => 'Creadentials are incorrect or user already exist']);
        }
        $user->save();

        $verification = new Verification();
        $verification->user_id = $user->id;
        $verification->hash = md5($user->id);
        $verification->save();


        $auth = Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']]);

        $mailable = new VerificationMail($user, $verification->hash);
        Mail::to($user->email)->send($mailable);


        return back()->with(['success' => 'user has been created']);
    }

    public function logout() {

        Auth::logout();

        return redirect('/');
    }

}
