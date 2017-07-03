<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Menu;
use CoreCMF\admin\Models\Config;

class MenuController extends Controller
{
    /** @var MenuRepository */
    private $menuModel;
    private $configModel;

    public function __construct(Menu $menuRepo, Config $configRepo)
    {
        $this->menuModel = $menuRepo;
        $this->configModel = $configRepo;
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
        $group        = $request->get('tabIndex','admin');
        $pageSize     = $request->get('pageSize',$this->configModel->getPageSize());
        $pageSizes    = $this->configModel->getPageSizes();
        $page         = $request->get('page',1);
        $selectSearch = $request->get('selectSearch','id');
        $inputSearch  = '%'.$request->get('inputSearch').'%';

        // [$total 获取数据总数]
        $total = $this->menuModel
                            ->where('group', '=', $group)
                            ->where($selectSearch, 'like', $inputSearch)
                            ->count();

        // [$menus 获取数据对象]
        $menus = $this->menuModel
                            ->skip(($page-1)*$pageSize)
                            ->take($pageSize)
                            ->where('group', '=', $group)
                            ->where('status', '>=', 0)
                            ->where($selectSearch, 'like', $inputSearch)
                            ->orderBy('sort', 'ASC')
                            ->get();
        $tabs = $this->configModel->getTabsMenuGroupList();

        $table = resolve('builderTable')
                  ->tabs($tabs)
                  ->tabsGroup('group')
                  ->data($menus)
                  ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                  ->column(['prop' => 'icon',       'label'=> '图标',	  'width'=> '65',		'type' => 'icon'])
                  ->column(['prop' => 'title',      'label'=> '标题',   'minWidth'=> '160'])
                  ->column(['prop' => 'type',       'label'=> '类型',   'minWidth'=> '100'])
                  ->column(['prop' => 'value',      'label'=> '菜单值', 'minWidth'=> '180'])
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
                  ->pagination(['total'=>$total, 'pageSize'=>$pageSize, 'pageSizes'=>$pageSizes])
                  ->searchTitle('请输入搜索内容')
                  ->searchSelect(['id'=>'ID','title'=>'标题','api_route'=>'API路由名'])
                  ;
        $html = resolve('builderHtml')
                  ->title('配置管理')
                  ->item($table)
                  ->response();
        return $html;
    }
}
