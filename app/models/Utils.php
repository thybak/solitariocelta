<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 17/05/2017
 * Time: 17:15
 */

namespace App\Models;


class Utils
{
    const ERROR_500 = ['code' => 500, 'message' => 'Error interno, no se ha podido procesar la petición'];
    const ERROR_404 = ['code' => 404, 'message' => 'No se ha encontrado la entidad que se buscaba'];
    const ERROR_403 = ['code' => 403, 'message' => 'Este usuario no tiene autorización para realizar esta acción'];
    const USER_ERROR_400 = ['code' => 400, 'message' => 'El email o el nombre de usuario ya existen en la BBDD'];
    const USER_ERROR_422 = ['code' => 422, 'message' => 'Falta el nombre de usuario, email o contraseña en la entidad'];
}