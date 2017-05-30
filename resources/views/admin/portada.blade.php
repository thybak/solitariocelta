@extends('layouts.app')
@section ('titulo', 'Portada de administración')
@section('menu')
    <div class="ui attached stackable menu">
        <a class="item" href="/admin">
            <h1 class="ui header"><i class="game icon"></i> Solitario celta (TDW 16-17) - Administración</h1>
        </a>
        <a class="item" href="{!! url('/admin/gestionarInactivos') !!}">
            <i class="unlock icon"></i> Gestión usuarios inactivos
        </a>
        <a class="item" href="{!! url('/admin/gestionarUsuarios') !!}">
            <i class="user icon"></i> Gestión usuarios
        </a>
        <a class="item" href="{!! url('/admin/gestionarPartidas') !!}">
            <i class="archive icon"></i> Gestión partidas
        </a>
        <a class="item" href="{!! url('/admin/gestionarPuntuaciones') !!}">
            <i class="archive icon"></i> Gestión puntuaciones
        </a>
        <a class="item" href="{!! url('/admin/verTopPuntuaciones') !!}">
            <i class="trophy icon"></i> Ver ranking de puntuaciones
        </a>
        <div class="item">
            <i class="user icon"></i> Usuario: {!! $usuarioAuth->nombreUsuario !!}
        </div>
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