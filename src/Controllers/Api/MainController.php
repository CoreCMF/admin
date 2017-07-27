<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Menu;

class MainController extends Controller
{
    private $builderMain;
    private $menuModel;
    private $group = 'admin';
    /** return  CoreCMF\core\Builder\Main */
    public function __construct(Menu $MenuPro)
    {
        $this->builderMain = resolve('builderAdminMain');        //全局统一实例
        $this->menuModel = $MenuPro;
    }
    public function index()
    {
        $builderMain = $this->builderMain;

        $builderMain->route([
          'path'  =>  '/admin/login',
          'name'  =>  'admin.login',
          'apiUrl'  =>  null,
          'children'  =>  $builderMain->setRouteComponent([[
            'path'  =>  '/admin/login',
            'name'  =>  'api.admin.login',
            'meta'    =>[ 'apiUrl' => route('api.admin.auth.auth') ]
          ]],'<bve-index/>'),
          'component' =>  '<cve-login/>'
        ]);

        $routes = $this->menuModel->getGroupRoutes($this->group);
        $builderMain->route([
          'path'  =>  '/admin',
          'name'  =>  'admin',
          'apiUrl'  =>  null,
          'children'  =>  $builderMain->setRouteComponent($routes,'<bve-index/>'),
          'component' =>  '<cve-layout/>'
        ]);

        $builderMain->config('homeRouterNmae','api.admin.dashboard.index');
        $builderMain->config('loginRouterNmae','api.admin.login');
        $builderMain->config('topNavActive',config('admin.topNav.name'));

        $builderMain->apiUrl('topNav',      route('api.admin.nav.top'));
        $builderMain->apiUrl('logout',      route('api.admin.auth.logout'));
        $builderMain->apiUrl('authCheck',   route('api.admin.auth.check'));

        return $builderMain->response();
    }
}
