<?php

namespace CoreCMF\admin;

use Illuminate\Support\ServiceProvider;
use CoreCMF\core\Builder\Main as builderAdminMain;

class adminServiceProvider extends ServiceProvider
{
    protected $commands = [
        'CoreCMF\admin\Commands\InstallCommand',
        'CoreCMF\admin\Commands\UninstallCommand',
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //加载artisan commands
        $this->commands($this->commands);
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'admin');
        //配置路由
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        //视图路由
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/../databases/migrations');
        //发布seed填充文件
        $this->publishes([
            __DIR__.'/../databases/seeds/' => database_path('seeds')
        ], 'seeds');
        $this->publishes([
            __DIR__.'/../resources/mixes/vue-admin/dist/assets/' => public_path('vendor'),
        ], 'public');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('builderAdminMain', function () {
            return new builderAdminMain();
        });
    }
}
