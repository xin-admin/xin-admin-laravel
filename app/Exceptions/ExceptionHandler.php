<?php

namespace App\Exceptions;

use App\Enum\ShowType;
use Illuminate\Foundation\Exceptions\Handler as ;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionHandler extends ExceptionHandler
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
    public function register(): void
    {
        // HTTP Response Exception HTTP 错误响应
        $this->renderable(function (HttpResponseException $e) {
            return response()->json($e->getResData(), $e->getCode());
        });

        // Authorize Exception 权限验证异常
        $this->renderable(function (AuthorizeException $e) {
            return response()->json([
                'msg' => $e->getMessage(),
                'showType' => ShowType::ERROR_NOTIFICATION->value,
                'success' => false,
            ]);
        });

        // NotFoundHttpException 地址不存在
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'msg' => '请求地址不存在：'.$e->getMessage(),
                'showType' => ShowType::WARN_MESSAGE->value,
                'success' => false,
            ], 404);
        });

        // ValidationException 数据验证异常
        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'msg' => $e->validator->errors()->first(),
                'showType' => ShowType::WARN_MESSAGE->value,
                'success' => false,
            ]);
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
