<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Core\Models\User;
use CoreCMF\Core\Models\Role;
use CoreCMF\Admin\Http\Models\Config;
use CoreCMF\Admin\Http\Validator\UserRules;

class UserController extends Controller
{
	/** @var userRepo */
    private $userModel;
    private $roleModel;
    private $configModel;
		private $container;
		private $rules;

    public function __construct(
			User $userRepo,
			Role $rolePepo,
			Config $configRepo,
			Container $container,
			UserRules $rules
		)
    {
        $this->userModel = $userRepo;
        $this->roleModel = $rolePepo;
        $this->configModel = $configRepo;
				$this->container = $container;
				$this->rules = $rules;
    }
    public function index(Request $request)
    {
				$pageSizes = $this->configModel->getPageSizes();
				$data = $this->container->make('builderModel')
                            ->request($request)
                            ->pageSize($this->configModel->getPageSize())
														->load(['roles','userInfos'])
                            ->getData($this->userModel);

				$rolesConfig = ['type'=>'primary',    'keyNmae'=>'display_name'];   // rolesTags  tags显示配置      valueName显示数据对象名称 如果不填写默认显示整个对象
        $pictureConfig = ['keyNmae'=>'avatarUrl', 'width'=>50, 'height'=>50, 'class'=>'img-responsive img-circle', 'alt'=>'用户头像'];
				$table = $this->container->make('builderTable')
																	->data($data['model'])
																	->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
																	->column(['prop' => 'user_infos', 'label'=> '头像',   'width'=> '90',    'type' => 'picture',    'config'=>$pictureConfig])
																	->column(['prop' => 'roles',      'label'=> '角色',   'minWidth'=> '120',    'type' => 'tags',       'config'=>$rolesConfig])
																	->column(['prop' => 'nickname',   'label'=> '昵称', 'minWidth'=> '120'])
																	->column(['prop' => 'name',       'label'=> '用户名', 'minWidth'=> '120'])
																	->column(['prop' => 'email',      'label'=> '邮箱',   'minWidth'=> '180'])
																	->column(['prop' => 'mobile',     'label'=> '手机',   'minWidth'=> '180'])
																	->column(['prop' => 'status',     'label'=> '状态',   'width'=> '90',      'type' => 'status'])
																	->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220',  'type' => 'btn'])
																	->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.user.user.add'),'title'=>'新增用户','icon'=>'fa fa-plus'])                         // 添加新增按钮
																	->topButton(['buttonType'=>'resume',     'apiUrl'=> route('api.admin.user.user.status')])                         // 添加启用按钮
																	->topButton(['buttonType'=>'forbid',     'apiUrl'=> route('api.admin.user.user.status')])                         // 添加禁用按钮
																	->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.user.user.delete')])                         // 添加删除按钮
																	->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.user.user.edit')])                         // 添加编辑按钮
																	->rightButton(['buttonType'=>'forbid',   'apiUrl'=> route('api.admin.user.user.status')])                       // 添加禁用/启用按钮
																	->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.user.user.delete')])                       // 添加删除按钮
																	->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])//分页设置
																	->searchTitle('请输入搜索内容')
																	->searchSelect(['id'=>'ID','name'=>'用户名','email'=>'邮箱','mobile'=>'手机'])
																	;
				return $this->container->make('builderHtml')
								          			->title('用户管理')
								          			->item($table)
								          			->response();
    }
    public function status(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $this->userModel->where('id', '=', $id)->update(['status' => $value]);
        }
        $message = [
										'message'   => '用户状态更改成功!',
                    'type'      => 'success',
									];
				return $this->container->make('builderHtml')
														   ->message($message)
														   ->response();
    }
    public function delete(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $response = $this->userModel->find($id)->forceDelete();
        }
        $message = [
                    'message'   => '后台用户删除成功!',
                    'type'      => 'success',
                ];
				return $this->container->make('builderHtml')
														   ->message($message)
														   ->response();
    }
    public function add(){
        $roles = $this->roleModel->all()->keyBy('id');
        $roles->transform(function ($item, $key) {
            $item['name'] = $item['display_name'];
            return $item;
        });
				$form = $this->container->make('builderForm')
								->item(['name' => 'nickname',      'type' => 'text',     'label' => '昵称' ])
								->item(['name' => 'name',      'type' => 'text',     'label' => '用户名' ])
								->item(['name' => 'email',     'type' => 'text',     'label' => '用户邮箱'   ])
								->item(['name' => 'mobile',    'type' => 'text',     'label' => '用户手机'   ])
								->item(['name' => 'password',  'type' => 'password', 'label' => '用户密码'   ])
								->item(['name' => 'checkPassword','type' => 'password','label' => '密码验证'])
								->item(['name' => 'roles',     'type' => 'checkbox', 'label' => '用户角色',  'value'=>['1'], 'options'=>$roles])
								->item(['name' => 'avatar',    'type' => 'picture',  'label' => '用户头像',  'uploadUrl'=> route('api.admin.system.upload.image'), 'width'=>'250px', 'height'=>'250px'])
								->item(['name' => 'integral',  'type' => 'number',   'label' => '用户积分'   ])
								->item(['name' => 'money',     'type' => 'number',   'label' => '用户余额'  ])
								->rules($this->rules->addUser())
								->apiUrl('submit',route('api.admin.user.user.store'))
								->config('labelWidth','100px');
				return $this->container->make('builderHtml')
									->title('新增用户')
									->item($form)
									->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
									->response();
    }
		public function check(Request $request){
				return $this->userModel->check($request);
		}
    public function store(Request $request)
    {
        $input = $request->all();
        $user = $this->userModel->create($input);
        $user->roles()->attach($request->roles);//多对多关联
        $response = $user->userInfos()->create($input);//插入关联数据库userInfos
        if ($response->wasRecentlyCreated) {
            $data = [
                        'message'   => '新增用户数据成功！!',
                        'type'      => 'success',
                    ];
        }else{
            $data = [
                        'message'   => '新增用户数据失败！!',
                        'type'      => 'error',
                    ];
        }
				return $this->container->make('builderHtml')
														   ->message($data)
														   ->response();
    }
    public function edit(Request $request){
        $users = $this->userModel->find($request->id);
        $users->load('roles');//加载关联权限数据
        $users->load('userInfos');//加载关联头像数据
        $users->roles->transform(function ($item, $key) {
            return $item['id'];
        });//处理集合只留id

				$roles = $this->roleModel->all()->keyBy('id');
				$roles->transform(function ($item, $key) {
						$item['name'] = $item['display_name'];
						return $item;
				});

				$form = $this->container->make('builderForm')
								->item(['name' => 'id',      	 'type' => 'text',     'label' => 'ID', 'disabled'=>true ])
								->item(['name' => 'nickname',      'type' => 'text',     'label' => '昵称' ])
								->item(['name' => 'name',      'type' => 'text',     'label' => '用户名' ])
								->item(['name' => 'email',     'type' => 'text',     'label' => '用户邮箱'   ])
								->item(['name' => 'mobile',    'type' => 'text',     'label' => '用户手机'   ])
								->item(['name' => 'password',  'type' => 'password', 'label' => '用户密码'   ])
								->item(['name' => 'checkPassword','type' => 'password','label' => '密码验证'])
								->item(['name' => 'roles',     'type' => 'checkbox', 'label' => '用户角色', 'options'=>$roles])
								->item(['name' => 'avatar',    'type' => 'picture',  'label' => '用户头像',
									'loadAttribute'=>['user_infos.avatar','imageUrl'=>'user_infos.avatarUrl'],
									'uploadUrl'=> route('api.admin.system.upload.image'), 'width'=>'250px', 'height'=>'250px']
								)
								->item(['name' => 'integral',  'type' => 'number',   'label' => '用户积分',
									'loadAttribute'=>['user_infos.integral']   ]
								)
								->item(['name' => 'money',     'type' => 'number',   'label' => '用户余额',
									'loadAttribute'=>['user_infos.money']  ]
								)
								->itemData($users->toArray())
								->rules($this->rules->editUser())
								->apiUrl('submit',route('api.admin.user.user.update'))
								->config('labelWidth','100px');
				return $this->container->make('builderHtml')
									->title('编辑用户')
									->item($form)
									->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
									->response();
    }
    public function update(Request $request)
    {
        $input = $request->all();
        if(!$input['password']){
            unset($input['password']);
        }
        $user = $this->userModel->find($input['id']);
        $user->fill($input)->save();
        $user->roles()->sync($request->roles);//更新用户角色
        $user->userInfos()->update(['avatar'=>$request->avatar, 'integral'=>$request->integral, 'money'=>$request->money]);
        $message = [
                        'message'   => '编辑用户数据成功！!',
                        'type'      => 'success',
                    ];
				return $this->container->make('builderHtml')->message($message)->response();
    }
}
