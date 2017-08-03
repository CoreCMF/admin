<?php

namespace CoreCMF\admin\Http\Middleware;

use Closure;
use Route;
use App\Http\Controllers\Controller;
use Illuminate\Container\Container;
use CoreCMF\core\Models\Permission;

class CheckRole
{
    private $PermissionPepo;
    private $container;

    public function __construct(
      Permission $PermissionPepo,
      Container $container
    )
    {
        $this->PermissionModel = $PermissionPepo;
        $this->container = $container;
    }
    /**
     * 运行请求过滤器
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentRouteName = Route::currentRouteName();

        if (!$request->user()->hasGroup('admin')) {
            return $error = $this->error('你没有后台管理权限');
        }
        if (!$request->user()->hasRole('admin')) {
            if (!$request->user()->can($currentRouteName)) {
                return $this->error('你没有相关权限!请联系总管理员添加!');
            }
        }
        return $next($request);
    }
    public function error($error)
    {
        $builderForm = $this->container->make('builderForm');//自动构建 builderForm
        $builderForm->item(['name' => 'entrust',  'type' => 'alert', 'title' => $error, 'itemType'=>'warning'])
                    ->config('formReset',['hidden'=>true ])
                    ->config('formSubmit',['hidden'=>true ]);
        return $this->container->make('builderHtml')->item($builderForm)->response();
    }

}
