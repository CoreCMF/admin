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
                            ->where('status', '=', 1)
                            ->get();
        return $data = BuilderData::addFormData($configs)
                            ->addFormApiUrl('submit',route('api.admin.system.system.update'))              //添加Submit通信API
                            ->setTabs(Helpers::getTabsConfigGroupList())    //设置页面Tabs
                            ->setTitle('系统设置')
                            ->setFormConfig(['width'=>'100px'])
                            ->get();
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
