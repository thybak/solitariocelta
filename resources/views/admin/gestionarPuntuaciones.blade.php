@extends('admin.portada')
@section('titulo', 'Gestión de puntuaciones')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista se pueden gestionar las puntuaciones registradas en el sistema</p>
            <button type="button" class="ui button" onclick="utils.mostrarModalRegistro('#modalRegistro', true);"><i
                        class="add icon"></i>Añadir
                nueva puntuación
            </button>
            <button type="button" onclick="utils.prepararModalUpdate('#modalRegistro', _id_, '/api/results/' + _id_)"
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
                    <th>Puntuación</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="ui modal" id="modalRegistro">
        <div class="header new">
            Crear una nueva puntuación
        </div>
        <div class="header update">
            Actualizar puntuación
        </div>
        <div class="content">
            <p class="new">Rellena la siguiente información para dar de alta una nueva puntuación</p>
            <p class="update">Actualiza la información de la puntuación rellenando el siguiente formulario</p>
            <form class="ui form">
                <input type="hidden" value="0" id="id"/>
                <div class="field">
                    <label for="puntos">Puntos: </label>
                    <input type="number" id="puntos"/>
                </div>
                <div class="field new">
                    <label for="usuarioId">Identificador de usuario: </label>
                    <select id="usuarioId">
                        <option>Selecciona un usuario</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="actions">
            <button type="button" class="ui button new" onclick="gestionarPuntuacion(true)"><i
                        class="checkmark icon"></i>
                Crear nueva partida
            </button>
            <button type="button" class="ui button update" onclick="gestionarPuntuacion(false)"><i
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
                    onclick="utils.eliminarRegistro('/api/results/' + $('#id').val(), utils.getPuntuaciones)">
                <i class="checkmark icon"></i> Confirmar
            </button>
            <button type="button" class="ui deny button">Cancelar</button>
        </div>
    </div>
    <script>
        function cargarVistaPuntuaciones(){
            utils.getPuntuaciones();
            utils.crearSelectUsuarios('#usuarioId');
        }
        function gestionarPuntuacion(nuevo) {
            var resultado = {
                puntos: $("#puntos").val()
            };
            var id = $("#id").val();
            if (!nuevo && id > 0) {
                utils.actualizarRegistro('/api/results/' + id, resultado, utils.getPuntuaciones);
            } else {
                resultado.usuarioId = $("#usuarioId").val();
                utils.altaRegistro('/api/results', resultado, utils.getPuntuaciones);
            }
        }
        document.onload = cargarVistaPuntuaciones();
    </script>
@endsection