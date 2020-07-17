<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDebug
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $debug = env('APP_DEBUG');
        if (!$debug) return abort(404, '您访问的页面有误！');
        return $next($request);
    }
}
