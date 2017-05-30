@extends('admin.portada')
@section('titulo', 'Gestión de partidas')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista podrás gestionar las partidas guardadas por los usuarios</p>
            <button type="button" class="ui button" onclick="utils.mostrarModalRegistro('#modalRegistro', true);"><i
                        class="add icon"></i>Añadir
                nueva partida
            </button>
            <button type="button" onclick="utils.prepararModalUpdate('#modalRegistro', _id_, '/api/matches/' + _id_)"
                    id="btnModificarTemplate" class="hdn"><i class="edit icon"></i> Editar
            </button>
            <button type="button"
                    onclick="utils.prepararModalDelete('#modalBorrado', _id_)"
                    id="btnEliminarTemplate" class="hdn"><i class="remove icon"></i> Eliminar
            </button>
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
    <div class="ui modal" id="modalRegistro">
        <div class="header new">
            Crear una nueva partida
        </div>
        <div class="header update">
            Actualizar partida
        </div>
        <div class="content">
            <p class="new">Rellena la siguiente información para dar de alta a una nueva partida</p>
            <p class="update">Actualiza la información de la partida rellenando el siguiente formulario</p>
            <form class="ui form">
                <input type="hidden" value="0" id="id"/>
                <div class="field">
                    <label for="estadoJson">Estado de la partida: </label>
                    <textarea id="estadoJson"></textarea>
                </div>
                <div class="field new">
                    <label for="usuarioId">Identificador de usuario: </label>
                    <input type="text" id="usuarioId" />
                </div>
            </form>
        </div>
        <div class="actions">
            <button type="button" class="ui button new" onclick="gestionarPartida(true)"><i class="checkmark icon"></i>
                Crear nueva partida
            </button>
            <button type="button" class="ui button update" onclick="gestionarPartida(false)"><i
                        class="checkmark icon"></i> Actualizar partida
            </button>
            <button type="button" class="ui deny button">Cancelar</button>
        </div>
    </div>
    <div class="ui modal" id="modalBorrado">
        <div class="header">
            Eliminar partida
        </div>
        <div class="content">
            <p>¿Estás seguro de que quieres eliminar esta partida?</p>
        </div>
        <div class="actions">
            <button type="button" class="ui button"
                    onclick="utils.eliminarRegistro('/api/matches/' + $('#id').val(), utils.getPartidas)">
                <i class="checkmark icon"></i> Confirmar
            </button>
            <button type="button" class="ui deny button">Cancelar</button>
        </div>
    </div>
    <script>
        document.onload = utils.getPartidas();
        function gestionarPartida(nuevo) {
            var partida = {
                estadoJson: $("#estadoJson").val()
            };
            var id = $("#id").val();
            if (!nuevo && id > 0) {
                utils.actualizarRegistro('/api/matches/' + id, partida, utils.getPartidas);
            } else {
                partida.usuarioId = $("#usuarioId").val();
                utils.altaRegistro('/api/matches', partida, utils.getPartidas);
            }
        }
    </script>
@endsection