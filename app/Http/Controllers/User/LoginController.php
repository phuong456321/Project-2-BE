<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    public function index()
    {
        flash()->option('timeout', 1000)->warning('Please login to continue');
        return redirect()->route('home');
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);
            if (!$validator->passes()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $remember = $request->has('remember');
            // Thử đăng nhập
            if (!Auth::attempt($request->only('email', 'password'))) {
                flash()->option('timeout', 2000)->error('Email or Password is incorrect');
                return redirect()->back()->with('message', 'Email or Password is incorrect')->withInput();
            }
            $user = User::where('email', $request->email)->first();
            Auth::login($user, $remember);
            // Check if the user is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->with('error', 'Your email address is not verified. Please check your email for the verification link.');
            }
            if($remember){
                $token = Str::random(60);
                $user->remember_token = hash('sha256', $token);
                $user->save();
                Cookie::queue('remember_token', $token, 60 * 24 * 30);
            }
            flash()->option('timeout', 2000)->success('Logged in successfully');
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
{
    try {
        // Kiểm tra và xóa remember_token nếu tồn tại trong cookie
        $rememberToken = $request->cookie('remember_token');
        if ($rememberToken) {
            // Tìm người dùng với remember_token trong cookie
            $user = User::where('remember_token', hash('sha256', $rememberToken))->first();
            if ($user) {
                // Xóa remember_token khỏi người dùng
                $user->remember_token = null;
                $user->save();
            }

            // Xóa cookie remember_token khỏi trình duyệt
            Cookie::queue(Cookie::forget('remember_token'));
        }

        // Hủy session
        $request->session()->flush();

        // Đăng xuất người dùng
        Auth::guard('web')->logout();

        // Thông báo thành công
        flash()->option('timeout', 2000)->success('Đăng xuất thành công!');

        return redirect()->route('home');
    } catch (\Exception $e) {
        // Nếu có lỗi, quay lại trang trước đó và hiển thị thông báo lỗi
        return redirect()->back()->with('error', $e->getMessage());
    }
}
}
