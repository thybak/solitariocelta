@extends('layouts.app')

@section('titulo', 'Inicia sesión')
@section('cuerpo')
    <form>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label for="nombreUsuario">Nombre de usuario: </label>
        <input type="text" id="nombreUsuario" name="nombreUsuario" required />
        <label for="password">Contraseña: </label>
        <input type="password" id="password" name="password" required />
        <button type="button" onclick="utils.login()">Iniciar sesión</button>
    </form>
@endsection