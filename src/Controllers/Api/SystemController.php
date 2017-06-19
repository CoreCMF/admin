<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
    public function index(Request $request)
    {
        $group = $request->tabIndex;
        $group = empty($group) ? 0 : $group;
        $configs = $this->configModel
                            ->where('group', '=', $group)
                            ->orderBy('sort', 'ASC')
                            ->get();
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
