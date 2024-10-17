<?php

use App\Middleware\AllowCrossDomain;
use App\Exception\HttpResponseException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::prefix('api')
                ->group(base_path('routes/api/index.php'));

            Route::prefix('admin')
                ->group(base_path('routes/admin/index.php'));
        },
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomain::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpResponseException $e) {
            return response()->json($e->getResData(), $e->getCode());
        });
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'msg' => $e->validator->errors()->first(),
                'showType' => \App\Enum\ShowType::WARN_MESSAGE,
                'success' => false
            ]);
        });
    })->create();
