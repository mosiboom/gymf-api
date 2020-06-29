<?php

use App\Models\Admin\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', 'Admin\RoleController');
Route::get('test', function () {
    $role = \App\Models\Admin\AdminRolePermission::add(1, [1, 2, 3]);
    dump($role);
});
