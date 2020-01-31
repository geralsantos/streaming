<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => 'auth'],function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', ['as' => 'reports', 'uses' => 'HomeController@index']);
        Route::post('guardar/', ['as' => 'guardar', 'uses' => 'RegistroSalaController@guardar']);
        Route::get('cargar/', ['as' => 'cargar', 'uses' => 'RegistroSalaController@cargarAll']);
    });
});
