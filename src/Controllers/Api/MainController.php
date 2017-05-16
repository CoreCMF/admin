<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    private $builderMain;
    /** return  CoreCMF\core\Builder\Main */
    public function __construct()
    {
        $this->builderMain = resolve('builderMain');        //全局统一实例
    }
    public function index()
    {
        $builderMain = $this->builderMain;
        $builderMain->config('homeRouterNmae','api.admin.dashboard.index');
        $builderMain->config('loginRouterNmae','login');
        $builderMain->config('loginUrl','/admin/login');

        $builderMain->apiUrl('logout',      route('admin.auth.logout'));
        $builderMain->apiUrl('login',       route('admin.auth.login'));
        $builderMain->apiUrl('authCheck',   route('admin.auth.check'));

        return $builderMain->response();
    }
}
