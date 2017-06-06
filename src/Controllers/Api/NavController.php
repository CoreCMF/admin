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
        dd('top');
        // $topNavs = $this->menuModel->getGroupRoutes($this->group);                

        // return $topNavs->response();
    }
    public function sidebar()
    {
        dd('sidebar');
        // $topNavs = $this->menuModel->getGroupRoutes($this->group);                

        // return $topNavs->response();
    }
}
