<?php


use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/user/user/list', 'list');
    Route::post('/user/user/recharge', 'recharge');
    Route::put('/user/user/resetPassword', 'resetPassword');
});
