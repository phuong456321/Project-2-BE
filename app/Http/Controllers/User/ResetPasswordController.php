<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return redirect()->route('home')->with('success', 'Password reset link sent.');
            } else {
                return redirect()->route('home')->with('error', 'Failed to send password reset link.');
            }
        } catch (\Exception $e) {
                return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function reset(Request $request, $token)
    {
        try {
            $request->validate(['email' => 'required|email', 'password' => 'required|min:8|confirmed']);
            // Thực hiện đặt lại mật khẩu
            $status = Password::reset($request->only('email', 'password') + ['token' => $token], function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            });

            if ($status === Password::PASSWORD_RESET) {
                // Đăng xuất người dùng sau khi đổi mật khẩu thành công
                Auth::logout();

                // Trả về thông báo thành công và yêu cầu đăng nhập lại
                return redirect()->route('home')->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại.');
            }

            return redirect()->back()->with('error', 'Đặt lại mật khẩu không thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function showResetForm($token)
    {
        return redirect()->route('home')->with('token', $token);
    }
}
