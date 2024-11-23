<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            // Chuyển hướng đến trang yêu cầu xác thực email
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
