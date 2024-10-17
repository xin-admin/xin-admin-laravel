<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/user/getUserInfo', 'getUserInfo');
    Route::post('/user/refreshToken', 'refreshToken');
    Route::post('/user/logout', 'logout');
    Route::post('/user/upAvatar', 'upAvatar');
    Route::post('/user/setUserInfo', 'setUserInfo');
    Route::post('/user/setPassword', 'setPassword');
    Route::get('/user/getMoneyLog', 'getMoneyLog');
});
