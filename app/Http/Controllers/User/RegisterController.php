<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth\VerifiesEmails;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{3,}$/',
            'password' => 'required|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar_id' => 1,
            'verified' => true,
        ]);
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = $request->user();
        $user->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email sent'], 200);
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id); // Find the user by ID

        // Check if the hash matches
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        // Mark the email as verified
        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully.']);
    }

}
