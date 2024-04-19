<?php

namespace App\Providers;

use App\Data\Bar;
use App\Data\Database;
use App\Data\Foo;
use App\Data\Log;
use App\Data\UserRepository;
use App\Service\Contract\HelloService;
use App\Service\HelloServiceIndonesia;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FooBarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public $singletons  = [
        HelloService::class => HelloServiceIndonesia::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        app()->singleton(Bar::class, function (Application $app) {
            return new Bar($app->make(Foo::class));
        });

        app()->singleton(Log::class, function (Application $app) {
            return new Log('ini adalah log');
        });

        app()->singleton(UserRepository::class, function (Application $app) {
            return new UserRepository(
                $app->make(Database::class),
                $app->make(Log::class)
            );
        });
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
            HelloService::class,
            Foo::class,
            Bar::class,
            Log::class,
            Database::class,
            UserRepository::class
        ];
    }
}
