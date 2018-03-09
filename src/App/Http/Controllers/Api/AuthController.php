<?php

namespace CoreCMF\Admin\App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\ApiTokenCookieFactory;

class AuthController extends Controller
{
    /**
     * The API token cookie factory instance.
     *
     * @var ApiTokenCookieFactory
     */
    protected $cookieFactory;
    /**
     * Create a new middleware instance.
     *
     * @param  ApiTokenCookieFactory  $cookieFactory
     * @return void
     */
    public function __construct(ApiTokenCookieFactory $cookieFactory)
    {
        $this->cookieFactory = $cookieFactory;
    }
    public function index()
    {
        $rules = [
            'username'=> [
                ['required' => true,  'message' => '请输入用户名/手机/邮箱', 'trigger'=> 'blur'],
            ],
            'password'=> [
                [ 'required'=> true, 'message'=> '请输入账户密码', 'trigger'=> 'blur' ],
            ],
        ];
        $form = resolve('builderForm')
              ->event('login') //绑定login事件
              ->item([
                      'type' => 'html',
                      'style' => [ 'margin-bottom'=> '25px', 'text-align'=>'center' ],
                      'data' => '<img src="http://vueadmin.hinplay.com/static/images/a5ceee8b.png">'
                    ])
              ->item(['name' => 'username',      'type' => 'text',     'placeholder' => '用户名/手机/邮箱'])
              ->item(['name' => 'password',      'type' => 'password',    'placeholder' => '请输入账户密码'])
              ->rules($rules)
              ->apiUrl('submit', route('admin.auth.login'))
              ->config('redirect', env('APP_URL').'/admin/dashboard')
              ->config('formStyle', [ 'width'=>'300px', 'padding'=>'20px 10px' ])
              ->config('formSubmit', [ 'name'=>'登陆', 'style'=> ['width'=>'100%'] ])
              ->config('formReset', ['style'=> ['display'=>'none'] ])
              ->config('labelWidth', '0');
        return resolve('builderHtml')->title('后台登陆')->item($form)->response();
    }
    public function getToken()
    {
    }
    public function authCheck()
    {
        if (Auth::check()) {
            if (Auth::user()->hasGroup('admin')) {
                $auth = true;
                $message = [
                        'message'   => '登录状态正常！您访问的页面可能不存在！',
                        'type'      => 'warning'
                    ];
            } else {
                $auth = false;
                $message = [
                        'message'   => '登录失败！您没有后台管理权限!',
                        'type'      => 'warning'
                    ];
            }
        } else {
            $auth = false;
            $message = [
                    'message'   => '未登录正在跳转登录页面请稍后!',
                    'type'      => 'error'
                ];
        }
        return resolve('builderHtml')->auth($auth)->message($message)->response();
    }
    public function postLogin(Request $request, Response $response)
    {
        $cookie = null;
        $email = ['email' => $request->username, 'password' => $request->password];
        $mobile = ['mobile' => $request->username, 'password' => $request->password];
        $name = ['name' => $request->username, 'password' => $request->password];
        if (Auth::attempt($email) || Auth::attempt($mobile) || Auth::attempt($name)) {
            if (Auth::user()->hasGroup('admin')) {
                //设置Passport认证Cookie
                $cookie = $this->cookieFactory->make(
                   Auth::id(),
                   $request->session()->token()
               );
                $auth = true;
                $message = [
                       'message'   => '登录已成功！正在跳转请稍后!',
                       'type'      => 'success',
                   ];
            } else {
                $auth = false;
                $message = [
                      'message'   => '登录失败！您没有后台管理权限!',
                      'type'      => 'warning'
                  ];
            }
        } else {
            $auth = false;
            $message = [
                    'message'   => '登录失败！请检查账号密码是否正确!',
                    'type'      => 'error'
                ];
        }
        return resolve('builderHtml')->auth($auth)->message($message)->cookie($cookie)->response();
    }
    /**
     * [postLogout 用户退出]
     * @return   [type]                   [description]
     */
    public function postLogout()
    {
        $message = [
                    'message'   => '用户退出成功!',
                    'type'      => 'success',
                ];
        $auth = false;
        Auth::logout();
        return resolve('builderHtml')->message($message)->response();
    }
}
