<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 监听并记录执行的SQL语句
        DB::listen(function ($query) {
            $sql = vsprintf(str_replace("?", "%s", $query->sql), $query->bindings);
            Log::info(sprintf("[DB QUERY] TIME: %s ms; SQL: %s", $query->time, $sql));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
