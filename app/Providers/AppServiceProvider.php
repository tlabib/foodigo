<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        RateLimiter::for('authenticated-api', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : $request->ip();

            return Limit::perMinute(60)->by($key);
        });

        RateLimiter::for('orders-create-api', function (Request $request) {
            $key = $request->user()?->id ? 'user:'.$request->user()->id : $request->ip();

            return Limit::perMinute(10)->by('orders:create:'.$key);
        });
    }
}
