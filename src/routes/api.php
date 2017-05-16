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
        Route::get('main', [ 'as' => 'main', 'uses' => 'MainController@index']);
    });
});
