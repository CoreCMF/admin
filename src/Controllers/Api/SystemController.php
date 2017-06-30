<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use CoreCMF\core\Http\Request as CoreRequest;
use CoreCMF\admin\Models\Config;

class SystemController extends Controller
{
    /** @var configRepository */
    private $configModel;
    private $builderHtml;

    public function __construct(Config $configRepo)
    {
        $this->configModel = $configRepo;
    }
    public function index(CoreRequest $request)
    {
        $group  = $request->get('tabIndex',0);
        $configs = $this->configModel
                            ->where('group', '=', $group)
                            ->orderBy('sort', 'ASC')
                            ->get();
        foreach ($configs as $key => &$config) {
            if ($config['name'] == 'ADMIN_PAGE_SIZE') {
                $config['options']= collect($this->configModel->getPageSizes())
                                          ->map(function ($value) {
                                            return $value.' 条/页';
                                          });
            }
        }
        $tabs = $this->configModel->tabsConfigGroupList();
        $form = resolve('builderForm')
                  ->tabs($tabs)
                  ->tabsGroup('group')
                  ->data($configs)
                  ->apiUrl('submit',route('api.admin.system.system.update'))
                  ->config('labelWidth','100px');
        $html = resolve('builderHtml')
                  ->title('系统设置')
                  ->item($form)
                  ->itemConfig('layout',['xs' => 24, 'sm' => 20, 'md' => 18, 'lg' => 16])
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
