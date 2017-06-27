<?php

namespace CoreCMF\admin\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function index()
    {
      $form = resolve('builderForm')
              ->item(['name' => 'username',      'type' => 'text',     'label' => '配置名称',     'placeholder' => '配置名称'])
              ->item(['name' => 'password',      'type' => 'text',     'label' => '配置标题',     'placeholder' => '配置标题'])
              ->apiUrl('submit',route('api.admin.auth.login'));
      $html = resolve('builderHtml')
                ->title('后台登陆')
                ->item($form)
                ->itemConfig('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
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
    public function postLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password]) ||
            Auth::attempt(['mobile' => $request->username, 'password' => $request->password]) ||
            Auth::attempt(['name' => $request->username, 'password' => $request->password]))
        {
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
        return response()->json($data, 200);
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
