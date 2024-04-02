<?php

namespace App\Providers;

use App\Providers\Guard\TokenGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        DB::listen(function ($query) {
            $sql = $query->sql;
            $bindings = $query->bindings;
            $time = $query->time;

            // Log sesuai kebutuhan, misalnya ke console
            info('Query Executed:', compact('sql', 'bindings', 'time'));
        });

        Auth::extend('token', function (Application $app, string $name, array $config) {
            $tokenGuard = new TokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make(Request::class)
            );
            $app->refresh('request', $tokenGuard, 'setRequest');
            return $tokenGuard;
        });

    }
}
