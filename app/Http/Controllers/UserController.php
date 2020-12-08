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

/**
 * Description of UserController
 *
 * @author linux
 */
class UserController extends Controller {

    public function create() {

    }

    public function delete(DeleteUserRequest $request) {
        $validated = $request->validated();

        $user = User::query()->where('id', $validated['id'])->first();

        if (!$user) {
            return response()->json(['error' => 'user do not exist'], Response::HTTP_NOT_FOUND);
        }

        if (Auth::user()->role == 'moderator' || Auth::user()->role == 'user') {
            if (!Auth::user()->id == $user->id) {
                if ($user->role == 'moderator' || $user->role == 'admin') {
                    return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
                }
            }
        }

        $user->delete();
        return response()->json(['success' => 'user has been deleted'], Response::HTTP_ACCEPTED);
    }

    public function update(UpdateUserRequest $request) {
        $validated = $request->validated();

        $user = User::query()->where('id', $validated['id'])->first();

        if (!$user) {
            return response()->json(['error' => 'user do not exist'], Response::HTTP_NOT_FOUND);
        }

        if (Auth::user()->role == 'moderator' || Auth::user()->role == 'user' || Auth::user()->role == 'admin') {
            if (!Auth::user()->id == $user->id) {
                return reponse()->json(['error' => 'you do not have premission'], Response::HTTP_FORBIDDEN);
            }
        }


        $name = $validated['name'] ?? $user->name;
        $password = $validated['password'] ?? $user->password;
        $role = $validated['role'] ?? $user->role;
        $gender = $validated['gender'] ?? $user->gender;
        $firstName = $validated['FirstName'] ?? $user->FirstName;
        $lastName = $validated['LastName'] ?? $user->LastName;
        $avatar = $validated['avatar'] ?? $user->avatar;

        if ($user->name == $name && $user->password == $password && $user->role == $role && $user->gender == $gender && $user->firstName == $firstName && $user->lastName == $lastName && $user->avatar == $avatar) {
            return response()->json(['ok' => 'nothing to update'], Response::HTTP_OK);
        }

        $user->name = $name;
        $user->password = $password;
        $user->role = $role;
        $user->gender = $gender;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->avatar = $avatar;
        $user->save();

        return response()->json(['success' => 'user has been updated'], Response::HTTP_ACCEPTED);
    }

}
