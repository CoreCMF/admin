<?php

namespace CoreCMF\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Container\Container;

use App\Http\Controllers\Controller;
use CoreCMF\Core\Models\Module as modelModule;
use CoreCMF\Admin\Models\Config;
use CoreCMF\Admin\Validator\ModelRules;
use CoreCMF\Core\Support\Module\Module;

class ModuleController extends Controller
{
	/** @var userRepo */
    private $moduleModel;
		private $configModel;
		private $container;
		private $rules;

    public function __construct(
			modelModule $moduleRepo,
			Config $configRepo,
			Container $container,
			ModelRules $rules
		)
    {
        $this->moduleModel = $moduleRepo;
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
														->getData($this->moduleModel);
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
									->topButton(['buttonType'=>'add',       'apiUrl'=> route('api.admin.app.module.add'),'title'=>'安装模块插件'])                         // 添加新增按钮
									->topButton(['buttonType'=>'resume',    'apiUrl'=> route('api.admin.app.module.status')])                         // 添加启用按钮
									->topButton(['buttonType'=>'forbid',    'apiUrl'=> route('api.admin.app.module.status')])                         // 添加禁用按钮
									->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.admin.app.module.delete'),'title'=>'卸载'])                         // 添加删除按钮
									->rightButton(['buttonType'=>'edit',    'apiUrl'=> route('api.admin.app.module.edit')])                         // 添加编辑按钮
									->rightButton(['buttonType'=>'forbid',  'apiUrl'=> route('api.admin.app.module.status')])                       // 添加禁用/启用按钮
									->rightButton(['buttonType'=>'delete',  'apiUrl'=> route('api.admin.app.module.delete'),'title'=>'卸载'])                       // 添加删除按钮
									->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])
									->searchTitle('请输入搜索内容')
									->searchSelect(['id'=>'ID','name'=>'标识','title'=>'名称','author'=>'作者'])
									;
				$html = $this->container->make('builderHtml')
									->title('模块插件')
									->item($table)
									->response();
				return $html;
    }
		public function add(){
				$form = $this->container->make('builderForm')
								->item(['name' => 'composer',  'type' => 'textarea', 'label' => 'composer下载',  'placeholder' => '请输入url,通过composer下载并且保证服务器已经安装composer服务。'])
								->item(['name' => 'namespace', 'type' => 'text',     'label' => '模块插件命名空间',  			'placeholder' => '命名空间,例: CoreCMF\Socialite\ 。一般在composer.json里面有配置,autoload.psr-4的值.' ])
								->rules($this->rules->add())
								->apiUrl('submit',route('api.admin.app.module.store'))
								->config('labelWidth','150px')
								->config('formSubmit',['name'=>'安装','style'=> ['width'=>'25%']])
								;
				return $this->container->make('builderHtml')
									->title('安装模块插件')
									->item($form)
									->config('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
									->response();
		}
		public function store(Request $request,Module $module)
		{
				$message = $module->namespaceInstall($request->namespace);
				return $this->container->make('builderHtml')
															 ->message($message)
															 ->response();
		}
}
