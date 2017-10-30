<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 180)           ->comment('名称');
            $table->string('parent', 180)         ->comment('父级名称')->nullable();
            $table->string('group', 11)           ->comment('分组');
            $table->string('title', 31)           ->comment('导航标题');
            $table->string('type', 15)            ->comment('导航类型');
            $table->text('value')                ->comment('导航值')->nullable();
            $table->string('api_route')          ->comment('API网址')->nullable();
            $table->string('icon', 32)            ->comment('字体图标')->nullable();
            $table->string('target', 11)          ->comment('打开方式')->nullable();
            $table->tinyInteger('status')        ->comment('状态')->default(1);
            $table->bigInteger('sort')           ->comment('排序')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menus');
    }
}
