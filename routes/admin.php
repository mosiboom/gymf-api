<?php

use App\Models\Admin\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', 'Admin\RoleController');
Route::get('test', function () {
    $abc = new \App\Services\AuthService('admin', 1);
    dump($abc->generateGroup());
});
Route::get('test1', function () {
    $abc = new \App\Services\AuthService('admin', 1);
    dump($abc->generateGroup());
})->middleware('admin.auth');
