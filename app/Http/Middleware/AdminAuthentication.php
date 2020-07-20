<?php

namespace App\Http\Middleware;

use App\Enums\ResponseMessageEnum;
use App\Services\AuthService;
use Closure;
use DemeterChain\A;
use Illuminate\Support\Facades\Request;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        # Apache获取token先在.htaccess中加入 SetEnvIf Authorization ^(.*) HTTP_AUTHORIZATION=$1
        $Auth = Request::header('Authorization');
        if (!$Auth) {
            return response(ReturnAPI(ResponseMessageEnum::LOGIN_ERROR))->setStatusCode(401);
        }
        $Authorization = new AuthService('admin');
        $token = $Authorization->getFinalToken($Auth);
        if (!$token) {
            return response(ReturnAPI(ResponseMessageEnum::LOGIN_ERROR))->setStatusCode(401);
        }
        $result = $Authorization->verifyAccess($token);
        if (!$result['token']) {
            return response(ReturnAPI($result['code']))->setStatusCode(401);
        }
        $uid = $result['payload']['uid'];
        $Authorization::setUserIdFromRequest($uid);
        return $next($request);
    }
}
