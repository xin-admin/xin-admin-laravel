<?php

use App\Http\Controllers\Admin\System\MonitorController;
use Illuminate\Support\Facades\Route;

Route::controller(MonitorController::class)->group(function () {
    Route::get('/system/monitor/list', 'list');
});
