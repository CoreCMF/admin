<?php

namespace CoreCMF\Admin\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoreCMF\Core\App\Models\Permission;
use CoreCMF\Admin\App\Models\Config;
use CoreCMF\Admin\App\Http\Validator\PermissionRules;

class PermissionController extends Controller
{
    /** @var permissionPepo */
    private $userModel;
    private $permissionModel;
    private $configModel;
    private $rules;
    private $builderModel;

    public function __construct(
      Request $request,
      Permission $permissionPepo,
      Config $configRepo,
      PermissionRules $rules
    ) {
        $this->permissionModel = $permissionPepo;
        $this->configModel = $configRepo;
        $this->rules = $rules;
        $this->builderModel = resolve('builderModel')->request($request);
    }
    public function index()
    {
        $pageSizes = $this->configModel->getPageSizes();
        $data = $this->builderModel->group('admin')
                                  ->parent('name', 'parent', 'display_name')
                                  ->getData($this->permissionModel);
        $table = resolve('builderTable')
                                  ->tabs($this->configModel->tabsGroupList('ENTRUST_GROUP_LIST'))
                                  ->defaultTabs('admin')
                                  ->data($data['model'])
                                  ->column(['prop' => 'id',                'label'=> 'ID',     'width'=> '55'])
                                  ->column(['prop' => 'display_name',      'label'=> '权限名称',   'minWidth'=> '150'])
                                  ->column(['prop' => 'name',              'label'=> '路由名称',   'width'=> '250'])
                                  ->column(['prop' => 'description',       'label'=> '权限描述', 'minWidth'=> '250'])
                                  ->column(['prop' => 'rightButton',       'label'=> '操作',   'minWidth'=> '150',  'type' => 'btn'])
                                  ->topButton(['buttonType'=>'add',        'apiUrl'=> route('api.admin.user.permission.add'),'title'=>'新增权限','icon'=>'fa fa-plus'])                         // 添加新增按钮
                                  ->topButton(['buttonType'=>'delete',     'apiUrl'=> route('api.admin.user.permission.delete'), 'data'=>'delete'])                         // 添加删除按钮
                                  ->rightButton(['buttonType'=>'edit',     'apiUrl'=> route('api.admin.user.permission.edit')])                         // 添加编辑按钮
                                  ->rightButton(['buttonType'=>'delete',   'apiUrl'=> route('api.admin.user.permission.delete'), 'data'=>'delete'])                       // 添加删除按钮
                                  ->searchTitle('请输入搜索内容')
                                  ->searchSelect(['id'=>'ID','name'=>'权限标识','email'=>'权限名称','mobile'=>'权限描述'])
                                  ;
        return resolve('builderHtml')->title('权限管理')->item($table)->response();
    }
    public function delete()
    {
        if ($this->builderModel->delete($this->permissionModel)) {
            $message = [
                        'message'   => '权限数据删除成功!',
                        'type'      => 'success',
                    ];
        }
        return resolve('builderHtml')->message($message)->response();
    }
    public function add(Request $request)
    {
        $form = $this->formItem(route('api.admin.user.permission.add'))
                      ->apiUrl('submit', route('api.admin.user.permission.store'));
        $html = resolve('builderHtml')->title('新增权限')->item($form)->config('layout', ['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])->response();
        return $request->group? $form->response(): $html;
    }
    public function store()
    {
        if ($this->builderModel->save($this->permissionModel)) {
            $message = [
                      'message'   => '新增权限成功！!',
                      'type'      => 'success',
                    ];
        }
        return resolve('builderHtml')->message($message)->response();
    }
    public function edit(Request $request)
    {
        $form = $this->formItem(route('api.admin.user.permission.edit'))
                      ->prependItem(['name' => 'id',           'type' => 'text',     'label' => 'ID', 'disabled'=>true ])
                      ->apiUrl('submit', route('api.admin.user.permission.update'));
        if ($request->group) {
            return $form->response();
        } else {
            $permission = $this->permissionModel->find($request->id);
            $form->itemData($permission->toArray());
            return resolve('builderHtml')->title('编辑权限')->item($form)->config('layout', ['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])->response();
        }
    }
    public function update()
    {
        if ($this->builderModel->update($this->permissionModel)) {
            $message = [
                      'message'   => '权限编辑成功！!',
                      'type'      => 'success',
                    ];
        }
        return resolve('builderHtml')->message($message)->response();
    }
    public function formItem($groupApiUrl)
    {
        $groupList = $this->configModel->tabsGroupList('ENTRUST_GROUP_LIST');
        $data = $this->builderModel->group('admin')->parent('name', 'parent', 'display_name')->getData($this->permissionModel);
        $parent = $this->builderModel->toSelectData(
            $data['model'],
            'name',
            'display_name'
        );
        return resolve('builderForm')
                ->item(['name' => 'group',                 'type' => 'select',   'label' => '权限分组',
                        'placeholder' => '权限所属的分组','options'=>$groupList,    'value'=>'admin', 'apiUrl'=>$groupApiUrl])
                ->item(['name' => 'parent',             'type' => 'select',   'label' => '上级权限',
                                'placeholder' => '顶级权限','options'=>$parent])
                ->item(['name' => 'name',           'type' => 'text',     'label' => '权限标识'   ])
                ->item(['name' => 'display_name',   'type' => 'text',     'label' => '权限名称'   ])
                ->item(['name' => 'description',    'type' => 'text',     'label' => '权限描述'   ])
                ->rules($this->rules->permission())
                ->config('labelWidth', '100px');
    }
}
