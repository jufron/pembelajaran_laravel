<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        DB::listen( function ($query) {
            $sql        = $query->sql;
            $time       = $query->time;
            $bindings   = $query->bindings;
            Log::info("Query : $sql, Bindings : " . json_encode($bindings) . " Excecution Time : $time");
        });
    }
}
