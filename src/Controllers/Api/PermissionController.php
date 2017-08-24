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
                                  ->parent('name', 'parent', 'display_name')
                                  ->getData($this->permissionModel);
        $table = $this->container->make('builderTable')
                                  ->tabs($this->configModel->tabsGroupList('ENTRUST_GROUP_LIST'))
                                  ->defaultTabs('admin')
                                  ->data($data['model'])
                                  ->column(['prop' => 'id',                'label'=> 'ID',     'width'=> '55'])
                                  ->column(['prop' => 'display_name',      'label'=> '权限名称',   'minWidth'=> '150'])
                                  ->column(['prop' => 'name',              'label'=> '路由名称',   'width'=> '250'])
                                  ->column(['prop' => 'description',       'label'=> '权限描述', 'minWidth'=> '250'])
                                  ->column(['prop' => 'rightButton',       'label'=> '操作',   'minWidth'=> '150',  'type' => 'btn'])
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
        return $this->container->make('builderHtml')
                  ->title('新增权限')
                  ->item($this->formItem(true))
                  ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
    }
    public function store()
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
      return $this->container->make('builderHtml')
                ->title('编辑权限')
                ->item($this->formItem(true,$permission))
                ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                ->response();
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
    public function formItem($current = false, $modelData = null){
        $groupList = $this->configModel->tabsGroupList('ENTRUST_GROUP_LIST');
        $data = $this->builderModel->group('admin')->parent('name', 'parent', 'display_name')->getData($this->permissionModel);
        $parent = $this->builderModel->toSelectData(
            $data['model'],
            'name',
            'display_name'
        );
        $form = $this->container->make('builderForm')
                ->item(['name' => 'group',     			'type' => 'select',   'label' => '权限分组',
                        'placeholder' => '权限所属的分组','options'=>$groupList,	'value'=>'admin', 'apiUrl'=>route('api.admin.user.permission.form-item')])
                ->item(['name' => 'parent',     		'type' => 'select',   'label' => '上级权限',
                                'placeholder' => '顶级权限','options'=>$parent])
                ->item(['name' => 'name',           'type' => 'text',     'label' => '权限标识'   ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '权限名称'   ])
                ->item(['name' => 'description',    'type' => 'text',     'label' => '权限描述'   ])
                ->rules($this->rules->addPermission())
                ->apiUrl('submit',route('api.admin.user.permission.store'))
                ->config('labelWidth','100px');
        if ($modelData) {
            $form->itemData($modelData->toArray());
        }
        return $current? $form: $form->response();
    }
}
