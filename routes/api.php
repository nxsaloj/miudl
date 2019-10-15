<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api','cors'],'prefix' => 'v1'], function() {
    Route::group(['prefix' => '/administracion'], function() {
        Route::group(['prefix' => '/usuarios'], function() {
            Route::get('/', 'UsuarioController@getUsuariosAPI');
            Route::delete('/{usuario}', 'UsuarioController@destroy');
            Route::post('/desactivar/{usuario}', 'UsuarioController@desactivar');
            Route::post('/reactivar/{usuario}', 'UsuarioController@reactivar');
            Route::post('/reasignar/{usuario}', 'UsuarioController@reasignar');
        });
    });
    Route::group(['prefix' => '/rrhh'], function () {
        Route::group(['prefix' => '/trabajadores'], function() {
            Route::get('/', 'TrabajadorController@getTrabajadoresAPI');
            Route::delete('/{trabajador}', 'TrabajadorController@destroy');
        });
        Route::group(['prefix' => '/puestos'], function () {
            Route::get('/', 'PuestoTrabajoController@getPuestosTrabajoAPI');
            Route::delete('/{puesto}', 'PuestoTrabajoController@destroy');
        });
    });
    Route::group(['prefix' => '/educacion'], function (){
        Route::group(['prefix' => '/centrosuniversitarios'], function ()
        {
            Route::get('/', 'CentroUniversitarioController@getCentrosUniversitariosAPI');
            Route::delete('/{centrouniversitario}', 'CentroUniversitarioController@destroy');
        });
        Route::group(['prefix' => '/facultades'], function ()
        {
            Route::get('/', 'FacultadController@getFacultadesAPI');
            Route::delete('/{facultad}', 'FacultadController@destroy');
        });
    });
});
