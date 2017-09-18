<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;
use CoreCMF\Core\Models\Role;
use CoreCMF\Admin\Http\Models\Config;
use CoreCMF\Core\Models\Permission;
use CoreCMF\Admin\Validator\RoleRules;

class RoleController extends Controller
{
	/** @var rolePepo */
  /** @var userRepo */
    private $roleModel;
		private $permissionModel;
    private $configModel;
		private $container;
		private $rules;

    public function __construct(
			Request $request,
			Role $rolePepo,
			Permission $permissionPepo,
			Config $configRepo,
			Container $container,
			RoleRules $rules
		)
    {
        $this->roleModel = $rolePepo;
				$this->permissionModel = $permissionPepo;
        $this->configModel = $configRepo;
				$this->container = $container;
				$this->rules = $rules;
				$this->builderModel = $this->container->make('builderModel')->request($request);
    }
    public function index(Request $request)
    {
				$pageSizes = $this->configModel->getPageSizes();
				$data = $this->builderModel->group('global')
                            ->pageSize($this->configModel->getPageSize())
                            ->getData($this->roleModel);
				$table = $this->container->make('builderTable')
																	->tabs($this->configModel->tabsGroupList('ENTRUST_GROUP_LIST'))
        													->data($data['model'])
                                  ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                                  ->column(['prop' => 'name',       'label'=> '角色标识', 'minWidth'=> '120'])
                                  ->column(['prop' => 'display_name','label'=> '角色名称','minWidth'=> '180'])
                                  ->column(['prop' => 'description','label'=> '角色描述','minWidth'=> '280'])
                                  ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220',  'type' => 'btn'])
        													->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.user.role.add'),'title'=>'新增角色'])                         // 添加新增按钮
        													->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.user.role.delete')])                         // 添加删除按钮
        													->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.user.role.edit')])                         // 添加编辑按钮
                                  ->rightButton(['title'=>'权限管理',       'apiUrl'=> route('api.admin.user.role.permission'),'type'=>'warning', 'icon'=>'fa fa-unlock'])                         // 添加权限管理按钮
                                  ->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.user.role.delete')])                       // 添加删除按钮
        													->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])//分页设置
        													->searchTitle('请输入搜索内容')
        													->searchSelect(['id'=>'ID','name'=>'角色标识','display_name'=>'角色名称','description'=>'角色描述'])
        													;
        return $this->container->make('builderHtml')
        				          			->title('角色管理')
        				          			->item($table)
        				          			->response();
    }
    public function delete(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $response = $this->roleModel->find($id)->forceDelete();
        }
        $message = [
                    'message'   => '角色数据删除成功!',
                    'type'      => 'success',
                ];
        return $this->container->make('builderHtml')
                               ->message($message)
                               ->response();
    }
    public function add(){
				$groupList = $this->configModel->tabsGroupList('ENTRUST_GROUP_LIST');
        $form = $this->container->make('builderForm')
								->item(['name' => 'group',     			'type' => 'select',   'label' => '配置分组',
												'placeholder' => '配置所属的分组','options'=>$groupList,	'value'=>'admin'])
                ->item(['name' => 'name',           'type' => 'text',     'label' => '角色标识'   ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '角色名称'   ])
                ->item(['name' => 'description',    'type' => 'textarea', 'label' => '角色描述'   ])
                ->rules($this->rules->addRole())
                ->apiUrl('submit',route('api.admin.user.role.store'));
        return $this->container->make('builderHtml')
                  ->title('新增角色')
                  ->item($form)
                  ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
    }
    public function store(Request $request)
    {
        $this->roleModel->name = $request->name;
        $this->roleModel->display_name = $request->display_name;
        $this->roleModel->description = $request->description;
				$this->roleModel->group = $request->group;
        $this->roleModel->save();
        $message = [
                        'message'   => '新增角色数据成功！!',
                        'type'      => 'success',
                ];
        return $this->container->make('builderHtml')->message($message)->response();
    }
    public function edit(Request $request){
        $roles = $this->roleModel->find($request->id);
				$groupList = $this->configModel->tabsGroupList('ENTRUST_GROUP_LIST');
        $form = $this->container->make('builderForm')
				        ->item(['name' => 'id',             'type' => 'text',     'label' => 'ID',  'disabled'=>true     ])
								->item(['name' => 'group',     			'type' => 'select',   'label' => '配置分组',
												'placeholder' => '配置所属的分组','options'=>$groupList])
                ->item(['name' => 'name',           'type' => 'text',     'label' => '角色标识'   ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '角色名称'   ])
                ->item(['name' => 'description',    'type' => 'textarea', 'label' => '角色描述'   ])
                ->itemData($roles->toArray())
                ->rules($this->rules->addRole())
                ->apiUrl('submit',route('api.admin.user.role.update'));
        return $this->container->make('builderHtml')
                  ->title('新增角色')
                  ->item($form)
                  ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $role = $this->roleModel->find($request->id)->fill($input)->save();
        $message = [
                        'message'   => '编辑角色数据成功！!',
                        'type'      => 'success',
                    ];
        return $this->container->make('builderHtml')->message($message)->response();
    }
		/**
		 * 权限管理
		 */
    public function permission(Request $request){
				$permId = $this->roleModel->permissionId($request->id);//关联权限id
				$roles = $this->roleModel->find($request->id);
				$permission = $this->builderModel->group($roles->group)
                                  ->parent('name', 'parent', 'display_name')
                                  ->getDataTree($this->permissionModel);
				$form = $this->container->make('builderForm')
								->item(['name' => 'id',             'type' => 'text',     'label' => 'ID',  'disabled'=>true     ])
								->item(['name' => 'permission',     'type' => 'tree',     'label' => '权限', 'value'=>$permId, 'options'=>$permission,
							     			'nodeKey'=>'id', 'checkStrictly'=>true, 'props'=> ['children' => 'children','label' => 'display_name'],
												'defaultExpandAll'=>true, 'showCheckbox'=> true
											])
								->itemData($roles->toArray())
								->apiUrl('submit',route('api.admin.user.role.permission-update'));
				return $this->container->make('builderHtml')
									->title('权限管理')
									->item($form)
									->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
									->response();

    }
		/**
		 * 权限保存
		 */
    public function permissionUpdate(Request $request)
    {
				$role = $this->roleModel->find($request->id);
				if ($role->name == 'admin') {
						$message = [
														'message'   => '超级管理员角色不用配置权限!',
														'type'      => 'warning',
												];
				}else{
						$role->perms()->sync($request->permission);//更新用户角色
						$message = [
														'message'   => '编辑角色权限成功！!',
														'type'      => 'success',
												];
				}
        return $this->container->make('builderHtml')->message($message)->response();
		}
}
