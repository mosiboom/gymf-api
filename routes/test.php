<?php

use App\Network\CURL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('1', function () {
    $abc = new \App\Services\AuthService('admin', 1);
    dump(request()->getHost());
    dump(request()->getHttpHost());
    dump($abc->generateGroup());
});
Route::get('2', function () {
    $abc = new \App\Services\AuthService('admin', 1);
    dump($abc->generateGroup());
    dump($abc->getUserIdByToken());
})->middleware('admin.auth');
Route::get('3', function () {
    $routes = Route::getRoutes()->get();
    dump($routes);
    $array = [];
    foreach ($routes as $route) {
        dump($route->uri);
        if (isset($route->action['as'])) {
            $array[] = $route->action['as'];
        }

    }
    dump($array);
});
Route::get('4', function () {
    $debug = false;
    ini_set('error_reporting', E_ALL);
    if ($debug) {
        ini_set('display_errors', 'on');//错误显示在浏览器上
        ini_set('log_errors', 'off');//错误不显示在日志
    } else {
        ini_set('display_errors', 'off');
        ini_set('log_errors', 'on');
        ini_set('error_log', public_path('error.log'));
    }
    echo $num = 1;
});

Route::get('5', function (Request $request) {
    $network = new CURL("http://admin.manage.com");
    $result1 = $network->get('/test/curl', ['a' => 1, 'b' => 2]);
    $result2 = $network->post('/test/curl', ['a' => 1, 'b' => 2]);
    $result3 = $network->patch('/test/curl', ['a' => 1, 'b' => 2]);
    $result4 = $network->put('/test/curl', ['a' => 1, 'b' => 2]);
    $result5 = $network->delete('/test/curl', ['a' => 1, 'b' => 2]);
    dump($result1, $result2, $result3, $result4, $result5);
});
Route::get('6', function (Request $request) {
    $network = new App\Network\CURL("http://www.baidu.com");
    dump($network->get(''));
});
/*测试CURL*/
Route::get('curl', function (Request $request) {
    return response(ReturnCorrect([
        'method' => $request->method(),
        'param' => $request->all()
    ]));
});
Route::post('curl', function (Request $request) {
    return response(ReturnCorrect([
        'method' => $request->method(),
        'param' => $request->all()
    ]));
});
Route::put('curl', function (Request $request) {
    return response(ReturnCorrect([
        'method' => $request->method(),
        'param' => $request->all()
    ]));
});
Route::delete('curl', function (Request $request) {
    return response(ReturnCorrect([
        'method' => $request->method(),
        'param' => $request->all()
    ]));
});
Route::patch('curl', function (Request $request) {
    return response(ReturnCorrect([
        'method' => $request->method(),
        'param' => $request->all()
    ]));
});
