<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Menu;

class NavController extends Controller
{
    private $builderMain;
    private $menuModel;
    private $container;
    private $name;
    /** return  CoreCMF\core\Builder\Main */
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
        $this->builderMain->menus(
            $this->menuModel->getGroupMenus($this->name)
        );
        return $this->container->make('builderHtml')->main($this->builderMain)->response();
    }
}
