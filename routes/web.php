<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'SeccionController@getSeccionesByModel');

//'auth','checkAuth','pre.page'
Route::group(['middleware' => []], function () {
    Route::get('/change', 'MainController@viewChange');
    Route::get('/cambiarpass', 'MainController@viewCambiar');
    Route::post('/change', 'MainController@change')->name('change');

    Route::get('/', 'MainController@index')->name('index');
    //Recursos Humanos
    Route::group(['prefix' => '/rrhh'], function () {
        Route::resource('/puestos', 'PuestoTrabajoController')->except(['show']);
        Route::resource('/trabajadores', 'TrabajadorController')->except(['show']);
    });
});
