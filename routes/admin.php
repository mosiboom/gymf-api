<?php

use Illuminate\Support\Facades\Route;

# 用户管理
Route::post('login', 'Admin\LoginController@login');
# 登录后
Route::middleware(['admin.auth'])->group(function () {
    # 退出登录
    Route::get('logout', 'Admin\LoginController@logout');
    Route::patch('users/refresh', 'Admin\UserController@refresh');
    Route::apiResource('users', 'Admin\UserController');
    Route::apiResource('category', 'SiteCategoryController');
});

Route::post('upload/{key}/{original?}', 'CommonController@upload');
