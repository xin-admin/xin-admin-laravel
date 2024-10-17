<?php

use App\Http\Controllers\Admin\System\SettingController;
use Illuminate\Support\Facades\Route;

Route::controller(SettingController::class)->group(function () {
    Route::get('/system/setting/list', 'list');
    Route::post('/system/setting/add', 'add');
    Route::post('/system/setting/saveSetting', 'saveSetting');
    Route::post('/system/setting/addGroup', 'addGroup');
    Route::put('/system/setting/edit', 'edit');
    Route::delete('/system/setting/delete', 'delete');
    Route::get('/system/setting/querySettingGroup', 'querySettingGroup');
});
