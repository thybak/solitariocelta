@extends('admin.portada')
@section('titulo', 'Gestión de usuarios inactivos')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde aquí podrás llevar la gestión de peticionarios del sistema, bastará con sólo accionar el botón de
                habilitar.</p>
            <table class="ui celled table" id="lineas">
                <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Fecha alta</th>
                    <th>¿Habilitar?</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <button type="button" onclick="utils.activarDesactivarUsuario(_id_, 1, utils.ocultarCelda, this)" id="btnActivarTemplate" class="hdn"><i class="checkmark icon"></i> Habilitar</button>
    <script>
        document.onload = utils.getInactivos();
    </script>
@endsection