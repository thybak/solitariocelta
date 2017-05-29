@extends('admin.portada')
@section('titulo', 'Gestión de usuarios')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista podrás editar la información de los usuarios registrados en el sistema</p>
            <button type="button" class="ui button" onclick="utils.mostrarModalRegistro('#modalRegistro', true);"><i
                        class="add icon"></i>Añadir
                nuevo usuario
            </button>
            <button type="button" onclick="utils.prepararModalUpdate('#modalRegistro', _id_, '/api/users/' + _id_)"
                    id="btnModificarTemplate" class="hdn"><i class="edit icon"></i> Editar
            </button>
            <button type="button"
                    onclick="utils.prepararModalDelete('#modalBorrado', _id_)"
                    id="btnEliminarTemplate" class="hdn"><i class="remove icon"></i> Eliminar
            </button>
            <table class="ui celled table" id="lineas">
                <thead>
                <tr>
                    <th>Nombre de usuario</th>
                    <th>Email</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Teléfono</th>
                    <th>Activo</th>
                    <th>¿Administrador?</th>
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
            Crear un nuevo usuario
        </div>
        <div class="header update">
            Actualizar usuario
        </div>
        <div class="content">
            <p class="new">Rellena la siguiente información para dar de alta a un nuevo usuario</p>
            <p class="update">Actualiza la información del usuario rellenando el siguiente formulario</p>
            <form class="ui form">
                <input type="hidden" value="0" id="id"/>
                <div class="field">
                    <label for="nombreUsuario">Nombre de usuario: </label>
                    <input type="text" id="nombreUsuario"/>
                </div>
                <div class="field">
                    <label for="password">Contraseña: </label>
                    <input type="password" id="password"/>
                </div>
                <div class="field">
                    <label for="email">Email: </label>
                    <input type="email" id="email"/>
                </div>
                <div class="field">
                    <label for="nombre">Nombre: </label>
                    <input type="text" id="nombre"/>
                </div>
                <div class="field">
                    <label for="nombre">Apellidos: </label>
                    <input type="text" id="apellidos"/>
                </div>
                <div class="field">
                    <label for="nombre">Teléfono: </label>
                    <input type="text" id="telefono"/>
                </div>
                <div class="update">
                    <label for="habilitado">Habilitado: </label>
                    <input type="checkbox" id="habilitado"/>
                    <label for="esAdmin">Administrador: </label>
                    <input type="checkbox" id="esAdmin"/>
                </div>
            </form>
        </div>
        <div class="actions">
            <button type="button" class="ui button new" onclick="gestionarUsuario(true)"><i class="checkmark icon"></i>
                Crear nuevo usuario
            </button>
            <button type="button" class="ui button update" onclick="gestionarUsuario(false)"><i
                        class="checkmark icon"></i> Actualizar usuario
            </button>
            <button type="button" class="ui deny button">Cancelar</button>
        </div>
    </div>
    <div class="ui modal" id="modalBorrado">
        <div class="header">
            Eliminar usuario
        </div>
        <div class="content">
            <p>¿Estás seguro de que quieres eliminar este usuario?</p>
        </div>
        <div class="actions">
            <button type="button" class="ui button"
                    onclick="utils.eliminarRegistro('/api/users/' + $('#id').val(), utils.getUsuarios)">
                <i class="checkmark icon"></i> Confirmar
            </button>
            <button type="button" class="ui deny button">Cancelar</button>
        </div>
    </div>
    <script>
        document.onload = utils.getUsuarios();
        function gestionarUsuario(nuevo) {
            var usuario = {
                nombreUsuario: $("#nombreUsuario").val(),
                password: $("#password").val(),
                email: $("#email").val(),
                nombre: $("#nombre").val(),
                apellidos: $("#apellidos").val(),
                telefono: $("#telefono").val()
            };
            var id = $("#id").val();
            if (!nuevo && id > 0) {
                if (usuario.password === "") {
                    delete usuario.password; // wa seguridad
                }
                usuario.habilitado = $("#habilitado").is(':checked') ? 1 : 0;
                usuario.esAdmin = $("#esAdmin").is(':checked') ? 1 : 0;
                utils.actualizarRegistro('/api/users/' + id, usuario, utils.getUsuarios);
            } else {
                utils.altaRegistro('/api/users', usuario, utils.getUsuarios);
            }
        }
    </script>
@endsection