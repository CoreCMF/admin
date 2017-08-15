<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use CoreCMF\admin\Models\Config;
use CoreCMF\admin\Validator\ConfigRules;

class ConfigController extends Controller
{
    /** @var configRepository */
    private $configModel;
    private $rules;
    private $container;

    public function __construct(
      Config $configRepo,
      ConfigRules $rules,
      Container $container
    )
    {
        $this->configModel = $configRepo;
        $this->rules = $rules;
        $this->container = $container;
    }
    public function index(Request $request)
    {
        $pageSizes = $this->configModel->getPageSizes();
        $data = $this->container->make('builderModel')
                            ->request($request)
                            ->total()
                            ->search()
                            ->group(0)
                            ->page($this->configModel->getPageSize())
                            ->getData($this->configModel);
        $table = $this->container->make('builderTable')
                  ->tabs($this->configModel->tabsConfigGroupList())
                  ->tabsGroup('group')
                  ->data($data['model'])
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
                  ->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])
                  ->searchTitle('请输入搜索内容')
                  ->searchSelect(['id'=>'ID','name'=>'名称','title'=>'标题'])
                  ;
        $html = $this->container->make('builderHtml')
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
        $message = [
                    'message'   => '后台配置数据状态更改成功!',
                    'type'      => 'success',
                ];
        return response()->json(['message' => $message], 200);
    }
    public function delete(Request $request){
        $input = $request->all();
        foreach ($input as $id => $value) {
            $response = $this->configModel->find($id)->forceDelete();
        }
        $message = [
                    'message'   => '后台配置数据删除成功!',
                    'type'      => 'success',
                ];
        return response()->json(['message' => $message], 200);
    }
    public function add(){
        $configGroupList = $this->configModel->tabsConfigGroupList();
        $itemType = $this->container->make('builderForm')->itemType;
        $form = $this->container->make('builderForm')
                ->item(['name' => 'group',     'type' => 'select',   'label' => '配置分组',     'placeholder' => '配置所属的分组',                          'options'=>$configGroupList,    'value'=>0])
                ->item(['name' => 'type',      'type' => 'select',   'label' => '配置类型',     'placeholder' => '配置类型的分组',                          'options'=>$itemType,       'value'=>'text'])
                ->item(['name' => 'name',      'type' => 'text',     'label' => '配置名称',     'placeholder' => '配置名称'])
                ->item(['name' => 'title',     'type' => 'text',     'label' => '配置标题',     'placeholder' => '配置标题'])
                ->item(['name' => 'value',     'type' => 'textarea', 'label' => '配置值',       'placeholder' => '配置值',                                   'rows'=>4])
                ->item(['name' => 'options',   'type' => 'textarea', 'label' => '配置项',       'placeholder' => '如果是单选、多选、下拉等类型 需要配置该项',   'rows'=>4])
                ->item(['name' => 'tip',       'type' => 'textarea', 'label' => '配置说明',     'placeholder' => '配置说明',                                  'rows'=>4])
                ->item(['name' => 'sort',      'type' => 'number',   'label' => '排序',         'placeholder' => '用于显示的顺序'                             ,'value'=>0])
                ->rules($this->rules->index())
                ->apiUrl('submit',route('api.admin.system.config.store'))
                ->config('labelWidth','100px');
        return $this->container->make('builderHtml')
                  ->title('新增配置')
                  ->item($form)
                  ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
    }
    /**
     * [store 新增配置数据].
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $response = $this->configModel->create($input);
        if ($response->wasRecentlyCreated) {
            $message = [
                        'message'   => '新增配置数据成功！!',
                        'type'      => 'success',
                    ];
        }else{
            $message = [
                        'message'   => '新增配置数据失败！!',
                        'type'      => 'error',
                    ];
        }
        return response()->json(['message'=>$message], 200);
    }
    public function edit(Request $request){
        $config = $this->configModel->find($request->id);
        $configGroupList = $this->configModel->tabsConfigGroupList();
        $itemType = $this->container->make('builderForm')->itemType;
        $form = $this->container->make('builderForm')
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
                ->rules($this->rules->index())
                ->apiUrl('submit',route('api.admin.system.config.update'))
                ->config('labelWidth','100px');
        $html = $this->container->make('builderHtml')
                  ->title('编辑配置')
                  ->item($form)
                  ->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
                  ->response();
        return $html;
    }
    public function update(Request $request)
    {
        $input = $request->all();
        $response = $this->configModel->find($input['id'])->fill($input)->save();
        if ($response) {
            $message = [
                        'message'   => '编辑配置数据成功！!',
                        'type'      => 'success',
                    ];
        }else{
            $message = [
                        'message'   => '编辑配置数据失败！!',
                        'type'      => 'error',
                    ];
        }
        return response()->json(['message'=>$message], 200);
    }

}
