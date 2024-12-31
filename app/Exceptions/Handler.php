<?php

namespace App\Exceptions;

use App\Enum\ShowType;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     * 未报告的异常类型列表
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        // HTTP Response Exception HTTP 错误响应
        if ($e instanceof HttpResponseException) {
            return response()->json($e->getResData(), $e->getCode());
        }

        // Authorize Exception 权限验证异常
        if ($e instanceof AuthorizeException) {
            return response()->json([
                'msg' => $e->getMessage(),
                'showType' => ShowType::ERROR_NOTIFICATION->value,
                'success' => false,
            ]);
        }

        // NotFoundHttpException 地址不存在
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'msg' => '请求地址不存在：'.$e->getMessage(),
                'showType' => ShowType::WARN_MESSAGE->value,
                'success' => false,
            ], 404);
        }

        // ValidationException 数据验证异常
        if ($e instanceof ValidationException) {
            return response()->json([
                'msg' => $e->validator->errors()->first(),
                'showType' => ShowType::WARN_MESSAGE->value,
                'success' => false,
                'errors' => $e->validator->errors(),
            ]);
        }

        return response()->json([
            'msg' => $e->getMessage(),
            'showType' => ShowType::ERROR_MESSAGE->value,
            'success' => false,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
            'code' => $e->getCode(),
        ]);
    }
}
