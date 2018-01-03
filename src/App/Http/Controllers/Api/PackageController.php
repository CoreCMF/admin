<?php

namespace CoreCMF\Admin\App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use CoreCMF\Core\App\Models\Package;
use CoreCMF\Admin\App\Models\Config;
use CoreCMF\Admin\App\Http\Validator\ModelRules;
use CoreCMF\Core\Support\Package\Manage as packageManage;

class PackageController extends Controller
{
    /** @var userRepo */
    private $packageModel;
    private $configModel;
    private $rules;

    public function __construct(Package $packageRepo, Config $configRepo, ModelRules $rules)
    {
        $this->packageModel = $packageRepo;
        $this->configModel = $configRepo;
        $this->rules = $rules;
    }
    public function index(Request $request, packageManage $packageManage)
    {
        $packageManage = $packageManage->updatePackage();//更新数据库插件包
        $pageSizes = $this->configModel->getPageSizes();
        $data = resolve('builderModel')
                                                        ->request($request)
                                                        ->pageSize($this->configModel->getPageSize())
                                                        ->getData($this->packageModel);
        $table = resolve('builderTable')
                                    ->event('adminPackage')
                                    ->data($data['model'])

                                    ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                                    ->column(['prop' => 'name',       'label'=> '标识',   'minWidth'=> '120'])
                                    ->column(['prop' => 'title',      'label'=> '名称',   'minWidth'=> '180'])
                                    ->column(['prop' => 'status',     'label'=> '状态',   'minWidth'=> '90','type' => 'status'])
                                    // ->column(['prop' => 'description','label'=> '描述',   'width'=> '180'])
                                    ->column(['prop' => 'author',     'label'=> '作者',   'minWidth'=> '100'])
                                    ->column(['prop' => 'version',    'label'=> '版本',   'minWidth'=> '100'])
                                    ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220','type' => 'btn'])

                                    ->topButton(['buttonType'=>'add',       'apiUrl'=> route('api.admin.app.package.add'),'title'=>'安装扩展包'])                         // 添加新增按钮
                                    ->topButton(['buttonType'=>'resume',    'apiUrl'=> route('api.admin.app.package.status')])                         // 添加启用按钮
                                    ->topButton(['buttonType'=>'forbid',    'apiUrl'=> route('api.admin.app.package.status')])                         // 添加禁用按钮
                                    ->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.admin.app.package.delete'),'title'=>'卸载'])                         // 添加删除按钮

                                    ->rightButton(['buttonType'=>'forbid',  'apiUrl'=> route('api.admin.app.package.status')])                       // 添加禁用/启用按钮
                                    ->rightButton(['buttonType'=>'delete',  'apiUrl'=> route('api.admin.app.package.delete'),'title'=>'卸载'])                       // 添加删除按钮
                                    ->pagination(['total'=>$data['total'], 'pageSize'=>$data['pageSize'], 'pageSizes'=>$pageSizes])
                                    ->searchTitle('请输入搜索内容')
                                    ->searchSelect(['id'=>'ID','name'=>'标识','title'=>'名称','author'=>'作者'])
                                    ;
        return resolve('builderHtml')->title('扩展包管理')->item($table)->response();
    }
    /**
     * [status description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function status(Request $request)
    {
        foreach ($request->all() as $id => $value) {
            $config = $this->packageModel->where('id', '=', $id)->update(['status' => $value]);
        }
        $message = [
                                        'message'   => '扩展包状态成功!',
                                        'type'      => 'success',
                                ];
        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [add 安装扩展包]
     */
    public function add()
    {
        $form = resolve('builderForm')
                                ->item(['name' => 'composer',  'type' => 'textarea', 'label' => 'composer下载',  'placeholder' => '请输入url,通过composer下载并且保证服务器已经安装composer服务。'])
                                ->item(['name' => 'namespace', 'type' => 'text',     'label' => '扩展包命名空间',            'placeholder' => '命名空间,例: CoreCMF\Socialite\ 。一般在composer.json里面有配置,autoload.psr-4的值.' ])
                                ->rules($this->rules->add())
                                ->apiUrl('submit', route('api.admin.app.package.store'))
                                ->config('labelWidth', '150px')
                                ->config('formSubmit', ['name'=>'安装','style'=> ['width'=>'25%']])
                                ;
        return resolve('builderHtml')->title('安装扩展包')->item($form)->config('layout', ['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])->response();
    }
    /**
     * [store 启用禁用扩展包]
     * @param  Request       $request       [description]
     * @param  packageManage $packageManage [description]
     * @return [type]                       [description]
     */
    public function store(Request $request, packageManage $packageManage)
    {
        $message = $packageManage->namespaceInstall($request->namespace);
        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [delete 卸载展包]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, packageManage $packageManage)
    {
        foreach ($request->all() as $id => $value) {
            $package = $this->packageModel->find($id);
            $packageManage->uninstall($package->uninstall);//执行包卸载命令
                        $package->forceDelete(); //删除包数据库数据
        }
        $message = [
                                        'message'   => '扩展包删除成功!',
                                        'type'      => 'success',
                                ];
        return resolve('builderHtml')->message($message)->response();
    }
}
