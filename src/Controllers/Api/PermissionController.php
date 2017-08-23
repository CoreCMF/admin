<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;

use CoreCMF\core\Models\Permission;
use CoreCMF\admin\Models\Config;
use CoreCMF\admin\Validator\PermissionRules;
class PermissionController extends Controller
{
  	/** @var permissionPepo */
    private $userModel;
    private $permissionModel;
    private $configModel;
    private $container;
    private $rules;
    private $builderModel;

    public function __construct(
      Request $request,
      Permission $permissionPepo,
      Config $configRepo,
      Container $container,
      PermissionRules $rules
    )
    {
        $this->permissionModel = $permissionPepo;
        $this->configModel = $configRepo;
        $this->container = $container;
        $this->rules = $rules;
        $this->builderModel = $this->container->make('builderModel')->request($request);
    }
    public function index()
    {
        $pageSizes = $this->configModel->getPageSizes();
        $data = $this->builderModel->group('admin')
                                  ->parent('name', 'parent')
                                  ->getData($this->permissionModel);
        $table = $this->container->make('builderTable')
                                  ->tabs($this->configModel->tabsGroupList('ENTRUST_GROUP_LIST'))
                                  ->defaultTabs('admin')
                                  ->data($data['model'])
                                  ->column(['prop' => 'id',                'label'=> 'ID',     'width'=> '55'])
                                  ->column(['prop' => 'name',              'label'=> '权限标识',   'width'=> '350'])
                                  ->column(['prop' => 'display_name',      'label'=> '权限名称',   'minWidth'=> '100'])
                                  ->column(['prop' => 'description',       'label'=> '权限描述', 'minWidth'=> '250'])
                                  ->column(['prop' => 'rightButton',       'label'=> '操作',   'minWidth'=> '120',  'type' => 'btn'])
                                  ->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.user.permission.add'),'title'=>'新增权限','icon'=>'fa fa-plus'])                         // 添加新增按钮
                                  ->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.user.permission.delete')])                         // 添加删除按钮
                                  ->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.user.permission.edit')])                         // 添加编辑按钮
                                  ->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.user.permission.delete')])                       // 添加删除按钮
                                  ->searchTitle('请输入搜索内容')
                                  ->searchSelect(['id'=>'ID','name'=>'权限标识','email'=>'权限名称','mobile'=>'权限描述'])
                                  ;
        return $this->container->make('builderHtml')
                                ->title('权限管理')
                                ->item($table)
                                ->response();
    }
    public function delete(){
        if ($this->builderModel->delete($this->permissionModel)) {
            $message = [
                        'message'   => '权限数据删除成功!',
                        'type'      => 'success',
                    ];
        }
        return $this->container->make('builderHtml')->message($message)->response();
    }
    public function add(){
        $groupList = $this->configModel->tabsGroupList('ENTRUST_GROUP_LIST');
        $form = $this->container->make('builderForm')
                ->item(['name' => 'group',     			'type' => 'select',   'label' => '配置分组',
                        'placeholder' => '配置所属的分组','options'=>$groupList,	'value'=>'admin'])
                ->item(['name' => 'name',           'type' => 'text',     'label' => '权限标识' ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '权限名称'   ])
                ->item(['name' => 'description',    'type' => 'text',     'label' => '权限描述'   ])
                // ->item(['name' => 'roles',     'type' => 'checkbox', 'label' => '用户角色',  'value'=>['2'], 'options'=>$roles])
                ->rules($this->rules->addPermission())
                ->apiUrl('submit',route('api.admin.user.permission.store'))
                ->config('labelWidth','100px');
        return $this->container->make('builderHtml')
                  ->title('新增权限')
                  ->item($form)
                  ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
    }
    public function store(Request $request)
    {
        if ($this->builderModel->save($this->permissionModel)) {
            $message = [
                      'message'   => '新增权限成功！!',
                      'type'      => 'success',
                    ];
        }
        return $this->container->make('builderHtml')->message($message)->response();
    }
    public function edit(Request $request){
        $permission = $this->permissionModel->find($request->id);
        return $data = BuilderData::addFormApiUrl('submit',route('api.admin.system.permission.update'))               //添加Submit通信API
                            ->setFormTitle('新增角色')                                                   //添form表单页面标题
                            ->setFormConfig(['width'=>'90px'])
                            ->addFormItem(['name' => 'id',        'type' => 'hidden',   'label' => 'ID'     ])
                            ->addFormItem(['name' => 'name',      'type' => 'text',     'label' => '角色标识'     ])
                            ->addFormItem(['name' => 'display_name','type' => 'text',     'label' => '角色名称'   ])
                            ->addFormItem(['name' => 'description','type' => 'textarea','label' => '角色描述'   ])
                            ->setFormObject($permission)
                            ->setFormRules($this->permissionModel->getRules())
                            ->get();
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $permission = $this->permissionModel->find($request->id)->fill($input)->save();
        $data = [
                        'title'     => '用户编辑成功！',
                        'message'   => '编辑用户数据成功！!',
                        'type'      => 'success',
                    ];
        return response()->json($data, 200);
    }
}
