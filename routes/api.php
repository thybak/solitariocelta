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
const RUTA_USUARIOS = '/users';
const RUTA_RESULTADOS = '/results';
const RUTA_PARTIDAS = '/matches';

Route::post('/login', 'LoginController@login');
Route::post(RUTA_USUARIOS, 'UsuarioController@crear');
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get(RUTA_USUARIOS, 'UsuarioController@obtenerTodos');
    Route::get(RUTA_USUARIOS.'/deshabilitados', 'UsuarioController@obtenerTodosLosDeshabilitados');
    Route::get(RUTA_USUARIOS.'/{usuario}', 'UsuarioController@obtener');
    Route::delete(RUTA_USUARIOS.'/{usuario}', 'UsuarioController@borrar');
    Route::put(RUTA_USUARIOS.'/{usuario}', 'UsuarioController@actualizar');
    Route::get(RUTA_RESULTADOS, 'ResultadoController@obtenerTodos');
    Route::get(RUTA_RESULTADOS.'/{resultado}', 'ResultadoController@obtener')->where('resultado', '[0-9]+');
    Route::get(RUTA_RESULTADOS.'/user/{usuario}', 'ResultadoController@obtenerDeUsuario');
    Route::get(RUTA_RESULTADOS.'/user/{usuario}/top5', 'ResultadoController@obtenerTop5DeUsuario');
    Route::post(RUTA_RESULTADOS, 'ResultadoController@crear');
    Route::put(RUTA_RESULTADOS.'/{resultado}', 'ResultadoController@actualizar');
    Route::delete(RUTA_RESULTADOS.'/{resultado}', 'ResultadoController@borrar');
    Route::post(RUTA_RESULTADOS.'/top10', 'ResultadoController@obtenerTop10');
    Route::get(RUTA_PARTIDAS, 'PartidaSinFinalizarController@obtenerTodos');
    Route::get(RUTA_PARTIDAS.'/{partida}', 'PartidaSinFinalizarController@obtener');
    Route::get(RUTA_PARTIDAS.'/user/{usuario}', 'PartidaSinFinalizarController@obtenerDeUsuario');
    Route::post(RUTA_PARTIDAS, 'PartidaSinFinalizarController@crear');
    Route::put(RUTA_PARTIDAS.'/{partida}', 'PartidaSinFinalizarController@actualizar');
    Route::delete(RUTA_PARTIDAS.'/{partida}', 'PartidaSinFinalizarController@borrar');
});