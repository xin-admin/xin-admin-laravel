<?php

use App\Http\Controllers\Admin\AdminRuleController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminRuleController::class)->group(function () {
    Route::get('/adminRule/list', 'list');
    Route::post('/adminRule/add', 'add');
    Route::get('/adminRule/getRulePid', 'getRulePid');
    Route::put('/adminRule/edit', 'edit');
    Route::delete('/adminRule/delete', 'delete');
});
