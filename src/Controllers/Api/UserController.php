<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\core\Models\User;
use CoreCMF\core\Models\Role;
use CoreCMF\admin\Models\Config;

class UserController extends Controller
{
	/** @var userRepo */
    private $userModel;
    private $roleModel;
    private $configModel;
		private $container;

    public function __construct(
			User $userRepo,
			Role $rolePepo,
			Config $configRepo,
			Container $container
		)
    {
        $this->userModel = $userRepo;
        $this->roleModel = $rolePepo;
        $this->configModel = $configRepo;
				$this->container = $container;
    }
    public function index(Request $request)
    {
        $group        = $request->get('tabIndex',0);
        $pageSize     = $request->get('pageSize',$this->configModel->getPageSize());
        $pageSizes    = $this->configModel->getPageSizes();
        $page         = $request->get('page',1);
        $selectSearch = $request->get('selectSearch','id');
        $inputSearch  = '%'.$request->get('inputSearch').'%';

        // [$total 获取数据总数]
        $total = $this->userModel
                        ->where('status', '>=', 0)
                        ->where($selectSearch, 'like', $inputSearch)
                        ->count();
        //[$userModel 获取数据对象]
        $users = $this->userModel
                        ->skip(($page-1)*$pageSize)
                        ->take($pageSize)
                        ->orderBy('id', 'ASC')
                        ->where('status', '>=', 0)
                        ->where($selectSearch, 'like', $inputSearch)
                        ->get();
        $users->load('roles');//加载关联权限数据
        $users->load('userInfos');//加载关联头像数据

				$rolesConfig = ['type'=>'primary',    'keyNmae'=>'display_name'];   // rolesTags  tags显示配置      valueName显示数据对象名称 如果不填写默认显示整个对象
        $pictureConfig = ['keyNmae'=>'avatarUrl', 'width'=>50, 'height'=>50, 'class'=>'img-responsive img-circle', 'alt'=>'用户头像'];
				$table = $this->container->make('builderTable')
																	->data($users)
																	->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
																	->column(['prop' => 'user_infos', 'label'=> '头像',   'width'=> '90',    'type' => 'picture',    'config'=>$pictureConfig])
																	->column(['prop' => 'roles',      'label'=> '角色',   'minWidth'=> '120',    'type' => 'tags',       'config'=>$rolesConfig])
																	->column(['prop' => 'name',       'label'=> '用户名', 'minWidth'=> '120'])
																	->column(['prop' => 'email',      'label'=> '邮箱',   'minWidth'=> '180'])
																	->column(['prop' => 'mobile',     'label'=> '手机',   'minWidth'=> '180'])
																	->column(['prop' => 'status',     'label'=> '状态',   'width'=> '90',      'type' => 'status'])
																	->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220',  'type' => 'btn'])
																	->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.system.user.add'),'title'=>'新增用户','icon'=>'fa fa-plus'])                         // 添加新增按钮
																	->topButton(['buttonType'=>'resume',     'apiUrl'=> route('api.admin.system.user.status')])                         // 添加启用按钮
																	->topButton(['buttonType'=>'forbid',     'apiUrl'=> route('api.admin.system.user.status')])                         // 添加禁用按钮
																	->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.system.user.delete')])                         // 添加删除按钮
																	->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.system.user.edit')])                         // 添加编辑按钮
																	->rightButton(['buttonType'=>'forbid',   'apiUrl'=> route('api.admin.system.user.status')])                       // 添加禁用/启用按钮
																	->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.system.user.delete')])                       // 添加删除按钮
																	->pagination(['total'=>$total,'pageSize'=>$pageSize,'pageSizes'=>$pageSizes])//分页设置
																	->searchTitle('请输入搜索内容')
																	->searchSelect(['id'=>'ID','name'=>'用户名','email'=>'邮箱','mobile'=>'手机'])
																	;
				return $this->container->make('builderHtml')
								          			->title('配置管理')
								          			->item($table)
								          			->response();
    }
    public function status(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $this->userModel->where('id', '=', $id)->update(['status' => $value]);
        }
        $data = [
										'message'   => '用户状态更改成功!',
                    'type'      => 'success',
									];
				return $this->container->make('builderHtml')
														   ->message($data)
														   ->response();
    }
    public function delete(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $response = $this->userModel->find($id)->forceDelete();
        }
        $data = [
                    'message'   => '后台用户删除成功!',
                    'type'      => 'success',
                ];
				return $this->container->make('builderHtml')
														   ->message($data)
														   ->response();
    }
    public function add(){
        $roles = $this->roleModel->all();
        $roles->transform(function ($item, $key) {
            $item['name'] = $item['display_name'];
            return $item;
        });
        return $data = BuilderData::addFormApiUrl('submit',route('api.admin.system.user.store'))               //添加Submit通信API
                            ->setFormTitle('新增用户')                                           //添form表单页面标题
                            ->setFormConfig(['width'=>'90px'])
                            ->addFormItem(['name' => 'name',      'type' => 'text',     'label' => '用户名'     ])
                            ->addFormItem(['name' => 'email',     'type' => 'text',     'label' => '用户邮箱'   ])
                            ->addFormItem(['name' => 'mobile',    'type' => 'text',     'label' => '用户手机'   ])
                            ->addFormItem(['name' => 'password',  'type' => 'password', 'label' => '用户密码'   ])
                            ->addFormItem(['name' => 'checkPassword','type' => 'password','label' => '密码验证'])
                            ->addFormItem(['name' => 'roles',     'type' => 'checkbox', 'label' => '用户角色',  'value'=>[2], 'options'=>$roles])
                            ->addFormItem(['name' => 'avatar',    'type' => 'picture',  'label' => '用户头像',  'uploadUrl'=>'/api/admin/system/upload/image', 'width'=>'250px', 'height'=>'250px'])
                            ->addFormItem(['name' => 'integral',  'type' => 'number',   'label' => '用户积分'   ])
                            ->addFormItem(['name' => 'money',     'type' => 'number',   'label' => '用户余额'  ])
                            ->setFormRules($this->userModel->getRules())
                            ->get();
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
        $roles = $this->roleModel->all();
        $roles->transform(function ($item, $key) {
            $item['name'] = $item['display_name'];
            return $item;
        });
        return $data = BuilderData::addFormApiUrl('submit',route('api.admin.system.user.update'))               //添加Submit通信API
                            ->setFormTitle('编辑用户')                                           //添form表单页面标题
                            ->setFormConfig(['width'=>'90px'])
                            ->addFormItem(['name' => 'id',        'type' => 'hidden',   'label' => 'ID'     ])
                            ->addFormItem(['name' => 'name',      'type' => 'text',     'label' => '用户名'     ])
                            ->addFormItem(['name' => 'email',     'type' => 'text',     'label' => '用户邮箱'   ])
                            ->addFormItem(['name' => 'mobile',    'type' => 'text',     'label' => '用户手机'   ])
                            ->addFormItem(['name' => 'password',  'type' => 'password',  'label' => '用户密码' ])
                            ->addFormItem(['name' => 'checkPassword','type' => 'password','label' => '密码验证'])
                            ->addFormItem(['name' => 'roles',     'type' => 'checkbox', 'label' => '用户角色', 'options'=>$roles])
                            ->addFormItem(['name' => 'avatar',    'type' => 'picture',  'label' => '用户头像', 'loadAttribute'=>['user_infos.avatar','imageUrl'=>'user_infos.avatarUrl'], 'uploadUrl'=>'/api/admin/system/upload/image', 'width'=>'250px', 'height'=>'250px'])
                            ->addFormItem(['name' => 'integral',  'type' => 'number',   'label' => '用户积分', 'loadAttribute'=>['user_infos.integral']])
                            ->addFormItem(['name' => 'money',     'type' => 'number',   'label' => '用户余额', 'loadAttribute'=>['user_infos.money']])
                            ->setFormObject($users)
                            ->setFormRules($this->userModel->getRules())
                            ->get();
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
        $data = [
                        'title'     => '用户编辑成功！',
                        'message'   => '编辑用户数据成功！!',
                        'type'      => 'success',
                    ];
				return $this->container->make('builderHtml')
														   ->message($data)
														   ->response();
    }
}
