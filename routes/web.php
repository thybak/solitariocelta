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

Route::get('/', function () {
    $usuario = new \App\models\Usuario;
    $usuario->nombreUsuario = "thybak";
    $usuario->clavePaso = "thybak";
    $usuario->habilitado = true;
    $usuario->esAdmin = true;
    $usuario->email = "davidm.martin@outlook.com";
    $usuario->save();
    return view('welcome');
});
