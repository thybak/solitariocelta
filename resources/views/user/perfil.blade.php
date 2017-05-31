@extends('user.portada')
@section('titulo', 'Modificar perfil')
@section('cuerpo')
    <div class="container">
        <div class="ui segment">
            <h2>@yield('titulo')</h2>
            <p>Desde esta vista podrás editar los datos personales que almacenas en SolitarioCelta, así como las
                credenciales de
                acceso al sistema</p>
            <form class="ui form">
                <input type="hidden" id="id" value="{{$usuarioAuth->id}}"/>
                <div class="field">
                    <label for="nombreUsuario">Nombre de usuario:</label>
                    <input type="text" disabled id="nombreUsuario" value="{{$usuarioAuth->nombreUsuario}}"/>
                </div>
                <div class="field">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" value=""/>
                </div>
                <div class="field">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" value="{{$usuarioAuth->nombre}}"/>
                </div>
                <div class="field">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" value="{{$usuarioAuth->apellidos}}"/>
                </div>
                <div class="field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" value="{{$usuarioAuth->email}}" required/>
                </div>
                <div class="field">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" maxlength="9" size="9" value="{{$usuarioAuth->telefono}}"/>
                </div>
                <button class="ui button" type="button" onclick="actualizarUsuario()"><i class="refresh icon"></i>
                    Actualizar perfil
                </button>
            </form>
        </div>
    </div>
    <script>
        function actualizarUsuario() {
            if ($('form').valid()) {
                var usuario = {
                    nombre: $("#nombre").val(),
                    apellidos: $("#apellidos").val(),
                    email: $("#email").val(),
                    telefono: $("#telefono").val()
                };
                var password = $("#password").val();
                if (password !== "") {
                    usuario.password = password;
                }
                utils.actualizarRegistro('/api/users/' + $("#id").val(), usuario);
            } else {
                utils.mostrarAlerta('Revisa que has facilitado correctamente el email');
            }
        }

    </script>
@endsection

