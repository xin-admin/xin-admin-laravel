<?php

use App\Http\Controllers\Admin\System\DictItemController;
use Illuminate\Support\Facades\Route;

Route::controller(DictItemController::class)->group(function () {
    Route::get('/system/dictItem/list', 'list');
    Route::post('/system/dictItem/add', 'add');
    Route::put('/system/dictItem/edit', 'edit');
    Route::delete('/system/dictItem/delete', 'delete');
    Route::get('/system/dictItem/dictList', 'dictList');
});
