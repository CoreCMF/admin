<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Admin\Models\Config;
use CoreCMF\Admin\Models\Addon;
use CoreCMF\Admin\Validator\AddonRules;

class AddonController extends Controller
{
	/** @var userRepo */
    private $addonModel;
		private $configModel;
		private $container;
		private $rules;

    public function __construct(
			Addon $addonRepo,
			Config $configRepo,
			Container $container,
			AddonRules $rules
		)
    {
        $this->addonModel = $addonRepo;
				$this->configModel = $configRepo;
				$this->container = $container;
				$this->rules = $rules;
    }
    public function index(Request $request)
    {
				$pageSizes = $this->configModel->getPageSizes();
				$data = $this->container->make('builderModel')
														->request($request)
														->pageSize($this->configModel->getPageSize())
														->getData($this->addonModel);
				$table = $this->container->make('builderTable')
									->tabs($this->configModel->tabsConfigGroupList())
									->data($data['model'])
									->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
									->column(['prop' => 'name',       'label'=> '标识',   'minWidth'=> '80'])
									->column(['prop' => 'title',      'label'=> '名称',   'minWidth'=> '100'])
									->column(['prop' => 'status',     'label'=> '状态',   'minWidth'=> '90','type' => 'status'])
									->column(['prop' => 'description','label'=> '描述',   'width'=> '180'])
									->column(['prop' => 'author',     'label'=> '作者',   'minWidth'=> '70'])
									->column(['prop' => 'version',    'label'=> '版本',   'minWidth'=> '70'])
									->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220','type' => 'btn'])
									->topButton(['buttonType'=>'add',       'apiUrl'=> route('api.admin.system.config.add'),'title'=>'安装插件'])                         // 添加新增按钮
									->topButton(['buttonType'=>'resume',    'apiUrl'=> route('api.admin.system.config.status')])                         // 添加启用按钮
									->topButton(['buttonType'=>'forbid',    'apiUrl'=> route('api.admin.system.config.status')])                         // 添加禁用按钮
									->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.admin.system.config.delete'),'title'=>'卸载'])                         // 添加删除按钮
									->rightButton(['buttonType'=>'edit',    'apiUrl'=> route('api.admin.system.config.edit')])                         // 添加编辑按钮
									->rightButton(['buttonType'=>'forbid',  'apiUrl'=> route('api.admin.system.config.status')])                       // 添加禁用/启用按钮
									->rightButton(['buttonType'=>'delete',  'apiUrl'=> route('api.admin.system.config.delete'),'title'=>'卸载'])                       // 添加删除按钮
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
}
