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
Route::fallback(function(){
    return response()->view('errors.404', [], 404);
})->middleware('pre.page');

Route::get('/login', 'MainController@viewLogin');
Route::post('/login', 'UsuarioController@login')->name('login');

Route::get('/recuperar', 'MainController@viewRecuperar');
Route::post('/recuperar', 'UsuarioController@recuperar')->name('recuperar');

Route::get('/logout', function(){
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});

//'auth','checkAuth','pre.page'
Route::group(['middleware' => ['auth','checkAuth','pre.page']], function () {
    Route::get('/change', 'MainController@viewChange');
    Route::get('/cambiarpass', 'MainController@viewCambiar');
    Route::post('/change', 'MainController@change')->name('change');

    Route::get('/', 'PuestoTrabajoController@index')->name('index');
    //Recursos Humanos
    Route::group(['prefix' => '/rrhh'], function () {
        Route::resource('/puestos', 'PuestoTrabajoController')->except(['show']);
        Route::resource('/trabajadores', 'TrabajadorController')->except(['show']);
    });
});
