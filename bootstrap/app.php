<?php

use App\Middleware\AllowCrossDomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomain::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
