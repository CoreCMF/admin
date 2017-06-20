<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Config;

class ConfigController extends Controller
{
    /** @var configRepository */
    private $configModel;
    private $builderHtml;

    public function __construct(Config $configRepo)
    {
        $this->configModel = $configRepo;
    }
    public function index(Request $request)
    {
        $group = $request->tabIndex;
        $group = empty($group) ? 0 : $group;
        $configs = $this->configModel
                            ->where('group', '=', $group)
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
                ;
        $html = resolve('builderHtml')
                  ->title('配置管理')
                  ->item($table)
                  ->itemConfig('layout',['xs' => 24, 'sm' => 24, 'md' => 24, 'lg' => 24])
                  ->response();
        return $html;
    }
    public function update(Request $request){
        $input = $request->all();
        foreach ($input as $name => $value) {
            $config = $this->configModel->where('name', '=', $name)->update(['value' => $value]);
        }
        $data = [
                    'title'     => '保存成功',
                    'message'   => '系统设置保存成功!',
                    'type'      => 'success',
                ];
        return response()->json($data, 200);
    }
}
