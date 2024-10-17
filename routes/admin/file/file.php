<?php

use App\Http\Controllers\Admin\File\FileController;
use Illuminate\Support\Facades\Route;

Route::controller(FileController::class)->group(function () {
    Route::get('/file/file/list', 'list');
    Route::delete('/file/file/delete', 'delete');
});
