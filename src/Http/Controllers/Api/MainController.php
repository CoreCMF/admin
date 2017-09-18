<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;

use CoreCMF\Admin\Http\Models\Menu;

class MainController extends Controller
{
    private $container;
    private $builderMain;
    private $menuModel;
    private $group = 'admin';
    /** return  CoreCMF\Core\Builder\Main */
    public function __construct(Menu $MenuPro, Container $container)
    {
        $this->menuModel = $MenuPro;
        $this->container = $container;
        $this->builderMain = $this->container->make('builderAdminMain');        //全局统一实例
    }
    public function index()
    {
        $this->builderMain->route([
          'path'  =>  '/admin/login',
          'name'  =>  'admin.login',
          'apiUrl'  =>  null,
          'children'  =>  $this->builderMain->setRouteComponent([[
            'path'  =>  '/admin/login',
            'name'  =>  'admin.login',
            'meta'    =>[ 'apiUrl' => route('admin.auth.auth') ]
          ]],'<bve-index/>'),
          'component' =>  '<cve-login/>'
        ]);

        $routes = $this->menuModel->getGroupRoutes($this->group);
        $this->builderMain->route([
          'path'  =>  '/admin',
          'name'  =>  'admin',
          'apiUrl'  =>  null,
          'children'  =>  $this->builderMain->setRouteComponent($routes,'<bve-index/>'),
          'component' =>  '<cve-layout/>'
        ]);

        $this->builderMain->config('homeRouterNmae','api.admin.dashboard.index');
        $this->builderMain->config('loginRouterNmae','admin.login');
        $this->builderMain->config('topNavActive',config('admin.topNav.name'));

        $this->builderMain->apiUrl('topNav',      route('api.admin.nav.top'));
        $this->builderMain->apiUrl('logout',      route('admin.auth.logout'));
        $this->builderMain->apiUrl('authCheck',   route('admin.auth.check'));

        return $this->container->make('builderHtml')->main($this->builderMain)->response();
    }
}
