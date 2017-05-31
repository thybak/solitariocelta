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

use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\Input;
use \App\Models\Utils;

Route::get('/', function () {
    return view('login');
});
Route::get('/signup', function() {
    return view('registro');
});
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/admin', function(Request $request) {
        return Utils::generarVistaUsuario('admin.portada', $request);
    });
    Route::get('/admin/gestionarInactivos', function(Request $request){
        return Utils::generarVistaUsuario('admin.gestionarInactivos', $request);
    });
    Route::get('/admin/gestionarUsuarios', function(Request $request){
        return Utils::generarVistaUsuario('admin.gestionarUsuarios', $request);
    });
    Route::get('/admin/gestionarPartidas', function(Request $request){
        return Utils::generarVistaUsuario('admin.gestionarPartidas', $request);
    });
    Route::get('/admin/gestionarPuntuaciones', function(Request $request){
        return Utils::generarVistaUsuario('admin.gestionarPuntuaciones', $request);
    });
    Route::get('/admin/verTopPuntuaciones', function(Request $request){
        return Utils::generarVistaUsuario('admin.verTopPuntuaciones', $request);
    });
    Route::get('/user', function (Request $request) {
        return Utils::generarVistaUsuario('user.portada', $request);
    });
    Route::get('/user/perfil', function(Request $request) {
        return Utils::generarVistaUsuario('user.perfil', $request);
    });
    Route::get('/user/puntuaciones', function(Request $request) {
        return Utils::generarVistaUsuario('user.puntuaciones', $request);
    });
    Route::get('/user/juego', function(Request $request) {
        return Utils::generarVistaUsuario('user.juego', $request);
    });
    Route::get('/login', function () {
        Cookie::queue('token', Input::get('token'), 0, "/", false);
        return redirect('/user');
    });
});