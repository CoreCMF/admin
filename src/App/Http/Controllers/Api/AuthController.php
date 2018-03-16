<?php

namespace CoreCMF\Admin\App\Http\Controllers\Api;

use Auth;
use CoreCMF\Core\App\Models\PassportClient;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser as JwtParser;
use Laravel\Passport\TokenRepository;

class AuthController
{
    /**
     * The JWT parser instance.
     *
     * @var \CoreCMF\Core\App\Models\PassportClient
     */
    protected $passportClient;
    /**
     * The JWT parser instance.
     *
     * @var \Lcobucci\JWT\Parser
     */
    protected $jwt;
    /**
     * The token repository instance.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokenRepository;

    /**
     * Create a new middleware instance.
     *
     * @param  ApiTokenCookieFactory  $cookieFactory
     * @return void
     */
    public function __construct(PassportClient $passportClientRepo, JwtParser $jwt, TokenRepository $tokenRepository)
    {
        $this->passportClient = $passportClientRepo;
        $this->jwt = $jwt;
        $this->tokenRepository = $tokenRepository;
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
              ->apiUrl('submit', route('api.admin.auth.token'))
              ->config('redirect', env('APP_URL').'/admin/dashboard')
              ->config('formStyle', [ 'width'=>'300px', 'padding'=>'20px 10px' ])
              ->config('formSubmit', [ 'name'=>'登陆', 'style'=> ['width'=>'100%'] ])
              ->config('formReset', ['style'=> ['display'=>'none'] ])
              ->config('labelWidth', '0');
        return resolve('builderHtml')->title('后台登陆')->item($form)->response();
    }
    /**
     * [getToken description]
     * @return   [type]         [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-03-09
     */
    public function getToken(Request $request)
    {
        $token = $this->passportClient->getPasswordToken($request->username, $request->password);
        if (!empty($token['status_code'])) {
            switch ($token['status_code']) {
                case 401:
                    $message = [
                            'message'   => '登录失败！请检查账号密码是否正确!',
                            'type'      => 'warning'
                        ];
                    break;
                default:
                    $message = [
                            'message'   => '登录失败!未知错误。',
                            'type'      => 'error'
                        ];
                    break;
            }
        } else {
            $token['status_code'] = 200;
            $message = [
                   'message'   => '登录已成功！正在跳转请稍后!',
                   'type'      => 'success',
               ];
        }
        return resolve('builderHtml')->config('token', $token)->message($message)->response();
    }
    /**
     * [revoke 撤销授权]
     * @param    AccessTokenEntityInterface $accessTokenEntity [description]
     * @return   [type]                                        [description]
     * @Author   bigrocs
     * @QQ       532388887
     * @Email    bigrocs@qq.com
     * @DateTime 2018-03-09
     */
    public function revoke(Request $request)
    {
        $tokenId = $this->jwt->parse($request->bearerToken())->getClaim('jti');
        if ($this->tokenRepository->revokeAccessToken($tokenId)) {
            $message = [
                        'message'   => '用户退出成功!',
                        'type'      => 'success',
                    ];
        } else {
            $message = [
                        'message'   => '用户退出失败!',
                        'type'      => 'warning',
                    ];
        }
        return resolve('builderHtml')->message($message)->response();
    }
}
