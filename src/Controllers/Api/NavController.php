<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class NavController extends Controller
{
    private $menuModel;
    private $group = 'admin';
    /** return  CoreCMF\core\Builder\Main */
    public function __construct()
    {

    }
    public function top()
    {
        $top = config('admin.top');
        // sidebar apiUrl 地址
        $top['apiUrl'] = route('api.admin.nav.sidebar');
        return $top;
    }
    public function sidebar()
    {
        dd('sidebar');
        // $topNavs = $this->menuModel->getGroupRoutes($this->group);                

        // return $topNavs->response();
    }
}
