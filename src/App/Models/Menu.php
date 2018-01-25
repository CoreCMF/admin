<?php

namespace CoreCMF\Admin\App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = 'admin_menus';
    /**
     * [public 状态配置]
     * @var [type]
     */
    public $status = [
        [
            'type' => 'success',
            'icon' => 'fa fa-check',
            'title' => '正常'
        ],
        [
            'type' => 'warning',
            'icon' => 'fa fa-power-off',
            'title' => '禁用'
        ],
    ];
    /**
     * [getGroupMenus 根据分组获取前端菜单信息]
     * @param    [type]                   $group [分组]
     * @return   [type]                          [分组菜单]
     */
    public function getGroupMenus($group)
    {
        return $this->orderBy('sort', 'ASC')
                    ->where('group', '=', $group)
                    ->where('status', '=', 1)
                    ->get();
    }
    /**
     * [getGroupRoutes 根据获取分组前端路由配置信息]
     * @param    [type]                   $group [分组]
     * @return   [type]                          [前端路由定义参数集合]
     */
    public function getGroupRoutes($group)
    {
        $menus = $this->getGroupMenus($group);
        foreach ($menus as $menu) {
            if ($menu->api_route) {
                $routes[] = [
                            'name'=>$menu->api_route,
                            'path'=>$menu->value,
                            'meta'    =>[ 'apiUrl' => route($menu->api_route) ]
                        ];
            }
        }
        return $routes;
    }
}
