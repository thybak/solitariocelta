@extends('layouts.app')

@section('titulo', 'Regístrate')
@section('cuerpo')
    <p>Para tu registro sólo necesitamos de un usuario, una contraseña y un email. Sin embargo, antes de poder hacer uso del sistema, un usuario administrador deberá habilitar tu cuenta de usuario.</p>
    <form>
        <fieldset>
            <label for="nombreUsuario">Nombre de usuario: </label>
            <input type="text" id="nombreUsuario" name="nombreUsuario" required />
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" required />
            <label for="password">Contraseña: </label>
            <input type="password" id="password" name="password" required />
            <label for="passwordConfirmation">Confirmación de la contraseña: </label>
            <input type="password" id="passwordConfirmation" name="passwordConfirmation" required />
            <button type="button" onclick="utils.registro()">¡Regístrate!</button>
        </fieldset>
    </form>
@endsection