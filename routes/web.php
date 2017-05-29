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
        return Utils::generarVistaAdministrador('admin.portada', $request);
    });
    Route::get('/admin/gestionarInactivos', function(Request $request){
        return Utils::generarVistaAdministrador('admin.gestionarInactivos', $request);
    });
    Route::get('/admin/gestionarUsuarios', function(Request $request){
        return Utils::generarVistaAdministrador('admin.gestionarUsuarios', $request);
    });
    Route::get('/admin/gestionarPartidas', function(Request $request){
        return Utils::generarVistaAdministrador('admin.gestionarPartidas', $request);
    });
    Route::get('/admin/gestionarPuntuaciones', function(Request $request){
        return Utils::generarVistaAdministrador('admin.gestionarPuntuaciones', $request);
    });
    Route::get('/admin/verTopPuntuaciones', function(Request $request){
        return Utils::generarVistaAdministrador('admin.verTopPuntuaciones', $request);
    });
    Route::get('/user', function (Request $request) {
        return view('user.portada')->with('esAdmin', Utils::esTokenDeUsuarioAdmin($request->cookie('token')));
    });
    Route::get('/user/perfil', function() {
        return view('user.perfil');
    });
    Route::get('/login', function (Request $request) {
        return redirect('/user')->cookie('token', Input::get('token'), 0, "/", false);
    });
});