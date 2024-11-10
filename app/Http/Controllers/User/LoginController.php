<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user is verified
        if (!$user->hasVerifiedEmail()) {
            Auth::logout(); // Log the user out
            return response()->json(['message' => 'Your email address is not verified. Please check your email for the verification link.'], 403);
        }

        //generate token
        $accessToken = $user->createtoken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $accessToken,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
