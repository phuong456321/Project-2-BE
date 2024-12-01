<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;

use App\Console\Commands\UpdateUserPlans;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    // protected function configureRateLimiting(){
    //     RateLimiter::for('api', function(Request $request){
    //         return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    //     });
    // }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $this->configureRateLimiting();
        $this->routes(function(){
            Route::middleware('web')->group(base_path('routes/web.php'));
        });

        Artisan::command('user:update-plans', function () {
            $this->call(UpdateUserPlans::class);
        })->daily(); // Chạy hàng ngày
    }

    public function handle($request, Closure $next)
    {
        // Kiểm tra xem người dùng có cookie "remember_token" không
        if (Cookie::has('remember_token')) {
        $token = Cookie::get('remember_token');

        // Tìm token trong database
        $user = User::where('remember_token', hash('sha256', $token))->first();

        if ($user) {
            // Tìm người dùng tương ứng và đăng nhập cho họ
                Auth::login($user);
            }
        }
        return $next($request);
    }
}
