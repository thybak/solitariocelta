<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function obtenerTodos(){
        return json_encode(Usuario::all());
    }

    public function crear(Request $request){
        $ok = false;
        $usuarioDB = null;
        if ($request->isJson()){
            $usuarioPost = $request->input();
            $usuarioDB = new Usuario;
            if (isset($usuarioPost['password'])) {
                $usuarioPost['password'] = Hash::make($usuarioPost['password']);
            }
            $usuarioDB = $usuarioDB -> fill($usuarioPost);
            $ok = $usuarioDB->save();
        }
        return $ok ? json_encode($usuarioDB) : "Error guardando al usuario";
    }

    public function obtener(Usuario $usuario){
        return json_encode($usuario);
    }

    public function borrar(Usuario $usuario){
        $ok = false;
        if ($usuario){
            $ok = $usuario->delete();
        }
        if ($ok){
            return "Usuario borrado";
        }
        return "No se ha encontrado el usuario";
    }

    public function actualizar(Usuario $usuario, Request $request){
        $ok = false;

        $usuarioDB = $usuario;
        if ($usuarioDB && $request->isJson()){
            $usuarioPost = $request->input();
            if (isset($usuarioPost['password'])) {
                $usuarioPost['password'] = Hash::make($usuarioPost['password']);
                var_dump($usuarioPost['password']);
            }
            $ok = $usuarioDB->update($usuarioPost);
        }

        return $ok ? json_encode(Usuario::find($usuario['id'])) : "Error al actualizar el usuario";
    }
}
