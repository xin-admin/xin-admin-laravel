<?php

use App\Http\Middleware\AllowCrossDomainMiddleware;
use App\Http\Middleware\AuthGuardMiddleware;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\LoginLogMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomainMiddleware::class);
        $middleware->append(LanguageMiddleware::class);
        $middleware->append([
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
        $middleware->alias([
            'login_log' => LoginLogMiddleware::class,
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'authGuard' => AuthGuardMiddleware::class,
        ]);
        // 未登录响应
        $middleware->redirectGuestsTo(function (Request $request) {
            return redirect('/admin/login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
