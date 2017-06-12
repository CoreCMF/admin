<?php

namespace CoreCMF\admin\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use CoreCMF\admin\Models\Config;
use CoreCMF\core\Builder\Html;

class SystemController extends Controller
{
    /** @var configRepository */
    private $configModel;
    private $builderHtml;

    public function __construct(Config $configRepo, Html $html)
    {
        $this->configModel = $configRepo;
        $this->builderHtml = $html;
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
        $tabs = $this->configModel->tabsConfigGroupList();

        $builderHtml = $this->builderHtml;
        $builderHtml->title('系统设置');
        $builderHtml->tabs($tabs);
        $builderHtml->form->data($configs);
        $builderHtml->form->apiUrl('submit',route('api.admin.system.system.update'));
        $builderHtml->form->config(['width'=>'100px']);
        return $builderHtml->response();
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
