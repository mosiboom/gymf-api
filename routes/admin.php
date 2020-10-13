<?php

use Illuminate\Support\Facades\Route;

#用户管理
Route::post('login', 'Admin\LoginController@login');
#登录检测
Route::middleware(['admin.auth'])->group(function () {
    #首页
    Route::get('');
    #退出登录
    Route::get('logout', 'Admin\LoginController@logout');
    #刷新令牌
    Route::patch('users/refresh', 'Admin\UserController@refresh');
    #用户管理
    Route::apiResource('users', 'Admin\UserController');
    #分类管理
    Route::apiResource('category', 'SiteCategoryController');
    #资源库
    Route::apiResource('resource', 'ResourceLibController');
    #资源分类
    Route::get('resource/category/get', 'ResourceLibController@category');
    #产品内容
    Route::apiResource('product', 'ProductPostController');
    #文件上传
    Route::post('upload/{key}/{original?}', 'CommonController@upload');
});

