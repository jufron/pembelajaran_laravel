<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Providers\Guard\TokenGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('token', function (Application $app, string $name, array $config) {
            $tokenGuard = new TokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make(Request::class)
            );
            app()->refresh('request', $tokenGuard, 'setRequest');
            return $tokenGuard;
        });
    }
}
