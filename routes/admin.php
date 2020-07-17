<?php

use Illuminate\Support\Facades\Route;

# 用户管理
Route::get('login', 'Admin\LoginController@login');
Route::post('logout', 'Admin\LoginController@logout');
Route::patch('users/refresh', 'Admin\UserController@refresh');
Route::apiResource('users', 'Admin\UserController');
# 角色管理
Route::apiResource('roles', 'Admin\RoleController');
# 权限管理
Route::apiResource('permissions', 'Admin\PermissionController');

