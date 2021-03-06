<?php

use Illuminate\Support\Facades\Route;

#用户管理
Route::post('login', 'Admin\LoginController@login');
#登录检测
Route::middleware(['admin.auth'])->group(function () {
    #首页配置写入
    Route::post('home', 'HomeController@create');
    #退出登录
    Route::get('logout', 'Admin\LoginController@logout');
    #刷新令牌
    Route::patch('users/refresh', 'Admin\UserController@refresh');
    #用户管理
    Route::apiResource('users', 'Admin\UserController');
    #分类管理
    Route::apiResource('category', 'SiteCategoryController');
    #添加前端分类轮播
    Route::post('category/banner/{id}', 'SiteCategoryController@addBanner')->name('category.addBanner');
    #资源库
    Route::apiResource('resource', 'ResourceLibController');
    #资源分类
    Route::get('resource/category/get', 'ResourceLibController@category');
    #产品内容
    Route::apiResource('product', 'ProductPostController');
    #团队成员管理
    Route::apiResource('team', 'TeamMemberController');
    #文件上传
    Route::post('upload/{key}/{original?}', 'CommonController@upload');
    #留言管理
    Route::apiResource('contacts', 'UserContactsController');
});

