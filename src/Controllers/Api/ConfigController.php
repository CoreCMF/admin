<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use CoreCMF\core\Http\Request as CoreRequest;
use CoreCMF\admin\Models\Config;

class ConfigController extends Controller
{
    /** @var configRepository */
    private $configModel;

    public function __construct(Config $configRepo)
    {
        $this->configModel = $configRepo;
    }
    public function index(CoreRequest $request)
    {
        $group        = $request->get('tabIndex',0);
        $pageSize     = $request->get('pageSize',$this->configModel->getPageSize());
        $pageSizes    = $this->configModel->getPageSizes();
        $page         = $request->get('page',1);
        $selectSearch = $request->get('selectSearch','id');
        $inputSearch  = '%'.$request->get('inputSearch').'%';

        $total = $this->configModel
                            ->where('group', '=', $group)
                            ->where($selectSearch, 'like', $inputSearch)
                            ->count();
        $configs = $this->configModel
                            ->skip(($page-1)*$pageSize)
                            ->take($pageSize)
                            ->where('group', '=', $group)
                            ->where($selectSearch, 'like', $inputSearch)
                            ->orderBy('sort', 'ASC')
                            ->get();
        $tabs = $this->configModel->tabsConfigGroupList();

        $table = resolve('builderTable')
                  ->tabs($tabs)
                  ->tabsGroup('group')
                  ->data($configs)
                  ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                  ->column(['prop' => 'name',       'label'=> '名称',   'minWidth'=> '240'])
                  ->column(['prop' => 'title',      'label'=> '标题',   'minWidth'=> '180'])
                  ->column(['prop' => 'sort',       'label'=> '排序',   'width'=> '70'])
                  ->column(['prop' => 'status',     'label'=> '状态',   'minWidth'=> '90','type' => 'status'])
                  ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220','type' => 'btn'])
                  ->topButton(['buttonType'=>'add',       'apiUrl'=> route('api.admin.system.config.add'),'title'=>'添加配置'])                         // 添加新增按钮
                  ->topButton(['buttonType'=>'resume',    'apiUrl'=> route('api.admin.system.config.status')])                         // 添加启用按钮
                  ->topButton(['buttonType'=>'forbid',    'apiUrl'=> route('api.admin.system.config.status')])                         // 添加禁用按钮
                  ->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.admin.system.config.delete')])                         // 添加删除按钮
                  ->rightButton(['buttonType'=>'edit',    'apiUrl'=> route('api.admin.system.config.edit')])                         // 添加编辑按钮
                  ->rightButton(['buttonType'=>'forbid',  'apiUrl'=> route('api.admin.system.config.status')])                       // 添加禁用/启用按钮
                  ->rightButton(['buttonType'=>'delete',  'apiUrl'=> route('api.admin.system.config.delete')])                       // 添加删除按钮
                  ->pagination(['total'=>$total, 'pageSize'=>$pageSize, 'pageSizes'=>$pageSizes])
                  ->searchTitle('请输入搜索内容')
                  ->searchSelect(['id'=>'ID','name'=>'名称','title'=>'标题'])
                  ;
        $html = resolve('builderHtml')
                  ->title('配置管理')
                  ->item($table)
                  ->response();
        return $html;
    }
    public function status(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $config = $this->configModel->where('id', '=', $id)->update(['status' => $value]);
        }
        $data = [
                    'message'   => '后台配置数据状态更改成功!',
                    'type'      => 'success',
                ];
        return response()->json($data, 200);
    }
    public function delete(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $response = $this->configModel->find($id)->forceDelete();
        }
        $data = [
                    'message'   => '后台配置数据删除成功!',
                    'type'      => 'success',
                ];
        return response()->json($data, 200);
    }
    public function add(){
        $configGroupList = $this->configModel->tabsConfigGroupList();
        $itemType = resolve('builderForm')->itemType;
        $form = resolve('builderForm')
                ->item(['name' => 'group',     'type' => 'select',   'label' => '配置分组',     'placeholder' => '配置所属的分组',                          'options'=>$configGroupList,    'value'=>0])
                ->item(['name' => 'type',      'type' => 'select',   'label' => '配置类型',     'placeholder' => '配置类型的分组',                          'options'=>$itemType,       'value'=>'text'])
                ->item(['name' => 'name',      'type' => 'text',     'label' => '配置名称',     'placeholder' => '配置名称'])
                ->item(['name' => 'title',     'type' => 'text',     'label' => '配置标题',     'placeholder' => '配置标题'])
                ->item(['name' => 'value',     'type' => 'textarea', 'label' => '配置值',       'placeholder' => '配置值',                                   'rows'=>4])
                ->item(['name' => 'options',   'type' => 'textarea', 'label' => '配置项',       'placeholder' => '如果是单选、多选、下拉等类型 需要配置该项',   'rows'=>4])
                ->item(['name' => 'tip',       'type' => 'textarea', 'label' => '配置说明',     'placeholder' => '配置说明',                                  'rows'=>4])
                ->item(['name' => 'sort',      'type' => 'number',   'label' => '排序',         'placeholder' => '用于显示的顺序'                             ,'value'=>0])
                ->rules($this->configModel->getRules())
                ->apiUrl('submit',route('api.admin.system.config.store'))
                ->config('labelWidth','100px');
        $html = resolve('builderHtml')
                  ->title('新增配置')
                  ->item($form)
                  ->itemConfig('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
        return $html;
    }
    /**
     * [store 新增配置数据].
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $response = $this->configModel->create($input);
        if ($response->wasRecentlyCreated) {
            $data = [
                        'message'   => '新增配置数据成功！!',
                        'type'      => 'success',
                    ];
        }else{
            $data = [
                        'message'   => '新增配置数据失败！!',
                        'type'      => 'error',
                    ];
        }
        return response()->json($data, 200);
    }
    public function edit(Request $request){
        $config = $this->configModel->find($request->id);
        $configGroupList = $this->configModel->tabsConfigGroupList();
        $itemType = resolve('builderForm')->itemType;
        $form = resolve('builderForm')
                ->item(['name' => 'id',        'type' => 'text',     'label' => '数据ID',       'placeholder' => 'ID','disabled'=>true])
                ->item(['name' => 'group',     'type' => 'select',   'label' => '配置分组',     'placeholder' => '配置所属的分组',                          'options'=>$configGroupList])
                ->item(['name' => 'type',      'type' => 'select',   'label' => '配置类型',     'placeholder' => '配置类型的分组',                          'options'=>$itemType])
                ->item(['name' => 'name',      'type' => 'text',     'label' => '配置名称',     'placeholder' => '配置名称'])
                ->item(['name' => 'title',     'type' => 'text',     'label' => '配置标题',     'placeholder' => '配置标题'])
                ->item(['name' => 'value',     'type' => 'textarea', 'label' => '配置值',       'placeholder' => '配置值',                                   'rows'=>4])
                ->item(['name' => 'options',   'type' => 'textarea', 'label' => '配置项',       'placeholder' => '如果是单选、多选、下拉等类型 需要配置该项',      'rows'=>4])
                ->item(['name' => 'tip',       'type' => 'textarea', 'label' => '配置说明',     'placeholder' => '配置说明',                                  'rows'=>4])
                ->item(['name' => 'sort',      'type' => 'number',   'label' => '排序',         'placeholder' => '用于显示的顺序'])
                ->itemData($config)
                ->rules($this->configModel->getRules())
                ->apiUrl('submit',route('api.admin.system.config.update'))
                ->config('labelWidth','100px');
        $html = resolve('builderHtml')
                  ->title('编辑配置')
                  ->item($form)
                  ->itemConfig('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
        return $html;
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $response = $this->configModel->find($input['id'])->fill($input)->save();
        if ($response) {
            $data = [
                        'message'   => '编辑配置数据成功！!',
                        'type'      => 'success',
                    ];
        }else{
            $data = [
                        'message'   => '编辑配置数据失败！!',
                        'type'      => 'error',
                    ];
        }
        return response()->json($data, 200);
    }

}
