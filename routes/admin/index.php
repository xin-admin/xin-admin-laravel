<?php

use App\Http\Controllers\Admin\IndexController;
use Illuminate\Support\Facades\Route;

// 获取 router 文件夹内的所有路由文件
$directory = __DIR__;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() !== 'index.php') {
        // 加载每个路由文件
        require $file->getRealPath();
    }
}

Route::controller(IndexController::class)->group(function () {
    Route::get('/index', 'index');
    Route::post('/index/refreshToken', 'refreshToken');
    Route::post('/index/login', 'login');
    Route::post('/index/logout', 'logout');
    Route::get('/index/getAdminInfo', 'getAdminInfo');
});
