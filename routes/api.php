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

Route::post('/login', 'LoginController@login');

Route::get('/users', 'UsuarioController@obtenerTodos');

Route::post('/users/', 'UsuarioController@crear');

Route::get('/users/{usuario}', 'UsuarioController@obtener');

Route::delete('/users/{usuario}', 'UsuarioController@borrar');

Route::put('/users/{usuario}', 'UsuarioController@actualizar');