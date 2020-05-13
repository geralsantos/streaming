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
    Route::group(['prefix' => 'streaming'], function () {
        Route::get('/salas', ['as' => 'salas', 'uses' => 'StreamingSalasController@index']);
        Route::post('guardar/', ['as' => 'guardar', 'uses' => 'StreamingSalasController@guardar']);
        Route::get('cargar/', ['as' => 'cargar', 'uses' => 'StreamingSalasController@cargarAll']);
        Route::post('iniciarStream/', ['as' => 'cargar', 'uses' => 'StreamingSalasController@iniciarStream']);
        Route::post('encriptarStream/', ['as' => 'cargar', 'uses' => 'StreamingSalasController@encriptarStream']);
        
        Route::get('online/{id}', ['as' => 'streaming.online', 'uses' => 'StreamingOnlineController@index']);
        Route::post('online/entrarStreaming/', ['as' => 'entrarStreaming', 'uses' => 'StreamingOnlineController@entrarStreaming']);
         
    });
});
