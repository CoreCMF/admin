<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;

use CoreCMF\core\Models\Permission;
use CoreCMF\admin\Models\Config;
use CoreCMF\admin\Validator\UserRules;
class PermissionController extends Controller
{
  	/** @var permissionPepo */
    private $userModel;
    private $permissionModel;
    private $configModel;
    private $container;
    private $rules;

    public function __construct(
      Permission $permissionPepo,
      Config $configRepo,
      Container $container,
      UserRules $rules
    )
    {
        $this->permissionModel = $permissionPepo;
        $this->configModel = $configRepo;
        $this->container = $container;
        $this->rules = $rules;
    }
    public function index(Request $request)
    {
        $pageSizes = $this->configModel->getPageSizes();
        $data = $this->container->make('builderModel')
                            ->request($request)
                            ->total()
                            ->search()
                            ->group('admin')
                            ->page($this->configModel->getPageSize())
                            ->getData($this->permissionModel);
        $table = $this->container->make('builderTable')
                                  ->tabs($this->configModel->tabsGroupList('ENTRUST_GROUP_LIST'))
                                  ->data($data['model'])
                                  ->column(['prop' => 'id',                'label'=> 'ID',     'width'=> '55'])
                                  ->column(['prop' => 'name',              'label'=> '权限标识',   'width'=> '250'])
                                  ->column(['prop' => 'display_name',      'label'=> '权限名称',   'minWidth'=> '120'])
                                  ->column(['prop' => 'description',       'label'=> '权限描述', 'minWidth'=> '250'])
                                  ->column(['prop' => 'rightButton',       'label'=> '操作',   'minWidth'=> '120',  'type' => 'btn'])
                                  ->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.user.permission.add'),'title'=>'新增权限','icon'=>'fa fa-plus'])                         // 添加新增按钮
                                  ->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.user.permission.delete')])                         // 添加删除按钮
                                  ->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.user.permission.edit')])                         // 添加编辑按钮
                                  ->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.user.permission.delete')])                       // 添加删除按钮
                                  ->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])
                                  ->searchTitle('请输入搜索内容')
                                  ->searchSelect(['id'=>'ID','name'=>'权限标识','email'=>'权限名称','mobile'=>'权限描述'])
                                  ;
        return $this->container->make('builderHtml')
                                ->title('权限管理')
                                ->item($table)
                                ->response();
    }
    public function add(){
        return $data = BuilderData::addFormApiUrl('submit',route('api.admin.system.permission.store'))               //添加Submit通信API
                            ->setFormTitle('新增权限')                                                   //添form表单页面标题
                            ->setFormConfig(['width'=>'90px'])
                            ->addFormItem(['name' => 'name',      'type' => 'text',     'label' => '权限标识'     ])
                            ->addFormItem(['name' => 'display_name','type' => 'text',     'label' => '权限名称'   ])
                            ->addFormItem(['name' => 'description','type' => 'textarea','label' => '权限描述'   ])
                            ->setFormRules($this->permissionModel->getRules())
                            ->get();
    }
    public function store(Request $request)
    {
        $permissionModel = new Permission();
        $permissionModel->name = $request->name;
        $permissionModel->display_name = $request->display_name;
        $permissionModel->description = $request->description;
        $permissionModel->save();
        $data = [
                        'title'     => '新增角色成功！',
                        'message'   => '新增角色数据成功！!',
                        'type'      => 'success',
                ];

        return response()->json($data, 200);
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
