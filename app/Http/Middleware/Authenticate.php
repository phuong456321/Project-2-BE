<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        // Kiểm tra xem người dùng có cookie "remember_token" không
        if (Cookie::has('remember_token')) {
            $token = Cookie::get('remember_token');

            // Tìm token trong database
            $user = User::where('remember_token', hash('sha256', $token))->first();

            if ($user) {
                // Tìm người dùng tương ứng và đăng nhập cho họ
                Auth::guard('web')->login($user);
            }
        }
        return $next($request);
    }
}
