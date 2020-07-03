<?php

use Illuminate\Support\Facades\Route;

# 用户管理
Route::get('login', 'Admin\LoginController@login');
Route::post('logout', 'Admin\LoginController@logout');
Route::patch('users/refresh', 'Admin\UserController@refresh');
Route::apiResource('users', 'Admin\UserController');

# 角色管理
Route::apiResource('roles', 'Admin\RoleController');


Route::get('test', function () {
    $a = app_path('/Http/Requests/RequestTpl.php');
    $b = file_get_contents($a);
    $d = "Admin/ASDASD/UserRequest";
    $d = explode('/', $d);
    $namespace = "namespace App\Http\Requests";
    $count = count($d);
    if ($count > 1) {
        foreach ($d as $k => $v) {
            if ($count - 1 == $k) break;
            $namespace .= "\\" . $v;
        }
    }
    $c = str_replace("RequestTpl", $d[count($d) - 1], $b);
    dump($a, $b, $c, $d, $namespace);
    echo strstr("Admin/ASDASD/UserRequest.php",".",true);
});
Route::get('test1', function () {
    $abc = new \App\Services\AuthService('admin', 1);
    dump($abc->generateGroup());
})->middleware('admin . auth');
