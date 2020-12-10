<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Verification;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Mail;
use \App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;

/**
 * Description of UserController
 *
 * @author linux
 */
class UserController extends Controller {

    public function index() {

        $user = Auth::user();
        $users = User::query()->get();

        return view('user', ['user' => $user, 'users' => $users]);
    }

    public function delete(DeleteUserRequest $request) {
        $validated = $request->validated();

        $user = User::query()->where('id', $validated['id'])->first();

        if (!$user) {
            return back()->with(['error' => 'user do not exist'], Response::HTTP_NOT_FOUND);
        }

        if (Auth::user()->role == 'moderator' || Auth::user()->role == 'user') {
            if (Auth::user()->id !== $user->id) {
                if ($user->role == 'moderator' || $user->role == 'admin') {
                    return back()->with(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
                }
            }
        }
        $user->delete();

        return back()->with(['success' => 'user has been deleted'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateUserRequest $request) {

        $validated = $request->validated();

        $user = User::query()->where('id', $validated['id'])->first();
        if (!$user) {
            return back()->with(['error' => 'user do not exist'], Response::HTTP_NOT_FOUND);
        }

        if (Auth::user()->role == 'moderator' || Auth::user()->role == 'user' || Auth::user()->role == 'admin') {
            if (Auth::user()->id !== $user->id) {
                return back()->with(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
            }
        }


        $name = $validated['name'] ?? $user->name;
        $gender = $validated['gender'] ?? $user->gender;
        $firstName = $validated['FirstName'] ?? $user->FirstName;
        $lastName = $validated['LastName'] ?? $user->LastName;
        $avatar = $validated['avatar'] ?? $user->avatar;

        if ($user->name == $name && $user->gender == $gender && $user->firstName == $firstName && $user->lastName == $lastName && $user->avatar == $avatar) {
            return back()->with(['ok' => 'nothing to update'], Response::HTTP_OK);
        }

        $password = $validated['password'] ?? '';

        if ($password) {
            $hashedpassword = Hash::make($validated['password']);
        }

        $user->name = $name;
        $user->password = $hashedpassword ?? $user->password;
        $user->gender = $gender;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->avatar = $avatar;
        $user->save();

        return back()->with(['success' => 'user has been updated'], Response::HTTP_ACCEPTED);
    }

    public function verification(Request $request) {

        $user_id = $request->input('user_id');
        $verification = Verification::query()->where('user_id', $user_id)->first();
        $user = User::query()->where('id', $user_id)->first();

        if (!$user) {
            return back()->with(['error' => 'user do not exist']);
        }

        $mailable = new VerificationMail($user, $verification->hash);
        Mail::to($user->email)->send($mailable);

        return back()->with(['success' => 'email has been sent']);
    }

}
