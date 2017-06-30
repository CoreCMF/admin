<?php

namespace CoreCMF\admin\Controllers\Api;

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
      $form = resolve('builderForm')
              ->item([
                      'type' => 'html',
                      'style' => [ 'margin-bottom'=> '25px', 'text-align'=>'center' ],
                      'data' => '<img src="http://vueadmin.hinplay.com/static/images/a5ceee8b.png">'
                    ])
              ->item(['name' => 'username',      'type' => 'text',     'placeholder' => '用户名/手机/邮箱'])
              ->item(['name' => 'password',      'type' => 'password',    'placeholder' => '请输入账户名称密码'])
              ->apiUrl('submit',route('api.admin.auth.login'))
              ->config('formStyle',[ 'width'=>'300px', 'padding'=>'20px 10px' ])
              ->config('formSubmit',[ 'name'=>'登陆', 'style'=> ['width'=>'100%'] ])
              ->config('formReset',['style'=> ['display'=>'none'] ])
              ->config('labelWidth','0');
      $html = resolve('builderHtml')
                ->title('后台登陆')
                ->item($form)
                ->response();
      return $html;
    }
    public function authCheck()
    {
        if (Auth::check()) {
            $data = [
                    'message'   => '登录状态正常！您访问的页面可能不存在！',
                    'type'      => 'info',
                    'state'     => true
                ];
            return response()->json($data, 200);
        }else{
            $data = [
                    'title'     => '未登录！',
                    'message'   => '未登录正在跳转登录页面请稍后!',
                    'type'      => 'warning',
                    'state'     => false
                ];
            return response()->json($data, 200);
        }
    }
    public function postLogin(Request $request, Response $response)
    {
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password]) ||
            Auth::attempt(['mobile' => $request->username, 'password' => $request->password]) ||
            Auth::attempt(['name' => $request->username, 'password' => $request->password]))
        {
           //设置Passport认证Cookie
            $cookie = $this->cookieFactory->make(
                Auth::id(), $request->session()->token()
            );
            $data = [
                    'message'   => '登录已成功！正在跳转请稍后!',
                    'type'      => 'success',
                    'state'     => true
                ];
        } else {
            $data = [
                    'message'   => '登录失败！请检查账号密码是否正确!',
                    'type'      => 'error',
                    'state'     => false
                ];
        }
        return response()->json($data, 200)->cookie($cookie);
    }
    /**
     * [postLogout 用户退出]
     * @author BigRocs
     * @email    bigrocs@qq.com
     * @DateTime 2017-03-01T11:44:04+0800
     * @return   [type]                   [description]
     */
    public function postLogout()
    {
        $data = [
                    'message'   => '用户退出成功!',
                    'type'      => 'success',
                    'state'     => true
                ];
        Auth::logout();
        return response()->json($data, 200);

    }
}
