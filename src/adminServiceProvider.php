<?php

namespace CoreCMF\admin;

use Illuminate\Support\ServiceProvider;

class adminServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
    }
}