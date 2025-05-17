<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Verification;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Mail;
use \App\Mail\VerificationMail;
use Illuminate\Support\Facades\Hash;
use App\Enums\Gender;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * Description of UserController
 *
 * @author linux
 */
class UserController extends Controller {

    public function index() {
        $this->authorize('viewAny', User::class);

        $user = Auth::user();
        $users = User::query()->get();

        return view('user', ['user' => $user, 'users' => $users]);
    }

    public function delete(DeleteUserRequest $request) {
        $validated = $request->validated();

        $user = User::query()->where('id', $validated['id'])->first();

        if (!$user) {
            return back()->with(['error' => 'user does not exist'], ResponseAlias::HTTP_NOT_FOUND);
        }

        // Check authorization using policy
        $this->authorize('delete', $user);
        $user->delete();

        return back()->with(['success' => 'user has been deleted'], ResponseAlias::HTTP_ACCEPTED);
    }

    public function update(UpdateUserRequest $request) {
        $validated = $request->validated();

        $user = User::query()->where('id', $validated['id'])->first();
        if (!$user) {
            return back()->with(['error' => 'user does not exist'], ResponseAlias::HTTP_NOT_FOUND);
        }

        // Check authorization using policy
        $this->authorize('update', $user);

        $name = $validated['name'] ?? $user->name;
        $gender = isset($validated['gender']) ? intval($validated['gender']) : $user->gender->value;
        $firstName = $validated['FirstName'] ?? $user->FirstName;
        $lastName = $validated['LastName'] ?? $user->LastName;
        $avatar = $validated['avatar'] ?? $user->avatar;

        if ($user->name == $name && $user->gender->value == $gender && $user->firstName == $firstName && $user->lastName == $lastName && $user->avatar == $avatar) {
            return back()->with(['ok' => 'nothing to update'], ResponseAlias::HTTP_OK);
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

        return back()->with(['success' => 'user has been updated'], ResponseAlias::HTTP_ACCEPTED);
    }

    public function verification(Request $request) {
        $user_id = $request->input('user_id');
        $verification = Verification::query()->where('user_id', $user_id)->first();
        $user = User::query()->where('id', $user_id)->first();

        if (!$user) {
            return back()->with(['error' => 'user does not exist']);
        }

        // Check authorization using policy
        $this->authorize('verify', $user);

        $mailable = new VerificationMail($user, $verification->hash);
        Mail::to($user->email)->send($mailable);

        return back()->with(['success' => 'email has been sent']);
    }

}
