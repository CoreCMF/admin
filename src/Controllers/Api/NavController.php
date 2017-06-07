<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Menu;

class NavController extends Controller
{
    private $builderMain;
    private $menuModel;
    private $group = 'admin';
    /** return  CoreCMF\core\Builder\Main */
    public function __construct(Menu $MenuPr)
    {
        $this->builderMain = resolve('builderAdminMain');        //全局统一实例
    }
    public function top()
    {
        $topNav = config('admin.topNav');
        // sidebar apiUrl 地址
        $topNav['apiUrl'] = route('api.admin.nav.sidebar');

        $builderMain = $this->builderMain;
        $builderMain->topNavDefaultActive(config('admin.topNav.name'));
        $builderMain->topNavList($topNav);
        return $builderMain->getTopNavs();
    }
    public function sidebar()
    {
        dd('sidebar');
    }
}
