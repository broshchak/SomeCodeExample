<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DateCheckServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('dateCheck', 'App\Services\DateCheck');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
