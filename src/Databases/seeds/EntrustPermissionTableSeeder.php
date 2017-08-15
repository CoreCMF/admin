<?php
namespace CoreCMF\admin\Databases\seeds;

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
        //系统设置
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.system',
            'parent' 		=> '',
            'display_name' 	=> '系统设置',
            'description' 	=> '设置后台系统常用配置接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.system.update',
            'parent' 		=> 'api.admin.system',
            'display_name' 	=> '保存',
            'description' 	=> '系统设置保存接口权限',
            'group' 		=> 'admin',
        ]);
        //导航菜单
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu',
            'parent' 		=> '',
            'display_name' 	=> '导航菜单',
            'description' 	=> '全站的导航菜单列表显示接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu.status',
            'parent' 		=> 'api.admin.menu',
            'display_name' 	=> '状态',
            'description' 	=> '导航菜单数据状态通信接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu.delete',
            'parent' 		=> 'api.admin.menu',
            'display_name' 	=> '删除',
            'description' 	=> '导航菜单数据删除接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu.add',
            'parent' 		=> 'api.admin.menu',
            'display_name' 	=> '新增',
            'description' 	=> '导航菜单数据新增接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu.store',
            'parent' 		=> 'api.admin.menu',
            'display_name' 	=> '保存',
            'description' 	=> '导航菜单数据保存接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu.edit',
            'parent' 		=> 'api.admin.menu',
            'display_name' 	=> '编辑',
            'description' 	=> '导航菜单数据编辑接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.menu.update',
            'parent' 		=> 'api.admin.menu',
            'display_name' 	=> '更新',
            'description' 	=> '导航菜单数据更新接口权限',
            'group' 		=> 'admin',
        ]);
        //配置管理
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config',
            'parent' 		=> '',
            'display_name' 	=> '配置管理',
            'description' 	=> '配置管理列表显示接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config.status',
            'parent' 		=> 'api.admin.config',
            'display_name' 	=> '状态',
            'description' 	=> '配置管理数据状态通信接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config.delete',
            'parent' 		=> 'api.admin.config',
            'display_name' 	=> '删除',
            'description' 	=> '配置管理数据删除接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config.add',
            'parent' 		=> 'api.admin.config',
            'display_name' 	=> '新增',
            'description' 	=> '配置管理数据新增接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config.store',
            'parent' 		=> 'api.admin.config',
            'display_name' 	=> '保存',
            'description' 	=> '配置管理数据保存接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config.edit',
            'parent' 		=> 'api.admin.config',
            'display_name' 	=> '编辑',
            'description' 	=> '配置管理数据编辑接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.config.update',
            'parent' 		=> 'api.admin.config',
            'display_name' 	=> '更新',
            'description' 	=> '配置管理数据更新接口权限',
            'group' 		=> 'admin',
        ]);
        //用户管理
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user',
            'parent' 		=> '',
            'display_name' 	=> '用户管理',
            'description' 	=> '用户管理列表显示接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user.status',
            'parent' 		=> 'api.admin.user',
            'display_name' 	=> '状态',
            'description' 	=> '用户管理数据状态通信接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user.delete',
            'parent' 		=> 'api.admin.user',
            'display_name' 	=> '删除',
            'description' 	=> '用户管理数据删除接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user.add',
            'parent' 		=> 'api.admin.user',
            'display_name' 	=> '新增',
            'description' 	=> '用户管理数据新增接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user.store',
            'parent' 		=> 'api.admin.user',
            'display_name' 	=> '保存',
            'description' 	=> '用户管理数据保存接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user.edit',
            'parent' 		=> 'api.admin.user',
            'display_name' 	=> '编辑',
            'description' 	=> '用户管理数据编辑接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.user.update',
            'parent' 		=> 'api.admin.user',
            'display_name' 	=> '更新',
            'description' 	=> '用户管理数据更新接口权限',
            'group' 		=> 'admin',
        ]);
        //角色管理
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role',
            'parent' 		=> '',
            'display_name' 	=> '角色管理',
            'description' 	=> '角色管理列表显示接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role.status',
            'parent' 		=> 'api.admin.role',
            'display_name' 	=> '状态',
            'description' 	=> '角色管理数据状态通信接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role.delete',
            'parent' 		=> 'api.admin.role',
            'display_name' 	=> '删除',
            'description' 	=> '角色管理数据删除接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role.add',
            'parent' 		=> 'api.admin.role',
            'display_name' 	=> '新增',
            'description' 	=> '角色管理数据新增接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role.store',
            'parent' 		=> 'api.admin.role',
            'display_name' 	=> '保存',
            'description' 	=> '角色管理数据保存接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role.edit',
            'parent' 		=> 'api.admin.role',
            'display_name' 	=> '编辑',
            'description' 	=> '角色管理数据编辑接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.role.update',
            'parent' 		=> 'api.admin.role',
            'display_name' 	=> '更新',
            'description' 	=> '角色管理数据更新接口权限',
            'group' 		=> 'admin',
        ]);
        //权限管理
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission',
            'parent' 		=> '',
            'display_name' 	=> '权限管理',
            'description' 	=> '权限管理列表显示接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission.status',
            'parent' 		=> 'api.admin.permission',
            'display_name' 	=> '状态',
            'description' 	=> '权限管理数据状态通信接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission.delete',
            'parent' 		=> 'api.admin.permission',
            'display_name' 	=> '删除',
            'description' 	=> '权限管理数据删除接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission.add',
            'parent' 		=> 'api.admin.permission',
            'display_name' 	=> '新增',
            'description' 	=> '权限管理数据新增接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission.store',
            'parent' 		=> 'api.admin.permission',
            'display_name' 	=> '保存',
            'description' 	=> '权限管理数据保存接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission.edit',
            'parent' 		=> 'api.admin.permission',
            'display_name' 	=> '编辑',
            'description' 	=> '权限管理数据编辑接口权限',
            'group' 		=> 'admin',
        ]);
        DB::table('entrust_permissions')->insert([
            'name' 	=> 'api.admin.permission.update',
            'parent' 		=> 'api.admin.permission',
            'display_name' 	=> '更新',
            'description' 	=> '权限管理数据更新接口权限',
            'group' 		=> 'admin',
        ]);
    }
}
