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
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', '=', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Email or Password is incorrect'], 401);
            }

            // Check if the user is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout(); // Log the user out
                return response()->json(['message' => 'Your email address is not verified. Please check your email for the verification link.'], 403);
            }

            Auth::login($user);
            //generate token
            $accessToken = $user->createtoken('access_token')->plainTextToken;
            $cookie = cookie('session_id', session()->getId(), 60); // 60 phút
            return response()->json([
                'message' => 'Logged in successfully',
                'token' => $accessToken,
                    'token_type' => 'Bearer'
                ], 200)->withCookie($cookie);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
