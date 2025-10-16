<?php

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

if (! function_exists('frankenphp_handle_request')) {
    echo 'FrankenPHP must be in worker mode to use this script.';
    exit(1);
}

ignore_user_abort(true);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = (require_once __DIR__.'/../bootstrap/app.php');

$requestCount = 0;
$debugMode = $_ENV['APP_DEBUG'] ?? $_SERVER['APP_DEBUG'] ?? 'false';
$maxRequests = $_ENV['MAX_REQUESTS'] ?? $_SERVER['MAX_REQUESTS'] ?? 1000;
$requestMaxExecutionTime = $_ENV['REQUEST_MAX_EXECUTION_TIME'] ?? $_SERVER['REQUEST_MAX_EXECUTION_TIME'] ?? null;

try {
    $handleRequest = static function () use ($app, $debugMode) {
        try {
            $app->handleRequest(Request::capture());
        }catch (Throwable $e) {
            if ($app) {
                report($e);
            }

            $response = new Response(
                $debugMode === 'true' ? (string) $e : 'Internal Server Error',
                500,
                [
                    'Status' => '500 Internal Server Error',
                    'Content-Type' => 'text/plain',
                ],
            );

            $response->send();
        }
    };

    while ($requestCount < $maxRequests && frankenphp_handle_request($handleRequest)) {
        $requestCount++;
    }
} finally {
    $app?->terminate();

    gc_collect_cycles();
}