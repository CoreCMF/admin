<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'CoreCMF\admin\Controllers\Api', 'as' => 'api.'], function () {
    /*
    |--------------------------------------------------------------------------
    | Admin主路由设置 routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::post('main', [ 'as' => 'main', 'uses' => 'MainController@index']);
    });
    /*
    |--------------------------------------------------------------------------
    | 需要用户认证路由模块
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['api']], function () {
    	// 后台nav配置
    	Route::group(['prefix' => 'nav', 'as' => 'nav.'], function () {
		    Route::post('top',                ['as' => 'top',     'uses' => 'NavController@top']);  
		    Route::post('sidebar',            ['as' => 'sidebar',  'uses' => 'NavController@sidebar']);  
		});
    	// 后台仪表盘路由
	    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
		    Route::post('dashboard',                ['as' => 'index',     'uses' => 'DashboardController@index']);  
		});
	    // 后台系统设置路由
		Route::group(['prefix' => 'system', 'as' => 'system.'], function () {
		    /**
		     *  系统设置
		     */
		    Route::post('system',                   ['as' => 'system',              'uses' => 'SystemController@index']);
		    Route::post('system/update',            ['as' => 'system.update',       'uses' => 'SystemController@update']);
		    /**
		     * 菜单导航
		     */
		    Route::post('menu',                     ['as' => 'menu',                'uses' => 'MenuController@index']);
		    Route::post('menu/status',              ['as' => 'menu.status',         'uses' => 'MenuController@status']);
		    Route::post('menu/delete',              ['as' => 'menu.delete',         'uses' => 'MenuController@delete']);
		    Route::post('menu/add',                 ['as' => 'menu.add',            'uses' => 'MenuController@add']);
		    Route::post('menu/store',               ['as' => 'menu.store',          'uses' => 'MenuController@store']);
		    Route::post('menu/edit',                ['as' => 'menu.edit',           'uses' => 'MenuController@edit']);
		    Route::post('menu/update',              ['as' => 'menu.update',         'uses' => 'MenuController@update']);
		    /**
		     *  配置管理
		     */
		    Route::post('config',                   ['as' => 'config',              'uses' => 'ConfigController@index']);
		    Route::post('config/status',            ['as' => 'config.status',       'uses' => 'ConfigController@status']);
		    Route::post('config/delete',            ['as' => 'config.delete',       'uses' => 'ConfigController@delete']);
		    Route::post('config/add',               ['as' => 'config.add',          'uses' => 'ConfigController@add']);
		    Route::post('config/store',             ['as' => 'config.store',        'uses' => 'ConfigController@store']);
		    Route::post('config/edit',              ['as' => 'config.edit',         'uses' => 'ConfigController@edit']);
		    Route::post('config/update',            ['as' => 'config.update',       'uses' => 'ConfigController@update']);
		    /**
		     *  上传管理
		     */
		    Route::post('upload',                   ['as' => 'upload',              'uses' => 'UploadController@index']);
		    Route::post('upload/status',            ['as' => 'upload.status',       'uses' => 'UploadController@status']);
		    Route::post('upload/delete',            ['as' => 'upload.delete',       'uses' => 'UploadController@delete']);
		    Route::post('upload/add',               ['as' => 'upload.add',          'uses' => 'UploadController@add']);
		    Route::post('upload/store',             ['as' => 'upload.store',        'uses' => 'UploadController@store']);
		    Route::post('upload/edit',              ['as' => 'upload.edit',         'uses' => 'UploadController@edit']);
		    Route::post('upload/update',            ['as' => 'upload.update',       'uses' => 'UploadController@update']);
		    Route::post('upload/image',             ['as' => 'upload.image',        'uses' => 'UploadController@postImage']);

		    Route::post('model',                    ['as' => 'model',              'uses' => 'ModelController@index']);
		    Route::post('addon',                    ['as' => 'addon',              'uses' => 'AddonController@index']);
		    Route::post('theme',                    ['as' => 'theme',              'uses' => 'ThemeController@index']);
		    /**
		     * 用户管理
		     */
		    Route::post('user',                     ['as' => 'user',                'uses' => 'UserController@index']);
		    Route::post('user/status',              ['as' => 'user.status',         'uses' => 'UserController@status']);
		    Route::post('user/delete',              ['as' => 'user.delete',         'uses' => 'UserController@delete']);
		    Route::post('user/add',                 ['as' => 'user.add',            'uses' => 'UserController@add']);
		    Route::post('user/store',               ['as' => 'user.store',          'uses' => 'UserController@store']);
		    Route::post('user/edit',                ['as' => 'user.edit',           'uses' => 'UserController@edit']);
		    Route::post('user/update',              ['as' => 'user.update',         'uses' => 'UserController@update']);
		    /**
		     * 角色管理
		     */
		    Route::post('role',                     ['as' => 'role',                    'uses' => 'RoleController@index']);
		    Route::post('role/status',              ['as' => 'role.status',            'uses' => 'RoleController@status']);
		    Route::post('role/delete',              ['as' => 'role.delete',            'uses' => 'RoleController@delete']);
		    Route::post('role/add',                 ['as' => 'role.add',               'uses' => 'RoleController@add']);
		    Route::post('role/store',               ['as' => 'role.store',             'uses' => 'RoleController@store']);
		    Route::post('role/edit',                ['as' => 'role.edit',              'uses' => 'RoleController@edit']);
		    Route::post('role/update',              ['as' => 'role.update',            'uses' => 'RoleController@update']);
		    Route::post('role/permission',          ['as' => 'role.permission',        'uses' => 'RoleController@permission']);
		    Route::post('role/permission-update',   ['as' => 'role.permission-update', 'uses' => 'RoleController@permissionUpdate']);
		    /**
		     * 权限管理
		     */
		    Route::post('permission',               ['as' => 'permission',              'uses' => 'PermissionController@index']);
		    Route::post('permission/status',        ['as' => 'permission.status',       'uses' => 'PermissionController@status']);
		    Route::post('permission/delete',        ['as' => 'permission.delete',       'uses' => 'PermissionController@delete']);
		    Route::post('permission/add',           ['as' => 'permission.add',          'uses' => 'PermissionController@add']);
		    Route::post('permission/store',         ['as' => 'permission.store',        'uses' => 'PermissionController@store']);
		    Route::post('permission/edit',          ['as' => 'permission.edit',         'uses' => 'PermissionController@edit']);
		    Route::post('permission/update',        ['as' => 'permission.update',       'uses' => 'PermissionController@update']);
		    
		});
	});
});
