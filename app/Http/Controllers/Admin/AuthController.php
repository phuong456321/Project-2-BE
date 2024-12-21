<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('admin.songs');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
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
