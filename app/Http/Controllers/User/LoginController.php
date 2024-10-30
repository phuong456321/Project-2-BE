<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

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

        //generate token
        $accessToken = $user->createtoken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $accessToken,
            'token_type' => 'Bearer'
        ], 200);
    }
}
