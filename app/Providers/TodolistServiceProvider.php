<?php

namespace App\Providers;

use App\Services\Todolist\{
    TodolistService,
    TodolistServiceImplementation
};
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TodolistServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        app()->singleton(TodolistService::class, TodolistServiceImplementation::class);
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
            TodolistService::class
        ];
    }
}
