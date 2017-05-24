@extends('layouts.app')
@section ('titulo', 'Portada de administración')
@section('menu')
    <div class="ui attached stackable menu">
        <a class="item" href="/admin">
            <h1 class="ui header"><i class="game icon"></i> Solitario celta (TDW 16-17) - Administración</h1>
        </a>
        <a class="item" href="{!! url('/admin/gestionarInactivos') !!}">
            <i class="move icon"></i> Gestionar usuarios inactivos
        </a>
        <a class="item" href="{!! url('/admin/gestionarUsuarios') !!}">
            <i class="play icon"></i> Gestión usuarios
        </a>
        <a class="item" href="{!! url('/admin/gestionarPartidas') !!}">
            <i class="save icon"></i> Gestionar partidas
        </a>
        <a class="item" href="{!! url('/admin/gestionarPuntuaciones') !!}">
            <i class="archive icon"></i> Gestionar puntuaciones
        </a>
        <a class="item" href="{!! url('/admin/verTopPuntuaciones') !!}">
            <i class="archive icon"></i> Ver ranking de puntuaciones
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
        </div>
    </div>
@endsection