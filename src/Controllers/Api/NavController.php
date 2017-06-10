<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Menu;

class NavController extends Controller
{
    private $builderMain;
    private $menuModel;
    private $name;
    /** return  CoreCMF\core\Builder\Main */
    public function __construct(Menu $MenuPro)
    {
        $this->menuModel = $MenuPro;
        $this->builderMain = resolve('builderAdminMain');        //全局统一实例
        $this->name = config('admin.topNav.name');
    }
    public function top()
    {
        $topNav = config('admin.topNav');
        // sidebar apiUrl 地址
        $topNav['apiUrl'] = route('api.admin.nav.sidebar');

        $builderMain = $this->builderMain;
        $builderMain->addTopNav($topNav);
        return $builderMain->getTopNavs();
    }
    public function sidebar()
    {
        $sidebar = $this->menuModel->getGroupMenus($this->name);

        $builderMain = $this->builderMain;
        $builderMain->setMenus($sidebar);
        return $builderMain->getMenus();
    }
}
