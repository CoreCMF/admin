<?php

namespace CoreCMF\Admin\App\Http\Middleware;

use Closure;
use Route;
use App\Http\Controllers\Controller;
use CoreCMF\Core\App\Models\Permission;

class CheckRole
{
    private $PermissionPepo;

    public function __construct(Permission $PermissionPepo)
    {
        $this->PermissionModel = $PermissionPepo;
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
            return $error = $this->error('你没有后台管理权限!!!', '系统会自动记录您现在的请求,请保证您现在的请求是合法的!');
        }
        if (!$request->user()->hasRole('admin')) {
            if ($this->isCheck($currentRouteName)) {
                if (!$request->user()->can($currentRouteName)) {
                    return $this->error('您没有相关权限!!!', '请您联系超级管理员添加!', 'warning');
                }
            }
        }
        return $next($request);
    }
    public function error($error, $description=null, $type = 'error')
    {
        $builderForm = resolve('builderForm');//自动构建 builderForm
        $message = [
            'message'   => $error,
            'type'      => $type,
        ];
        $builderForm->item(['name' => 'entrust',  'type' => 'alert', 'title' => $error, 'description'=> $description, 'itemType'=> $type])
                    ->config('formReset', ['hidden'=>true ])
                    ->config('formSubmit', ['hidden'=>true ]);
        return resolve('builderHtml')->item($builderForm)->message($message)->response();
    }
    /**
     * 跳过不检查权限的路由
     */
    public function isCheck($currentRouteName)
    {
        return in_array($currentRouteName, config('admin.skipCheck'))? false: true;
    }
}
