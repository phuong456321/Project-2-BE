<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
{
    try {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email không tồn tại.');
        }

        // Tạo token
        $token = Str::random(60);

        // Lưu token vào bảng password_resets
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Gửi email đặt lại mật khẩu
        Mail::send('emails.reset_password', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email)->subject('Đặt lại mật khẩu của bạn');
        });

        return redirect()->route('home')->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
    }
}


public function reset(Request $request)
{
    try {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);

        // Kiểm tra token
        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return redirect()->back()->with('error', 'Token không hợp lệ.');
        }

        // Cập nhật mật khẩu
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            // Xóa tất cả các phiên của người dùng
            DB::table('sessions')->where('user_id', $user->id)->delete();

            // Xóa token sau khi đặt lại mật khẩu thành công
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            flash()->addSuccess('Đặt lại mật khẩu thành công. Vui lòng đăng nhập lại.');
            return redirect()->route('home');
        }

        return redirect()->back()->with('error', 'Không tìm thấy người dùng.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
    }
}


    public function showResetForm($token, Request $request)
    {
        return view('user.changepassword', ['token' => $token,
        'email' => $request->email]);
    }

    public function showForgotForm()
    {
        return view('emails.email');
    }
}
