<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;
use CoreCMF\core\Models\Role;
use CoreCMF\admin\Models\Config;
use CoreCMF\admin\Validator\RoleRules;

class RoleController extends Controller
{
	/** @var rolePepo */
  /** @var userRepo */
    private $roleModel;
    private $configModel;
		private $container;
		private $rules;

    public function __construct(
			Role $rolePepo,
			Config $configRepo,
			Container $container,
			RoleRules $rules
		)
    {
        $this->roleModel = $rolePepo;
        $this->configModel = $configRepo;
				$this->container = $container;
				$this->rules = $rules;
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
        $total = $this->roleModel
                        ->where($selectSearch, 'like', $inputSearch)
                        ->count();
        //[$roleModel 获取数据对象]
        $roles = $this->roleModel
                        ->skip(($page-1)*$pageSize)
                        ->take($pageSize)
                        ->orderBy('id', 'ASC')
                        ->where($selectSearch, 'like', $inputSearch)
                        ->get();
        $table = $this->container->make('builderTable')
        													->data($roles)
                                  ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                                  ->column(['prop' => 'name',       'label'=> '角色标识', 'minWidth'=> '120'])
                                  ->column(['prop' => 'display_name','label'=> '角色名称','minWidth'=> '180'])
                                  ->column(['prop' => 'description','label'=> '角色描述','minWidth'=> '280'])
                                  ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220',  'type' => 'btn'])
        													->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.system.role.add'),'title'=>'新增角色'])                         // 添加新增按钮
        													->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.system.role.delete')])                         // 添加删除按钮
        													->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.system.role.edit')])                         // 添加编辑按钮
                                  ->rightButton(['title'=>'权限管理',       'apiUrl'=> route('api.admin.system.role.permission'),'type'=>'warning', 'icon'=>'fa fa-unlock'])                         // 添加权限管理按钮
                                  ->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.system.role.delete')])                       // 添加删除按钮
        													->pagination(['total'=>$total,'pageSize'=>$pageSize,'pageSizes'=>$pageSizes])//分页设置
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
        $form = $this->container->make('builderForm')
                ->item(['name' => 'name',           'type' => 'text',     'label' => '角色标识'   ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '角色名称'   ])
                ->item(['name' => 'description',    'type' => 'textarea', 'label' => '角色描述'   ])
                ->rules($this->rules->addRole())
                ->apiUrl('submit',route('api.admin.system.role.store'));
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
        $this->roleModel->save();
        $message = [
                        'message'   => '新增角色数据成功！!',
                        'type'      => 'success',
                ];
        return $this->container->make('builderHtml')->message($message)->response();
    }
    public function edit(Request $request){
        $roles = $this->roleModel->find($request->id);
        $form = $this->container->make('builderForm')
                ->item(['name' => 'id',             'type' => 'text',     'label' => 'ID',  'disabled'=>true     ])
                ->item(['name' => 'name',           'type' => 'text',     'label' => '角色标识'   ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '角色名称'   ])
                ->item(['name' => 'description',    'type' => 'textarea', 'label' => '角色描述'   ])
                ->itemData($roles->toArray())
                // ->rules($this->rules->addRole())
                ->apiUrl('submit',route('api.admin.system.role.update'));
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
    public function permission(Request $request){
        $roles = $this->roleModel->find($request->id);
        return $data = BuilderData::addFormApiUrl('submit',route('api.admin.system.role.permission-update'))               //添加Submit通信API
                            ->setFormTitle('新增角色')                                                   //添form表单页面标题
                            ->setFormConfig(['width'=>'90px'])
                            ->addFormItem(['name' => 'id',        'type' => 'hidden',   'label' => 'ID'     ])
                            ->addFormItem(['name' => 'name',      'type' => 'text',     'label' => '角色标识'     ])
                            ->addFormItem(['name' => 'display_name','type' => 'text',     'label' => '角色名称'   ])
                            ->addFormItem(['name' => 'description','type' => 'textarea','label' => '角色描述'   ])
                            ->setFormObject($roles)
                            ->setFormRules($this->roleModel->getRules())
                            ->get();
    }
    public function permissionUpdate(Request $request)
    {
        $input = $request->all();
        $role = $this->roleModel->find($request->id)->fill($input)->save();
        $message = [
                        'message'   => '编辑角色权限成功！!',
                        'type'      => 'success',
                    ];
        return $this->container->make('builderHtml')->message($message)->response();
		}
}
