<?php

use Illuminate\Support\Facades\Route;

Route::get('/doc', function () {
    return view('swagger');
});
