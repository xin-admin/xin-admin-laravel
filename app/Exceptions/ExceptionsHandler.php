<?php

namespace App\Exceptions;

use App\Enum\ShowType;
use App\RequestJson;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionsHandler extends ExceptionHandler
{
    use RequestJson;

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
        $exceptionHandlers = [
            HttpResponseException::class => function (HttpResponseException $e) {
                return response()->json($e->toArray(), $e->getCode());
            },
            MissingAbilityException::class => function ($e) {
                return $this->notification(
                    'No Permission',
                    __('system.error.no_permission'),
                    ShowType::WARN_NOTIFICATION
                );
            },
            AuthenticationException::class => function ($e) {
                return response()->json([
                    'msg' => __('user.refresh_token_expired'),
                    'success' => false
                ], 401);
            },
            NotFoundHttpException::class => function ($e) {
                return $this->notification(
                    'Route Not Exist',
                    __('system.error.route_not_exist'),
                    ShowType::WARN_NOTIFICATION
                );
            },
            ValidationException::class => function (ValidationException $e) {
                return response()->json([
                    'msg' => $e->validator->errors()->first(),
                    'showType' => ShowType::WARN_MESSAGE->value,
                    'success' => false,
                ]);
            },
        ];

        foreach ($exceptionHandlers as $exceptionType => $handler) {
            if ($e instanceof $exceptionType) {
                $response = $handler($e);
                break;
            }
        }

        if (!isset($response)) {
            $debug = config('app.debug');
            $data = [
                'msg' => $e->getMessage(),
                'showType' => ShowType::ERROR_MESSAGE->value,
                'success' => false,
            ];

            if ($debug) {
                $data += [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                    'code' => $e->getCode(),
                ];
            }

            $response = response()->json($data);
        }

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Max-Age', 1800);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        return $response;
    }
}
