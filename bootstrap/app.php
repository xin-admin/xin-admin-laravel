<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Modules\Common\Middlewares\AllowCrossDomainMiddleware;
use Modules\Common\Middlewares\LanguageMiddleware;
use Modules\SystemUser\Http\Middleware\AuthGuardMiddleware;
use Modules\SystemUser\Http\Middleware\LoginLogMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php'
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomainMiddleware::class);
        $middleware->append(LanguageMiddleware::class);
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
