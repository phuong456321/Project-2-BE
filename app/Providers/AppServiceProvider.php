<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;

use App\Console\Commands\UpdateUserPlans;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
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
}
