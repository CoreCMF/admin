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
Route::group(['prefix' => 'admin', 'middleware' => 'web', 'namespace' => 'CoreCMF\Admin\Controllers', 'as' => 'admin.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
      Route::post('login', [ 'as' => 'login', 'uses' => 'AuthController@postLogin']);
      Route::post('auth', [ 'as' => 'auth', 'uses' => 'AuthController@index']);
      Route::post('authCheck', [ 'as' => 'check', 'uses' => 'AuthController@authCheck']);
      Route::post('logout', [ 'as' => 'logout', 'uses' => 'AuthController@postLogout']);
    });
});

Route::group(['prefix' => 'admin', 'middleware' => 'web', 'as' => 'admin'], function () {
    Route::get('/{vue_capture?}', function () {
        return view('core::index',[ 'model' => 'admin' ]);
    })->where('vue_capture', '[\/\w\.-]*');
});
