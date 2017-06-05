@extends('layouts.app')
@section ('titulo', 'Portada de administración')
@section('menu')
    <div class="ui six item menu">
        <a class="item" href="/admin">
            <h1 class="ui header"><i class="key icon"></i> Administración</h1>
        </a>
        <div class="ui simple dropdown item">
            Usuarios
            <i class="dropdown icon"></i>
            <div class="menu">
                <a class="item" href="{!! url('/admin/gestionarInactivos') !!}">
                    <i class="unlock icon"></i> Gestión usuarios inactivos
                </a>
                <a class="item" href="{!! url('/admin/gestionarUsuarios') !!}">
                    <i class="user icon"></i> Gestión usuarios
                </a>
            </div>
        </div>
        <a class="item" href="{!! url('/admin/gestionarPartidas') !!}">
            <i class="archive icon"></i> Gestión partidas
        </a>
        <div class="ui simple dropdown item">
            Puntuaciones
            <i class="dropdown icon"></i>
            <div class="menu">
                <a class="item" href="{!! url('/admin/gestionarPuntuaciones') !!}">
                    <i class="archive icon"></i> Gestión puntuaciones
                </a>
                <a class="item" href="{!! url('/admin/verTopPuntuaciones') !!}">
                    <i class="trophy icon"></i> Ver ranking de puntuaciones
                </a>
            </div>
        </div>
        <a class="item" href="{!! url('/user') !!}">
            <i class="user icon"></i> Usuario: {!! $usuarioAuth->nombreUsuario !!}
        </a>
        <a class="item" onclick="utils.cerrarSesion()">
            <i class="sign out icon"></i> Cerrar sesión
        </a>
    </div>
@endsection
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>¡Bienvenido al área de administración!</h2>
            <p>Desde este área serás capaz de administrar a través del menú:</p>
            <ul>
                <li>Activación de usuarios</li>
                <li>Usuarios</li>
                <li>Partidas</li>
                <li>Puntuaciones</li>
                <li>Rankings de puntuaciones</li>
            </ul>
        </div>
    </div>
@endsection