<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Squadron\Base\Http\Controllers\Api')
    ->prefix('api')
    ->middleware('api')
    ->group(function () {
        // ping
        Route::get('/ping', 'PingController@ping');
    });
