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
    const USER_ERROR_422 = ['code' => 422, 'message' => 'Falta el nombre de usuario, email o contraseña'];
    const RES_ERROR_422 = ['code' => 422, 'message' => 'Falta el identificador de usuario o la puntuación de la partida'];
    const RES_ERROR_400 = ['code' => 400, 'message' => 'Se han facilitado fechas incorrectas'];
    const PAR_ERROR_422 = ['code' => 422, 'message' => 'Falta el identificador de usuario o el estado de la partida'];

    /**
     * Devuelve el usuario que está autenticado actualmente a partir del token de sesión haciendo uso de la fachada JWTAuth
     * @return Usuario
     */
    public static function obtenerUsuarioLogeado(): Usuario
    {
        try {
            return \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (Exception $e){return null;}
    }
    /**
     * Determina si el usuario tiene permisos suficientes para realizar una operación
     * @param Int $id
     * @return bool
     */
    public static function checkPermisos(Int $id): bool
    {
        $usuarioLogeado = Utils::obtenerUsuarioLogeado();
        return $usuarioLogeado != null && $usuarioLogeado->habilitado && ($usuarioLogeado->esAdmin || ($usuarioLogeado->id == $id));
    }
    /**
     * Determina si el usuario autenticado es administrador o no
     * @return bool
     */
    public static function usuarioLogeadoEsAdmin(): bool
    {
        $usuarioLogeado = Utils::obtenerUsuarioLogeado();
        return $usuarioLogeado != null && $usuarioLogeado -> esAdmin && $usuarioLogeado -> habilitado;
    }

    /**
     * Comprueba que el identificador de usuario que se ha enviado se corresponde con el de algún usuario existente
     * @param $id
     * @return bool
     */
    public static function usuarioValido(Int $id): bool
    {
        $usuarioDB = \App\Models\Usuario::find($id);
        return $usuarioDB != null;
    }

    /**
     * Comprueba que el token que se pasa por parámetro se corresponde al de un usuario administrador
     * @param string $token
     * @return bool
     */
    public static function esTokenDeUsuarioAdmin(string $token): bool {
        $usuarioDB = null;
        try {
            $usuarioDB = \Tymon\JWTAuth\Facades\JWTAuth::authenticate($token);
        } catch (Exception $e){}
        return $usuarioDB != null && $usuarioDB -> esAdmin && $usuarioDB -> habilitado;
    }

    /**
     * Comprueba que el token que se pasa por parámetro se corresponde al de un usuario habilitado
     * @param string $token
     * @return bool
     */
    public static function esTokenDeUsuarioHabilitado(string $token){
        $usuarioDB = null;
        try {
            $usuarioDB = \Tymon\JWTAuth\Facades\JWTAuth::authenticate($token);
        } catch (Exception $e){}
        return $usuarioDB != null && $usuarioDB -> habilitado;
    }

    /**
     * Genera una vista a partir de su ruta y añadiendo el usuario autenticando en el momento
     * @param string $ruta
     * @return view
     */
    public static function generarVistaUsuario(string $ruta, $request){
        return view($ruta)->with('usuarioAuth', $request->only('usuarioAuth')['usuarioAuth']);
    }
}