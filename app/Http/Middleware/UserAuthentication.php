<?php

namespace App\Http\Middleware;

use App\Enums\ResponseMessageEnum;
use App\Services\AuthService;
use Closure;
use Illuminate\Support\Facades\Request;

class UserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*获取token先在.htaccess中加入 SetEnvIf Authorization ^(.*) HTTP_AUTHORIZATION=$1 */
        /* 判断有没有Authorization头 */
        $Auth = Request::header('Authorization');

        if (!$Auth) {
            return response(ReturnAPI(ResponseMessageEnum::LOGIN_ERROR));
        }
        $Authorization = new AuthService('user');
        $token = $Authorization->getFinalToken($Auth);
        if (!$token) {
            return response(ReturnAPI(ResponseMessageEnum::LOGIN_ERROR));
        }
        $result = $Authorization->verifyAccess($token);
        if (!$result['token']) {
            return response(ReturnAPI($result['code']));
        }
        $info = $result['payload']['uid'];
        $request->payload = $info;
        return $next($request);
    }
}
