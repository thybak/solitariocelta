@extends('layouts.app')

@section('titulo', 'Inicia sesión')
@section('cuerpo')
    <div class="container">
        <div class="ui grid">
            <div class="five wide column"></div>
            <div class="six wide column">
                <h1 class="pdn-top-25p txt-center">¡Bienvenido a SolitarioCelta!</h1>
                <div class="ui divider"></div>
                <div class="ui segment">
                    <form class="ui form">
                        <h2>@yield('titulo')</h2>
                        <div class="ui divider"></div>
                        <div class="field">
                            <label for="nombreUsuario">Nombre de usuario: </label>
                            <input type="text" id="nombreUsuario" name="nombreUsuario" required/>
                        </div>
                        <div class="field">
                            <label for="password">Contraseña: </label>
                            <input type="password" id="password" name="password" required/>
                        </div>
                        <button type="button" class="ui button" onclick="utils.login()"><i class="sign in icon"></i>
                            Iniciar sesión
                        </button>
                        <p>¿Aún sin cuenta? <a href="{!! url('/signup') !!}">Regístrate</a>
                    </form>
                </div>
            </div>
            <div class="five wide column"></div>
        </div>
    </div>
@endsection