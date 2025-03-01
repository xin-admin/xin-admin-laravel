<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowCrossDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // 添加跨域头
        $response = addHeaders($response);
        // 如果是预检请求, 返回 204
        if ($request->isMethod('OPTIONS')) {
            return response()->json([], 204, $response->headers->all());
        }

        return $response;
    }
}
