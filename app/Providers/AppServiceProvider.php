<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
// use Illuminate\Routing\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{

    // public const HOME = '/home';
    
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
    }
}
