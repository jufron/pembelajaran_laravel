<?php

namespace App\Providers;

use App\Contracts\UserService;
use App\Repositories\UserServiceImplement;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public array $singletons = [
        UserService::class  => UserServiceImplement::class
    ];


    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides (): array
    {
        return [
            UserService::class,
            UserServiceImplement::class
        ];
    }
}
