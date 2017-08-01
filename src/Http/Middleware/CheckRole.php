<?php

namespace CoreCMF\admin\Http\Middleware;

use Closure;
use Route;
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
        if (!$request->user()->hasRole('admin')) {
            if (!$request->user()->can($currentRouteName)) {
                if ($this->PermissionModel->isExist($currentRouteName)) {
                    dd('没有权限访问2');
                }
            }
        }

        return $next($request);
    }

}
