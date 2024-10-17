<?php

use App\Http\Controllers\Admin\AdminGroupController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminGroupController::class)->group(function () {
    Route::get('/adminGroup/list', 'list');
    Route::post('/adminGroup/add', 'add');
    Route::post('/adminGroup/setGroupRule', 'setGroupRule');
    Route::put('/adminGroup/edit', 'edit');
    Route::delete('/adminGroup/delete', 'delete');
});
