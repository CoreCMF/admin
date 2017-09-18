<?php

namespace CoreCMF\Admin\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;

use CoreCMF\Admin\App\Models\Menu;
use CoreCMF\Admin\App\Models\Config;

class MenuController extends Controller
{
    /** @var MenuRepository */
    private $menuModel;
    private $configModel;
    private $container;

    public function __construct(Container $container, Menu $menuRepo, Config $configRepo)
    {
        $this->menuModel = $menuRepo;
        $this->configModel = $configRepo;
        $this->container = $container;
    }
    /**
     * [index 表格列表显示]
     * @author BigRocs
     * @email    bigrocs@qq.com
     * @DateTime 2017-05-01T14:45:36+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function index(Request $request)
    {
        $pageSizes = $this->configModel->getPageSizes();
        $data = $this->container->make('builderModel')
                            ->request($request)
                            ->group('admin')
                            ->pageSize($this->configModel->getPageSize())
                            ->getData($this->menuModel);
        $table = $this->container->make('builderTable')
                  ->tabs($this->configModel->tabsGroupList('MENU_GROUP_LIST'))
                  ->data($data['model'])
                  ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                  ->column(['prop' => 'icon',       'label'=> '图标',	  'width'=> '65',		'type' => 'icon'])
                  ->column(['prop' => 'title',      'label'=> '标题',   'minWidth'=> '160'])
                  ->column(['prop' => 'type',       'label'=> '类型',   'minWidth'=> '100'])
                  ->column(['prop' => 'value',      'label'=> '导航值', 'minWidth'=> '180'])
                  ->column(['prop' => 'api_route',    'label'=> 'API路由名','minWidth'=> '270'])
                  ->column(['prop' => 'sort',       'label'=> '排序',   'width'=> '70'])
                  ->column(['prop' => 'status',     'label'=> '状态',   'minWidth'=> '90',	'type' => 'status'])
                  ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220',	'type' => 'btn'])
                  ->topButton(['buttonType'=>'add',       'apiUrl'=> route('api.admin.system.menu.add'),'title'=>'添加导航'])                         // 添加新增按钮
                  ->topButton(['buttonType'=>'resume',    'apiUrl'=> route('api.admin.system.menu.status')])                         // 添加启用按钮
                  ->topButton(['buttonType'=>'forbid',    'apiUrl'=> route('api.admin.system.menu.status')])                         // 添加禁用按钮
                  ->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.admin.system.menu.delete')])                         // 添加删除按钮
                  ->rightButton(['buttonType'=>'edit',    'apiUrl'=> route('api.admin.system.menu.edit')])                         // 添加编辑按钮
                  ->rightButton(['buttonType'=>'forbid',  'apiUrl'=> route('api.admin.system.menu.status')])                       // 添加禁用/启用按钮
                  ->rightButton(['buttonType'=>'delete',  'apiUrl'=> route('api.admin.system.menu.delete')])                       // 添加删除按钮
                  ->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])
                  ->searchTitle('请输入搜索内容')
                  ->searchSelect(['id'=>'ID','title'=>'标题','api_route'=>'API路由名'])
                  ;
        $html = $this->container->make('builderHtml')
                  ->title('配置管理')
                  ->item($table)
                  ->response();
        return $html;
    }
}
