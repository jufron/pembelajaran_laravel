<?php

namespace App\Providers;

use App\Services\Mpl\UserServiceMpl;
use App\Services\Interface\UserService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */

    public function register(): void
    {
        app()->singleton(UserService::class, UserServiceMpl::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides(): array
    {
        return [
            UserService::class
        ];
    }
}
