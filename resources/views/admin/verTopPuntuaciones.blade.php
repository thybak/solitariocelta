@extends('admin.portada')
@section('titulo', 'Ranking de puntuaciones')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>En esta sección podrás ver el ranking de las mejores 10 puntuaciones registradas en la base de datos en
                el periodo comprendido entre las fechas facilitadas por el usuario. En caso contrario, podrás ver todas
                las puntuaciones de un usuario en concreto.</p>
            <div class="ui divider"></div>
            <form class="ui form">
                <div class="ui grid">
                    <div class="eight wide column">
                        <h3>Por rango de fechas</h3>
                        <div class="ui divider"></div>
                        <div class="field">
                            <label for="txtFechaInicio">Fecha de inicio (mm/dd/yyyy): </label>
                            <input type="text" id="txtFechaInicio"/>
                        </div>
                        <div class="field">
                            <label for="txtFechaFin">Fecha de fin (mm/dd/yyyy): </label>
                            <input type="text" id="txtFechaFin"/>
                        </div>
                        <button type="button" class="ui button" onclick="utils.getTop10()">Generar ranking</button>
                    </div>
                    <div class="eight wide column">
                        <h3>Por usuario</h3>
                        <div class="ui divider"></div>
                        <div class="field">
                            <label for="ddlUsuario">Usuario:</label>
                            <select id="ddlUsuario">
                                <option>Selecciona un usuario...</option>
                            </select>
                        </div>
                        <button type="button" class="ui button"
                                onclick="utils.getPuntuacionesDeUsuario($('#ddlUsuario').val())">Ver
                            puntuaciones
                        </button>
                    </div>
                </div>
            </form>
            <table id="lineas" class="ui celled table hdn">
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
    <script>
        utils.crearSelectUsuarios('#ddlUsuario');
        $('#txtFechaInicio').datepicker();
        $('#txtFechaFin').datepicker();
    </script>
@endsection