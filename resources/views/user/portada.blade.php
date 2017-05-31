@extends('layouts.app')
@section('titulo', 'Portada usuario')
@section('menu')
    <div class="ui attached stackable menu">
        <a class="item" href="/user">
            <h1 class="ui header"><i class="game icon"></i> Solitario celta (TDW 16-17)</h1>
        </a>
        <a class="item" href="{!! url('/user/perfil') !!}">
            <i class="user icon"></i> Modificar perfil
        </a>
        <a class="item" href="{!! url('/user/puntuaciones') !!}">
            <i class="trophy icon"></i> Ver puntuaciones
        </a>
        <a class="item" href="{!! url('/user/juego') !!}">
            <i class="game icon"></i> Jugar
        </a>
        @if ($usuarioAuth -> esAdmin)
            <a class="item" href="{!! url('/admin') !!}">
                <i class="key icon"></i> Administración del sistema
            </a>
        @endif
        <div class="item">
            <i class="user icon"></i> Usuario: {!! $usuarioAuth->nombreUsuario !!}
        </div>
        <a class="item" onclick="utils.cerrarSesion()">
            <i class="sign out icon"></i> Cerrar sesión
        </a>
    </div>
@endsection
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>Bienvenid@ a SolitarioCelta @if ($usuarioAuth -> nombre !== "") {{', '.$usuarioAuth->nombre}} @endif</h2>
            <p>Desde esta vista podrás acceder a: </p>
            <ul>
                <li>Tu perfil</li>
                <li>Las puntuaciones que has registrado</li>
                <li>Al juego del solitario celta</li>
            </ul>
        </div>
    </div>
@endsection