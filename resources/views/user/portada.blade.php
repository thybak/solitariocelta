@extends('layouts.app')
@section('titulo', 'Portada usuario')
@section('cuerpo')
    <ul>
        <li>
            Comenzar juego
        </li>
        <li>
            <a href="{!! url('/user/perfil') !!}">Ver perfil</a>
        </li>
        @if ($esAdmin)
            <li>
                <a href="{!! url('/admin') !!}">Administración</a>
            </li>
        @endif
    </ul>
@endsection