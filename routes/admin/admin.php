<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/list', 'list');
    Route::post('/admin/add', 'add');
    Route::put('/admin/updatePassword', 'updatePassword');
    Route::put('/admin/resetPassword', 'resetPassword');
    Route::put('/admin/updateAdmin', 'updateAdmin');
    Route::put('/admin/edit', 'edit');
    Route::delete('/admin/delete', 'delete');
    Route::post('/admin/upAvatar', 'upAvatar');
});
