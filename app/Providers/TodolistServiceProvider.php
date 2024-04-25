<?php

namespace App\Providers;

use App\Contracts\TodolistService;
use App\Repositories\TodoServiceImplementation;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TodolistServiceProvider extends ServiceProvider implements DeferrableProvider
{

    public array $singletons = [
        TodolistService::class  => TodoServiceImplementation::class
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

    public function provides(): array
    {
        return [
            TodolistService::class,
            TodoServiceImplementation::class
        ];
    }
}
