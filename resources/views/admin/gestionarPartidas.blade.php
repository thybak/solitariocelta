@extends('admin.portada')
@section('titulo', 'Gestión de partidas')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista podrás gestionar las partidas guardadas por los usuarios</p>
            <button type="button">Añadir nueva partida</button> <button type="button">Modificar registros</button>
            <table class="ui celled table" id="lineas">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Estado de la partida</th>
                    <th>Fecha de creación</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
        </div>
    </div>
    <script>
        document.onload = utils.getPartidas();
    </script>
@endsection