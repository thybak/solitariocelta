<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class UsuarioController extends Controller
{

    private function obtenerUsuarioLogeado(): Usuario
    {
        return JWTAuth::parseToken()->authenticate();
    }

    private function checkPermisos(Int $id): bool
    {
        $usuarioLogeado = $this->obtenerUsuarioLogeado();
        return $usuarioLogeado != null && ($usuarioLogeado->esAdmin || ($usuarioLogeado->id == $id));
    }

    private function permisosParaPropiedadAdmin($usuarioPost)
    {
        $usuarioLogeado = $this->obtenerUsuarioLogeado();
        $usuarioPostDB = (new Usuario())->fill($usuarioPost);
        return $usuarioLogeado != null && (($usuarioLogeado->esAdmin && $usuarioPostDB->esAdmin) || !$usuarioPostDB->esAdmin);
    }

    private function camposUnicosValidos($usuarioPost): bool
    {
        $usuarioDB = Usuario::where('nombreUsuario', $usuarioPost['nombreUsuario'])->orwhere('email', $usuarioPost['email'])->first();
        return $usuarioDB == null;
    }

    private function camposObligatoriosPresentes($usuarioPost): bool
    {
        return isset($usuarioPost['nombreUsuario']) && isset($usuarioPost['password']) && isset($usuarioPost['email']);
    }

    private function setPasswordHasheada($usuarioPost)
    {
        if (isset($usuarioPost['password'])) {
            $usuarioPost['password'] = Hash::make($usuarioPost['password']);
        }
        return $usuarioPost;
    }

    public function obtenerTodos()
    {
        $usuario = JWTAuth::parseToken()->authenticate();
        if ($usuario && $usuario->esAdmin) {
            return response()->json(['usuarios' => Usuario::all()], 200);
        }
        return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
    }

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
        if (!$usuarioDB->save()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json($usuarioDB, 201);
    }

    public function obtener(Int $usuario)
    {
        $tienePermiso = $this->checkPermisos($usuario);
        if (!$tienePermiso) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $usuarioDB = Usuario::find($usuario);
        if (!$usuarioDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        return response()->json($usuarioDB, 200);
    }

    public function borrar(Int $usuario)
    {
        $tienePermiso = $this->checkPermisos($usuario);
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

    public function actualizar(Int $usuario, Request $request)
    {
        $tienePermiso = $this->checkPermisos($usuario);
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
