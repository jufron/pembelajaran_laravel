<?php

namespace App\Providers;

use App\Data\{
    Foo,
    Bar,
    Mobil,
    Truck
};
use App\Services\{
    GoodbyeeServiceIndonesia,
    GoodbyeeServiceInterface,
    HelloInterface,
    HelloServiceIndonesia
};
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class FooBarServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * * ini ke depan tidak bolhe lagi menggunakan property singleton
     * * tapi masih direkomendasikan di dokumentasi
     */
    public array $singletons  = [
        GoodbyeeServiceInterface::class => GoodbyeeServiceIndonesia::class,
        HelloInterface::class => HelloServiceIndonesia::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Foo::class, function($app) {
            return new Foo();
        });
        $this->app->singleton(Bar::class, function($app) {
            return new Bar($app->make(Foo::class));
        });

        $this->app->singleton(Mobil::class);
        $this->app->singleton(Truck::class, function (Application $app) {
            return new Truck($app->make(Mobil::class), 10);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides() : array
    {
        return [
            Foo::class,
            Bar::class,
            Mobil::class,
            Truck::class,
            HelloServiceIndonesia::class,
            GoodbyeeServiceIndonesia::class
        ];
    }
}
