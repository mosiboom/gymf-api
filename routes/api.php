<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

#文件上传公共接口
/*Route::group(['prefix' => 'upload','middleware'=>'admin.auth'], function () {
    Route::post('/img', 'CommonController@uploadImg');
    Route::post('/file', 'CommonController@uploadFile');
});*/

Route::get('home', 'HomeController@get');

Route::get('category', 'IndexController@getCategory');
Route::get('category/banner/{id}', 'IndexController@getCategoryBanner');
