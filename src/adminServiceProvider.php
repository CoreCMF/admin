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
        //配置路由
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        //视图路由
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/../databases/migrations');
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