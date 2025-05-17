<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\Role;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\VerificationMail;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Authentication Controller
 *
 * Handles user authentication, registration, and logout functionality.
 */
class AuthController extends Controller
{

    /**
     * Handle user login
     *
     * @param LoginUserRequest $request
     * @return RedirectResponse
     */
    public function login(LoginUserRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return back()->with(['success' => 'You have been logged in successfully']);
        }

        return back()->with(['error' => 'Credentials are incorrect or account does not exist']);
    }

    /**
     * Handle user registration
     *
     * @param RegisterUserRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (User::where('email', $validated['email'])->exists()) {
            return back()->with(['error' => 'User with this email already exists']);
        }

        // Create new user
        $user = $this->createUser($validated);

        // Create verification record
//        $verificationHash = $this->createVerification($user);

        // Authenticate the user
        Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        // Send verification email
//        $this->sendVerificationEmail($user, $verificationHash);

        return back()->with(['success' => 'User has been created successfully']);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    private function createUser(array $data): User
    {
        $user = new User();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = Hash::make($data['password']);
        $user->gender = Gender::UNKNOWN->value; // Set default gender to unknown
        $user->role = Role::USER->value; // Set default role to user
        $user->save();

        return $user;
    }

    /**
     * Create verification record for user
     *
     * @param User $user
     * @return string The verification hash
     */
    private function createVerification(User $user): string
    {
        $verification = new Verification();
        $verification->user_id = $user->id;
        $verification->hash = md5($user->id);
        $verification->save();

        return $verification->hash;
    }

    /**
     * Send verification email to user
     *
     * @param User $user
     * @param string $verificationHash
     * @return void
     */
    private function sendVerificationEmail(User $user, string $verificationHash): void
    {
        $mailable = new VerificationMail($user, $verificationHash);
        Mail::to($user->email)->send($mailable);
    }

    /**
     * Handle user logout
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
