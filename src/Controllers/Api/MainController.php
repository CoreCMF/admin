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
        $routes = $this->menuModel->getGroupRoutes($this->group);

        $builderMain = $this->builderMain;
        $routes = $builderMain->setRouteComponent($routes,'<bve-index/>');
        $builderMain->route([
          'path'  =>  '/admin/login',
          'name'  =>  'api.admin.login',
          'apiUrl'  =>  route('api.admin.auth'),
          'children'  =>  null,
          'component' =>  '<cve-login/>'
        ]);
        $builderMain->route([
          'path'  =>  '/admin',
          'name'  =>  'admin',
          'apiUrl'  =>  null,
          'children'  =>  $routes,
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
