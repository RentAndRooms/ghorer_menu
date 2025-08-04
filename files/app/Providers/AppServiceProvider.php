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
        DB::listen(function($query){
            $threshold = 1000;
            if($query->time > $threshold){
                Log::channel('daily')->warning('Slow Queries : ', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . ' ms'
                ]);
            }
        });
    }
}
