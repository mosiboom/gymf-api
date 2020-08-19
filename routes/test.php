<?php

use App\Models\Admin\AdminUser;
use App\Network\CURL;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('1', function () {
    $abc = new \App\Services\AuthService('admin', 1);
    dump(\Illuminate\Support\Str::random(16));
    $a = \App\Services\EncryptService::makeAdminUser('123456');
    dump($a);
    dump(request()->getHost());
    dump(request()->getHttpHost());
    dump($abc->generateGroup());
    $admin = AdminUser::query()->find(1);
    dump($admin);
});
Route::get('2', function () {
    return ReturnCorrect([
        'uid' => AuthService::getUserIdFromRequest(),
        'jti' => AuthService::getJtiFromRequest()
    ]);
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
    $token = new AuthService('admin', 'user_id1');
    $access_token = $token->generateAccess()['token'];
    $result1 = $network->debug()->setHeader(['authorization' => "Bearer $access_token"])->get('/test/2', ['a' => 1, 'b' => 2]);
    dump($result1);
});
Route::get('6', function (Request $request) {
    echo 1;
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
/*测试内部接口*/
Route::post('api', function (Request $request) {
    $param = $request->post('param', []);
    $url = $request->post('url', '');
    $header = $request->post('header', []);
    $method = $request->post('method', []);
    if (!$url) return response()->json('需要填写请求接口！');
    $network = new App\Network\CURL();
    switch ($method) {
        case 'POST':
        {
            $result = $network->setHeader($header ?? [])->post($url, $param ?? []);
            break;
        }
        case 'PUT':
        {
            $result = $network->setHeader($header ?? [])->put($url, $param ?? []);
            break;
        }
        case 'PATCH':
        {
            $result = $network->setHeader($header ?? [])->patch($url, $param ?? []);
            break;
        }
        case 'DELETE':
        {
            $result = $network->setHeader($header ?? [])->delete($url, $param ?? []);
            break;
        }
        default:
        {
            $result = $network->setHeader($header ?? [])->get($url, $param ?? []);
            break;
        }
    }
    return response()->json($result);
});
Route::get('api', function () {
    return view('testApi');
});

