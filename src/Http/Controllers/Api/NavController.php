<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;

use CoreCMF\Admin\Http\Models\Menu;

class NavController extends Controller
{
    private $builderMain;
    private $menuModel;
    private $container;
    private $name;
    /** return  CoreCMF\Core\Builder\Main */
    public function __construct(Menu $MenuPro, Container $container)
    {
        $this->menuModel = $MenuPro;
        $this->container = $container;
        $this->builderMain = $this->container->make('builderAdminMain');        //全局统一实例
        $this->name = config('admin.topNav.name');
    }
    public function top()
    {
        $topNav = config('admin.topNav');
        // sidebar apiUrl 地址
        $topNav['apiUrl'] = route('api.admin.nav.sidebar');
        $this->builderMain->topNav($topNav);
        return $this->container->make('builderHtml')->main($this->builderMain)->response();
    }
    public function sidebar()
    {
        $menuData = $this->menuModel->getGroupMenus($this->name);
        $menus = $menuData->filter(function ($value, $key) {
            /**
             * 1、通过拥有此权限的用户
             * 2、通过超级管理员
             * 3、通过不包含路由的菜单
             */
            if (Auth::user()->can($value->api_route) || Auth::user()->hasRole('admin') || empty($value->api_route)) {
              return $value;
            }
        });
        $this->builderMain->menus($menus);
        return $this->container->make('builderHtml')->main($this->builderMain)->response();
    }
}
