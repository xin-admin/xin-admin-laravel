<?php

use App\Middleware\AllowCrossDomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomain::class);
    })
    ->withSingletons([
        'Illuminate\Foundation\Exceptions\Handler' => 'App\Exceptions\ExceptionHandler',
    ])
    ->withExceptions(function (Exceptions $exceptions) {})->create();
