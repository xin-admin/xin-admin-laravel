<?php

use App\Http\Controllers\Admin\User\UserGroupController;
use Illuminate\Support\Facades\Route;

Route::controller(UserGroupController::class)->group(function () {
    Route::get('/user/group/list', 'list');
    Route::post('/user/group/add', 'add');
    Route::post('/user/group/setGroupRule', 'setGroupRule');
    Route::put('/user/group/edit', 'edit');
    Route::delete('/user/group/delete', 'delete');
});
