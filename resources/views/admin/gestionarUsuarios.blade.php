@extends('admin.portada')
@section('titulo', 'Gestión de usuarios')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista podrás editar la información de los usuarios registrados en el sistema</p>
            <button type="button">Añadir nuevo usuario</button> <button type="button">Modificar registros</button>
            <table class="ui celled table" id="lineas">
                <thead>
                    <tr>
                        <th>Nombre de usuario</th>
                        <th>Email</th>
                        <th>Establecer contraseña</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Activo</th>
                        <th>¿Administrador?</th>
                        <th>Actualizar</th>
                        <th>¿Eliminar?</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        utils.getUsuarios();
    </script>
@endsection