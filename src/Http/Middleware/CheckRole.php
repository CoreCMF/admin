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
            return $error = $this->error('你没有后台管理权限!!!','系统会自动记录您现在的请求,请保证您现在的请求是合法!');
        }
        if (!$request->user()->hasRole('admin')) {
            if (!$request->user()->can($currentRouteName)) {
                return $this->error('您没有相关权限!!!','请您联系超级管理员添加!','warning');
            }
        }
        return $next($request);
    }
    public function error($error,$description=null,$type = 'error')
    {
        $builderForm = $this->container->make('builderForm');//自动构建 builderForm
        $builderForm->item(['name' => 'entrust',  'type' => 'alert', 'title' => $error, 'description'=> $description, 'itemType'=> $type])
                    ->config('formReset',['hidden'=>true ])
                    ->config('formSubmit',['hidden'=>true ]);
        return $this->container->make('builderHtml')->item($builderForm)->response();
    }

}
