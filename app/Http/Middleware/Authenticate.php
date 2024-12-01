<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    protected function redirectTo($request)
    {
        // Kiểm tra nếu route là admin
        if ($request->is('admin/*')) {
            return route('admin.login'); // Chuyển đến trang login admin
        }

        // Mặc định chuyển đến trang login user thường
        return route('login');
    }
}
