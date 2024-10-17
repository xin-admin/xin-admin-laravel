<?php


use App\Http\Controllers\Admin\User\UserRuleController;
use Illuminate\Support\Facades\Route;

Route::controller(UserRuleController::class)->group(function () {
    Route::get('/user/rule/list', 'list');
    Route::post('/user/rule/add', 'add');
    Route::put('/user/rule/edit', 'edit');
    Route::delete('/user/rule/delete', 'delete');
});
