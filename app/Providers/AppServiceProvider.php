<?php

namespace App\Providers;

use App\Models\User;
use App\Services\RateLimitService;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        if (env('FAKE_NOW')) Carbon::setTestNow(env('FAKE_NOW'));

        RateLimitService::register();
    }
}
