@extends('layouts.app')
@section('titulo', 'Regístrate en SolitarioCelta')
@section('cuerpo')
    <div class="container">
        <div class="ui grid">
            <div class="five wide column"></div>
            <div class="six wide column">
                <h1 class="txt-center pdn-top-25p">@yield('titulo')</h1>
                <div class="ui divider"></div>
                <div class="ui segment">
                    <div class="ui message"><i class="info icon"></i> Para tu registro sólo necesitamos de un usuario, una contraseña y un email.
                        Sin embargo, antes de poder hacer uso del sistema, un usuario administrador deberá habilitar tu
                        cuenta de usuario.
                    </div>
                    <form class="ui form">
                        <div class="field">
                            <label for="nombreUsuario">Nombre de usuario: </label>
                            <input type="text" id="nombreUsuario" name="nombreUsuario" required/>
                        </div>
                        <div class="field">
                            <label for="email">Email: </label>
                            <input type="email" id="email" name="email" required/>
                        </div>
                        <div class="field">
                            <label for="password">Contraseña: </label>
                            <input type="password" id="password" name="password" required/>
                        </div>
                        <div class="field">
                            <label for="passwordConfirmation">Confirmación de la contraseña: </label>
                            <input type="password" id="passwordConfirmation" name="passwordConfirmation" required/>
                        </div>
                        <a class="ui button" href="/"><i class="arrow left icon"></i> Atrás</a>
                        <button type="button" class="ui button" onclick="utils.registro()"><i class="add user icon"></i> ¡Regístrate!</button>
                    </form>
                </div>
            </div>
            <div class="five wide column"></div>
        </div>

    </div>
@endsection