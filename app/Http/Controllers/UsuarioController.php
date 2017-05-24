<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class UsuarioController extends Controller
{
    /**
     * Determina si el usuario recibido en el cuerpo de la petición tiene permisos para modificar propiedades de administración
     * @param $usuarioPost
     * @return bool
     */
    private function permisosParaPropiedadAdmin($usuarioPost): bool
    {
        $usuarioLogeado = Utils::obtenerUsuarioLogeado();
        $usuarioPostDB = (new Usuario())->fill($usuarioPost);
        return $usuarioLogeado != null && (($usuarioLogeado->esAdmin && $usuarioPostDB->esAdmin) || !$usuarioPostDB->esAdmin);
    }

    /**
     * Comprueba si el cuerpo de la petición tiene campos únicos válidos de cara a los registros de la base de datos
     * @param $usuarioPost
     * @return bool
     */
    private function camposUnicosValidos($usuarioPost): bool
    {
        $usuarioDB = Usuario::where('nombreUsuario', $usuarioPost['nombreUsuario'])->orwhere('email', $usuarioPost['email'])->first();
        return $usuarioDB == null;
    }

    /**
     * Determina si los campos obligatorios de la entidad están presentes o no
     * @param $usuarioPost
     * @return bool
     */
    private function camposObligatoriosPresentes($usuarioPost): bool
    {
        return isset($usuarioPost['nombreUsuario']) && isset($usuarioPost['password']) && isset($usuarioPost['email'])
            && isset($usuarioPost['nombre']) && isset($usuarioPost['apellidos']) && isset($usuarioPost['telefono']);
    }

    /**
     * Hashea la contraseña del usuario que se ha recibido en el cuerpo de la petición en caso de que exista
     * @param $usuarioPost
     * @return mixed
     */
    private function setPasswordHasheada($usuarioPost)
    {
        if (isset($usuarioPost['password'])) {
            $usuarioPost['password'] = Hash::make($usuarioPost['password']);
        }
        return $usuarioPost;
    }

    /**
     * Obtiene todos los usuarios del sistema
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTodos()
    {
        if (Utils::usuarioLogeadoEsAdmin()) {
            return response()->json(['usuarios' => Usuario::all()], 200);
        }
        return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
    }

    /**
     * Obtiene todos los usuarios deshabilitados del sistema
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTodosLosDeshabilitados()
    {
        if (Utils::usuarioLogeadoEsAdmin()) {
            return response()->json(['usuarios' => Usuario::where('habilitado', '=', false)->get()], 200);
        }
        return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
    }

    /**
     * Crea un nuevo usuario en la base de datos a partir del cuerpo de la petición
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crear(Request $request)
    {
        $usuarioPost = $request->input();
        if (!$this->camposObligatoriosPresentes($usuarioPost)) {
            return response()->json(Utils::USER_ERROR_422, Utils::USER_ERROR_422['code']);
        }
        if (!$this->camposUnicosValidos($usuarioPost)) {
            return response()->json(Utils::USER_ERROR_400, Utils::USER_ERROR_400['code']);
        }
        if (!$this->permisosParaPropiedadAdmin($usuarioPost)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $usuarioDB = new Usuario;
        $usuarioPost = $this->setPasswordHasheada($usuarioPost);
        $usuarioDB = $usuarioDB->fill($usuarioPost);
        $usuarioDB -> habilitado = false;
        if (!$usuarioDB->save()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json($usuarioDB, 201);
    }

    /**
     * Obtiene el usuario por su identificador
     * @param Int $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtener(Int $usuario)
    {
        $tienePermiso = Utils::checkPermisos($usuario);
        if (!$tienePermiso) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $usuarioDB = Usuario::find($usuario);
        if (!$usuarioDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        return response()->json($usuarioDB, 200);
    }

    /**
     * Borrar el usuario a partir de su identificador tras comprobaciones previas
     * @param Int $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function borrar(Int $usuario)
    {
        $tienePermiso = Utils::checkPermisos($usuario);
        if (!$tienePermiso) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $usuarioDB = Usuario::find($usuario);
        if (!$usuarioDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!$usuarioDB->delete()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json([], 204);
    }

    /**
     * Actualiza el registro asociado al identificador de usuario a partir del cuerpo de la petición
     * @param Int $usuario
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizar(Int $usuario, Request $request)
    {
        $tienePermiso = Utils::checkPermisos($usuario);
        $usuarioPost = $request->input();
        $usuarioPost = $this->setPasswordHasheada($usuarioPost);
        if (!$tienePermiso || !$this->permisosParaPropiedadAdmin($usuarioPost)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $usuarioDB = Usuario::find($usuario);
        if (!$usuarioDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!$usuarioDB->update($usuarioPost)) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json(Usuario::find($usuario), 209);
    }
}
