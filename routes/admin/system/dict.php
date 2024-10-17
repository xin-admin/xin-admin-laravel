<?php

use App\Http\Controllers\Admin\System\DictController;
use Illuminate\Support\Facades\Route;

Route::controller(DictController::class)->group(function () {
    Route::get('/system/dict/list', 'list');
    Route::post('/system/dict/add', 'add');
    Route::put('/system/dict/edit', 'edit');
    Route::delete('/system/dict/delete', 'delete');
});
