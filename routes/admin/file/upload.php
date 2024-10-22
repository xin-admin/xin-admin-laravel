<?php

use App\Http\Controllers\Admin\File\UploadController;
use Illuminate\Support\Facades\Route;

Route::controller(UploadController::class)->group(function () {
    Route::post('/file/upload/image', 'image');
    Route::post('/file/upload/video', 'video');
    Route::post('/file/upload/zip', 'zip');
    Route::post('/file/upload/mp3', 'mp3');
    Route::post('/file/upload/annex', 'annex');
});
