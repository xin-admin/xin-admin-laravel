<?php

use App\Http\Controllers\Admin\File\GroupController;
use Illuminate\Support\Facades\Route;

Route::controller(GroupController::class)->group(function () {
    Route::get('/file/group/list', 'list');
});
