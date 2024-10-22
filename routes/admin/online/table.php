<?php

use App\Http\Controllers\Admin\Online\OnlineTableController;
use Illuminate\Support\Facades\Route;

Route::controller(OnlineTableController::class)->group(function () {
    Route::get('/online/online_table/list', 'list');
    Route::post('/online/online_table/add', 'add');
    Route::put('/online/online_table/edit', 'edit');
    Route::delete('/online/online_table/delete', 'delete');
    Route::post('/online/online_table/crud', 'crud');
});
