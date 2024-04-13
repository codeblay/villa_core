<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    
    static function adminPath() : string {
        return config('admin.path');
    }
    
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->name('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'admin'])
                ->prefix(self::adminPath())
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['api', 'auth:seller', 'is_verified'])
                ->prefix('api/seller')
                ->group(base_path('routes/api_seller.php'));
                
            Route::middleware(['api', 'auth:buyer', 'is_verified'])
                ->prefix('api/buyer')
                ->group(base_path('routes/api_buyer.php'));

            Route::middleware('api')
                ->withoutMiddleware('app_key')
                ->prefix('api/callback')
                ->group(base_path('routes/api_callback.php'));

        });
    }
}
