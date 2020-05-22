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

Route::group(['prefix' => 'registrar'], function () {
    Route::get('/usuarios', ['as' => 'usuarios', 'uses' => 'MantenimientoUsuarioController@index']);
    Route::get('/usuarios/cargar', ['as' => 'usuarios', 'uses' => 'MantenimientoUsuarioController@cargarAll']);
    Route::post('/usuarios/buscar/', ['as' => 'usuarios.buscar', 'uses' => 'MantenimientoUsuarioController@buscar']);
        
    Route::post('/usuarios/verificar/', ['as' => 'usuarios.verificar', 'uses' => 'MantenimientoUsuarioController@verificar']);
    Route::patch('/usuarios/editar/', ['as' => 'usuarios.editar', 'uses' => 'MantenimientoUsuarioController@editar']);
    Route::post('/usuarios/eliminar/', ['as' => 'usuarios.eliminar', 'uses' => 'MantenimientoUsuarioController@eliminar']);
    Route::post('/usuarios/guardar/', ['as' => 'usuarios.guardar', 'uses' => 'MantenimientoUsuarioController@guardar']);
    Route::post('/usuarios/perfil/', ['as' => 'usuarios.editarPerfil', 'uses' => 'MantenimientoUsuarioController@editarPerfil']);
    Route::get('/usuarios/perfil/', ['as' => 'usuarios.editarPerfil', 'uses' => 'MantenimientoUsuarioController@index']);
    Route::post('/usuarios/clave/', ['as' => 'usuarios.cambiarClave', 'uses' => 'MantenimientoUsuarioController@cambiarClave']);
    Route::post('/usuarios/reset/', ['as' => 'usuarios.resetClave', 'uses' => 'MantenimientoUsuarioController@resetClave']);
    Route::get('/perfiles/cargar', ['as' => 'perfiles.cargar', 'uses' => 'MantenimientoPerfilController@cargarAll']);

});
