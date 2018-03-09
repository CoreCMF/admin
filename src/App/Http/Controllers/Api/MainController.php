<?php

namespace CoreCMF\Admin\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Admin\App\Models\Menu;

class MainController extends Controller
{
    private $builderMain;
    private $menuModel;
    private $group = 'admin';
    /** return  CoreCMF\Core\Builder\Main */
    public function __construct(Menu $MenuPro)
    {
        $this->menuModel = $MenuPro;
        $this->builderMain = resolve('builderMain')->event('adminMain');        //全局统一实例
    }
    public function index()
    {
        $this->builderMain->route([
          'path'  =>  '/admin/login',
          'name'  =>  'admin.login',
          'apiUrl'  =>  null,
          'children'  =>  $this->builderMain->setRouteComponent([[
            'path'  =>  '/admin/login',
            'name'  =>  'admin.login.index',
            'meta'    =>[ 'apiUrl' => route('api.admin.auth.index') ]
          ]], '<bve-index/>'),
          'component' =>  '<cve-login/>'
        ]);

        $routes = $this->menuModel->getGroupRoutes($this->group);
        $this->builderMain->route([
          'path'  =>  '/admin',
          'name'  =>  'admin',
          'apiUrl'  =>  null,
          'children'  =>  $this->builderMain->setRouteComponent($routes, '<bve-index/>'),
          'component' =>  '<cve-layout/>'
        ]);

        $this->builderMain->config('homeRouterNmae', 'api.admin.dashboard.index');
        $this->builderMain->config('loginRouterNmae', 'admin.login.index');
        $this->builderMain->config('topNavActive', config('admin.topNav.name'));

        $this->builderMain->apiUrl('topNav', route('api.admin.nav.top'));
        $this->builderMain->apiUrl('logout', route('api.admin.auth.revoke'));
        $this->builderMain->apiUrl('authCheck', route('api.admin.auth.check'));

        return resolve('builderHtml')->main($this->builderMain)->response();
    }
}
