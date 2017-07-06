<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
|--------------------------------------------------------------------------
| Admin后台路由设置 routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api/admin', 'middleware' => 'web', 'namespace' => 'CoreCMF\admin\Controllers\Api', 'as' => 'api.admin.'], function () {
    Route::post('login', [ 'as' => 'auth.login', 'uses' => 'AuthController@postLogin']);
});
Route::group(['prefix' => 'admin', 'middleware' => 'web', 'as' => 'admin'], function () {
    Route::get('/{vue_capture?}', function () {
        return view('admin::index');
    })->where('vue_capture', '[\/\w\.-]*');
});
