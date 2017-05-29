@extends('admin.portada')
@section('titulo', 'Ranking de puntuaciones')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>En esta sección podrás ver el ranking de las mejores 10 puntuaciones en la base de datos</p>
            <label for="txtFechaInicio">Fecha de inicio: </label>
            <input type="text" id="txtFechaInicio" />
            <label for="txtFechaFin">Fecha de fin: </label>
            <input type="text" id="txtFechaFin" />
            <button type="button" onclick="utils.getTop10()">Generar ranking</button>
            <table id="lineas" class="ui celled table">
            <thead>
                <tr>
                    <th>Puntos</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody></tbody>
            </table>
        </div>
    </div>
@endsection