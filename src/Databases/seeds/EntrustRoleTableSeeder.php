<?php
namespace CoreCMF\Admin\Databases\seeds;

use DB;
use Illuminate\Database\Seeder;

class EntrustRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entrust_roles')->insert([
            'name'            => 'admin',
            'display_name'    => '超级管理员',
            'description'     => '网站超级管理员,具有最高权限',
            'group'           => 'admin'
        ]);
    }
}
