<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            $status = Password::sendResetLink($request->only('email'));
            return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Password reset link sent.'], 200)
                : response()->json(['message' => 'Failed to send password reset link.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function reset(Request $request, $token)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);
            $email = $request->email;
            $password = $request->password;
            $status = Password::reset((array) [
                'email' => $email,
                'password' => $password,
                'token' => $token,
            ] , function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            });
            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Password reset successful.'], 200)
                : response()->json(['message' => 'Password reset failed.'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function showResetForm($token)
    {
        return response()->json(['token' => $token], 200);
    }
}
