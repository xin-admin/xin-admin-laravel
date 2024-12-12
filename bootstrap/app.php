<?php

use App\Enum\ShowType;
use App\Exception\AuthorizeException;
use App\Exception\HttpResponseException;
use App\Middleware\AllowCrossDomain;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // 全局跨域中间件
        $middleware->append(AllowCrossDomain::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpResponseException $e) {
            return response()->json($e->getResData(), $e->getCode());
        });
        $exceptions->render(function (AuthorizeException $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'showType' => ShowType::ERROR_MESSAGE->value,
                'success' => false,
            ], $e->getCode());
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'msg' => '地址不存在：'.$e->getMessage(),
                'showType' => ShowType::WARN_MESSAGE->value,
                'success' => false,
            ], 404);
        });
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'msg' => $e->validator->errors()->first(),
                'showType' => ShowType::WARN_MESSAGE->value,
                'success' => false,
            ]);
        });
    })->create();
