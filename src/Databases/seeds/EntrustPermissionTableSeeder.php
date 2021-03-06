<?php
namespace CoreCMF\Admin\Databases\seeds;

use DB;
use Illuminate\Database\Seeder;

class EntrustPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 后台首页仪表盘
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.dashboard.index',
            'parent'        => '',
            'display_name'    => '首页 Dashboard',
            'description'    => '首页仪表盘接口权限',
            'group'        => 'admin',
        ]);
        //系统设置
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.system',
            'parent'        => '',
            'display_name'    => '系统设置',
            'description'    => '设置后台系统常用配置接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.system.update',
            'parent'        => 'api.admin.system.system',
            'display_name'    => '保存',
            'description'    => '系统设置保存接口权限',
            'group'        => 'admin',
        ]);
        //导航菜单
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu',
            'parent'        => '',
            'display_name'    => '导航菜单',
            'description'    => '全站的导航菜单列表显示接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu.status',
            'parent'        => 'api.admin.system.menu',
            'display_name'    => '状态',
            'description'    => '导航菜单数据状态通信接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu.delete',
            'parent'        => 'api.admin.system.menu',
            'display_name'    => '删除',
            'description'    => '导航菜单数据删除接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu.add',
            'parent'        => 'api.admin.system.menu',
            'display_name'    => '新增',
            'description'    => '导航菜单数据新增接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu.store',
            'parent'        => 'api.admin.system.menu',
            'display_name'    => '保存',
            'description'    => '导航菜单数据保存接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu.edit',
            'parent'        => 'api.admin.system.menu',
            'display_name'    => '编辑',
            'description'    => '导航菜单数据编辑接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.menu.update',
            'parent'        => 'api.admin.system.menu',
            'display_name'    => '更新',
            'description'    => '导航菜单数据更新接口权限',
            'group'        => 'admin',
        ]);
        //配置管理
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config',
            'parent'        => '',
            'display_name'    => '配置管理',
            'description'    => '配置管理列表显示接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config.status',
            'parent'        => 'api.admin.system.config',
            'display_name'    => '状态',
            'description'    => '配置管理数据状态通信接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config.delete',
            'parent'        => 'api.admin.system.config',
            'display_name'    => '删除',
            'description'    => '配置管理数据删除接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config.add',
            'parent'        => 'api.admin.system.config',
            'display_name'    => '新增',
            'description'    => '配置管理数据新增接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config.store',
            'parent'        => 'api.admin.system.config',
            'display_name'    => '保存',
            'description'    => '配置管理数据保存接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config.edit',
            'parent'        => 'api.admin.system.config',
            'display_name'    => '编辑',
            'description'    => '配置管理数据编辑接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.config.update',
            'parent'        => 'api.admin.system.config',
            'display_name'    => '更新',
            'description'    => '配置管理数据更新接口权限',
            'group'        => 'admin',
        ]);
        //用户管理
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.user',
            'parent'        => '',
            'display_name'    => '用户管理',
            'description'    => '用户管理列表显示接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.user.status',
            'parent'        => 'api.admin.user.user',
            'display_name'    => '状态',
            'description'    => '用户管理数据状态通信接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.user.delete',
            'parent'        => 'api.admin.system.user',
            'display_name'    => '删除',
            'description'    => '用户管理数据删除接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.user.add',
            'parent'        => 'api.admin.user.user',
            'display_name'    => '新增',
            'description'    => '用户管理数据新增接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.user.store',
            'parent'        => 'api.admin.user.user',
            'display_name'    => '保存',
            'description'    => '用户管理数据保存接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.system.user.edit',
            'parent'        => 'api.admin.system.user',
            'display_name'    => '编辑',
            'description'    => '用户管理数据编辑接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.user.update',
            'parent'        => 'api.admin.user.user',
            'display_name'    => '更新',
            'description'    => '用户管理数据更新接口权限',
            'group'        => 'admin',
        ]);
        //角色管理
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role',
            'parent'        => '',
            'display_name'    => '角色管理',
            'description'    => '角色管理列表显示接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.permission',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '权限管路',
            'description'    => '角色管理数据状态通信接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.permission-update',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '权限保存',
            'description'    => '角色管理数据状态通信接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.delete',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '删除',
            'description'    => '角色管理数据删除接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.add',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '新增',
            'description'    => '角色管理数据新增接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.store',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '保存',
            'description'    => '角色管理数据保存接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.edit',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '编辑',
            'description'    => '角色管理数据编辑接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.role.update',
            'parent'        => 'api.admin.user.role',
            'display_name'    => '更新',
            'description'    => '角色管理数据更新接口权限',
            'group'        => 'admin',
        ]);
        //权限管理
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.permission',
            'parent'        => '',
            'display_name'    => '权限管理',
            'description'    => '权限管理列表显示接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.permission.delete',
            'parent'        => 'api.admin.user.permission',
            'display_name'    => '删除',
            'description'    => '权限管理数据删除接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.permission.add',
            'parent'        => 'api.admin.user.permission',
            'display_name'    => '新增',
            'description'    => '权限管理数据新增接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.permission.store',
            'parent'        => 'api.admin.user.permission',
            'display_name'    => '保存',
            'description'    => '权限管理数据保存接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.permission.edit',
            'parent'        => 'api.admin.user.permission',
            'display_name'    => '编辑',
            'description'    => '权限管理数据编辑接口权限',
            'group'        => 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name'    => 'api.admin.user.permission.update',
            'parent'        => 'api.admin.user.permission',
            'display_name'    => '更新',
            'description'    => '权限管理数据更新接口权限',
            'group'        => 'admin',
        ]);
    }
}
