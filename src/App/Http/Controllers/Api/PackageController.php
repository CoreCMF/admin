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

        $close = [
                'buttonType'=>'close',
                'apiUrl'=> route('api.admin.app.package.status'),
                'data'=> 'close',
                'show'=>['open']
            ];
        $open = [
                'buttonType'=>'open',
                'apiUrl'=> route('api.admin.app.package.status'),
                'data'=> 'open',
                'show'=>['close']
            ];
        $install = [
            'buttonType'=>'add',
            'apiUrl'=> route('api.admin.app.package.store'),
            'title'=>'安装',
            'method'=>'default',
            'show'=> ['uninstall']
        ];
        $uninstall = [
                'buttonType'=>'delete',
                'apiUrl'=> route('api.admin.app.package.delete'),
                'title'=>'卸载',
                'data'=> 'uninstall',
                'warning'=> '此操作将卸载包永久清空相关数据, 是否继续?',
                'hide'=>['uninstall']
            ];
        $table = resolve('builderTable')
                                    ->event('adminPackage')
                                    ->data($data['model'])

                                    ->column(['prop' => 'id',         'label'=> 'ID',     'width'=> '55'])
                                    ->column(['prop' => 'name',       'label'=> '标识',   'minWidth'=> '120'])
                                    ->column(['prop' => 'title',      'label'=> '名称',   'minWidth'=> '180'])
                                    ->column(['prop' => 'status',     'label'=> '状态',   'minWidth'=> '90','type' => 'status', 'config'=> $this->packageModel->statusConfig])
                                    ->column(['prop' => 'author',     'label'=> '作者',   'minWidth'=> '100'])
                                    ->column(['prop' => 'version',    'label'=> '版本',   'minWidth'=> '100'])
                                    ->column(['prop' => 'rightButton','label'=> '操作',   'minWidth'=> '220','type' => 'btn'])

                                    ->topButton(['buttonType'=>'resume',    'apiUrl'=> route('api.admin.app.package.status')])                         // 添加启用按钮
                                    ->topButton(['buttonType'=>'forbid',    'apiUrl'=> route('api.admin.app.package.status')])                         // 添加禁用按钮
                                    ->topButton(['buttonType'=>'delete',    'apiUrl'=> route('api.admin.app.package.delete'),'title'=>'卸载'])                         // 添加删除按钮

                                    ->rightButton($install)     //安装
                                    ->rightButton($close)         //关闭
                                    ->rightButton($open)        //打开
                                    ->rightButton($uninstall)   //卸载
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
            if ($value == 'close' || $value == 'open') {
                $config = $this->packageModel->where('id', '=', $id)->update(['status' => $value]);
            }
        }
        $message = [
                                        'message'   => '扩展包状态成功!',
                                        'type'      => 'success',
                                ];
        return resolve('builderHtml')->message($message)->response();
    }
    /**
     * [store 启用禁用扩展包]
     * @param  Request       $request       [description]
     * @param  packageManage $packageManage [description]
     * @return [type]                       [description]
     */
    public function store(Request $request, packageManage $packageManage)
    {
        $packageManage->install($request->id);
        $message = [
                  'message'   => '模块安装成功.',
                  'type'      => 'success',
              ];
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
            if ($value == 'uninstall') {
                $package = $this->packageModel->find($id);
                $package->status = 'uninstall';
                $package->save();
                $packageManage->uninstall($package);//执行包卸载命令
            }
        }
        $message = [
                        'message'   => '扩展包卸载成功!',
                        'type'      => 'success',
                    ];
        return resolve('builderHtml')->message($message)->response();
    }
}
