<?php


Auth::routes();
Route::group([

    'prefix' => 'auth'

], function () {

    Route::post('login', 'AuthController@login');


    Route::group([
        'middleware' => 'auth:api',
    ] , function (){

        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
        Route::post('save', 'AuthController@save');


        Route::apiResource('brand', 'BrandController');

        Route::apiResource('user', 'UserController');

    });



});


