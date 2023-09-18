<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::group(['middleware' => 'validate-api-key','apiLog'], function () {
        Route::group(['prefix' => 'verify'], function () {
            Route::post('/', 'KYCController@verify');
        });
    });
});
