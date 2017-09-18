<?php

namespace CoreCMF\Admin;

use Route;
use Illuminate\Support\ServiceProvider;
use CoreCMF\Core\Support\Builder\Main as builderAdminMain;

class adminServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Admin\App\Console\InstallCommand::class,
        \CoreCMF\Admin\App\Console\UninstallCommand::class,
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //注册 别名 role 权限中间件
        Route::aliasMiddleware('adminRole', App\Http\Middleware\CheckRole::class);
        //加载artisan commands
        $this->commands($this->commands);
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'admin');
        //配置路由
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/Databases/migrations');
        //设置发布前端文件
        $this->publishes([
            __DIR__.'/../resources/mixes/vue-admin/dist/vendor/' => public_path('vendor'),
        ], 'admin');
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
