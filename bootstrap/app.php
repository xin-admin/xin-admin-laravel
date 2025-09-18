<?php

use App\Http\Middleware\AllowCrossDomainMiddleware;
use App\Http\Middleware\AuthGuardMiddleware;
use App\Http\Middleware\LoginLogMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomainMiddleware::class);
        $middleware->alias([
            'login_log' => LoginLogMiddleware::class,
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'authGuard' => AuthGuardMiddleware::class,
        ]);
        // 未登录响应
        $middleware->redirectGuestsTo(function (Request $request) {
            return response()->json([
                'success' => false,
                'msg' => __('user.not_login')
            ], 401);
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
