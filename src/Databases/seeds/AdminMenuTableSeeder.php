<?php
namespace CoreCMF\admin\Databases\seeds;

use DB;
use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.dashboard.index',
        	  'parent'	=> '',
            'group' 	=> 'admin',
            'title' 	=> '首页 Dashboard',
            'type' 		=> '',
            'value'     => '/admin/dashboard',
            'api_route'    => 'api.admin.dashboard.index',
            'icon' 		=> 'fa fa-dashboard',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.system',
            'parent'	=> '',
            'group' 	=> 'admin',
            'title' 	=> '系统功能',
            'type' 		=> '',
            'value'     => '',
            'api_route'    => '',
            'icon' 		=> 'fa fa-cog',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.system.system',
            'parent'	=> 'admin.system',
            'group' 	=> 'admin',
            'title' 	=> '系统设置',
            'type' 		=> '',
            'value'     => '/admin/system/system',
            'api_route'    => 'api.admin.system.system',
            'icon' 		=> 'fa fa-wrench',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.system.menu',
            'parent'	=> 'admin.system',
            'group' 	=> 'admin',
            'title' 	=> '导航菜单',
            'type' 		=> '',
            'value'     => '/admin/system/menu',
            'api_route'    => 'api.admin.system.menu',
            'icon' 		=> 'fa fa-map-signs',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	  => 'admin.system.config',
            'parent'	=> 'admin.system',
            'group' 	=> 'admin',
            'title' 	=> '配置管理',
            'type' 		=> '',
            'value'     => '/admin/system/config',
            'api_route'    => 'api.admin.system.config',
            'icon' 		=> 'fa fa-cogs',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	  => 'admin.system.upload',
            'parent'	=> 'admin.system',
            'group' 	=> 'admin',
            'title' 	=> '上传管理',
            'type' 		=> '',
            'value'     => '/admin/system/upload',
            'api_route'    => 'api.admin.system.upload',
            'icon' 		=> 'fa fa-upload',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.app',
            'parent'	=> '',
            'group' 	=> 'admin',
            'title' 	=> '应用中心',
            'type' 		=> '',
            'value'     => '',
            'api_route'    => '',
            'icon' 		=> 'fa fa-folder-open-o',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.app.model',
            'parent'	=> 'admin.app',
            'group' 	=> 'admin',
            'title' 	=> '模块扩展',
            'type' 		=> '',
            'value'     => '/admin/app/model',
            'api_route'    => 'api.admin.app.model',
            'icon' 		=> 'fa fa-wrench',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.app.addon',
            'parent'	=> 'admin.app',
            'group' 	=> 'admin',
            'title' 	=> '插件管理',
            'type' 		=> '',
            'value'     => '/admin/app/addon',
            'api_route'    => 'api.admin.app.addon',
            'icon' 		=> 'fa fa-cogs',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.app.theme',
            'parent'	=> 'admin.app',
            'group' 	=> 'admin',
            'title' 	=> '主题管理',
            'type' 		=> '',
            'value'     => '/admin/app/theme',
            'api_route'    => 'api.admin.app.theme',
            'icon' 		=> 'fa fa-adjust',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.user',
            'parent'	=> '',
            'group' 	=> 'admin',
            'title' 	=> '用户权限',
            'type' 		=> '',
            'value'     => '',
            'api_route'    => '',
            'icon' 		=> 'fa fa-unlock-alt',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.user.user',
            'parent'	=> 'admin.user',
            'group' 	=> 'admin',
            'title' 	=> '用户管理',
            'type' 		=> '',
            'value'     => '/admin/user/user',
            'api_route'    => 'api.admin.user.user',
            'icon' 		=> 'fa fa-user',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.user.role',
            'parent'	=> 'admin.user',
            'group' 	=> 'admin',
            'title' 	=> '角色管理',
            'type' 		=> '',
            'value'     => '/admin/user/role',
            'api_route'    => 'api.admin.user.role',
            'icon' 		=> 'fa fa-group',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);
        DB::table('admin_menus')->insert([
            'name'	=> 'admin.user.permission',
            'parent'	=> 'admin.user',
            'group' 	=> 'admin',
            'title' 	=> '权限管理',
            'type' 		=> '',
            'value'     => '/admin/user/permission',
            'api_route'    => 'api.admin.user.permission',
            'icon' 		=> 'fa fa-unlock',
            'target' 	=> '',
            'sort' 		=> 1,
            'status' 	=> 1,
        ]);

    }
}
