<?php

namespace App\Modules\Ordering;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

final class OrderingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for(
            'qr-scan',
            fn (Request $request): Limit => Limit::perMinute(30)
                ->by($request->ip() ?? 'unknown')
        );
    }
}