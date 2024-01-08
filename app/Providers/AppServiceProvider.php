<?php

namespace App\Providers;

use App\Models\Vocher;
use App\Models\Product;
use App\Models\Costumer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        DB::listen(function (QueryExecuted $query) {
            $sql  = $query->sql;
            $time = $query->time;
            Log::info("query : $sql, Time : $time");
        });

        DB::whenQueryingForLongerThan(400, function (Connection $cnnection, QueryExecuted $query) {
            $sql    = $query->sql;
            $time   = $query->time;
            Log::warning("performance query slow : $sql, Excecution time : $time");
        });

        // Relation::enforceMorphMap([
        //     'product'   => Product::class,
        //     'vocher'    => Vocher::class,
        //     'costumer'  => Costumer::class
        // ]);
    }
}
