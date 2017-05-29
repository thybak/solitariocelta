@extends('admin.portada')
@section('titulo', 'Gestión de puntuaciones')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista se pueden gestionar las puntuaciones registradas en el sistema</p>
            <button type="button">Añadir nueva puntuación</button> <button type="button">Modificar registros</button>
            <table class="ui celled table" id="lineas">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Puntuación</th>
                        <th>Fecha</th>
                        <th>Actualizar</th>
                        <th>¿Eliminar?</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <script>
        utils.getPuntuaciones();
    </script>
@endsection