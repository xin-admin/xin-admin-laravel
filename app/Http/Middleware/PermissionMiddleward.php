<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * 权限验证中间件
 */
class PermissionMiddleward
{

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $key): Response|\Inertia\Response
    {
        $user = Auth::user();
        $permission = $user->ruleKeys();
        if (in_array($key, $permission)) {
            return $next($request);
        }
        return Inertia::render('login');
    }


}
