<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SolveCORS
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST,GET,PUT,OPTIONS,DELETE'); // 允许请求的类型
        header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
        header("Access-Control-Allow-Headers:Authorization,DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");
        return $next($request);
    }
}
