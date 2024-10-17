<?php

use App\Http\Controllers\Admin\User\UserMoneyLogController;
use Illuminate\Support\Facades\Route;

Route::controller(UserMoneyLogController::class)->group(function () {
    Route::get('/user/userMoneyLog/list', 'list');
});
