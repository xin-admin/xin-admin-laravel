<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$myApp = (require_once __DIR__.'/../bootstrap/app.php');

$maxRequests = 1000;

for ($nbRequests = 0, $running = true;  $nbRequests < $maxRequests && $running; ++$nbRequests) {
    $running = \frankenphp_handle_request(function () use ($myApp) {
        $myApp->handleRequest(Request::capture());
    });

    // 发送 HTTP 响应后执行某些操作
    $myApp->terminate();

    // 调用垃圾回收器以减少在页面生成过程中触发垃圾回收器的几率
    gc_collect_cycles();
}

