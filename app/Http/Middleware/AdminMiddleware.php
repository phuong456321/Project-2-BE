<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        if (Auth::user()->role == 'admin') {
            return $next($request);
        }
        Auth::guard('web')->logout();
        return redirect('/')->with('error', 'You are not authorized to access this page'); // Chuyển hướng nếu không phải admin
    }
}
