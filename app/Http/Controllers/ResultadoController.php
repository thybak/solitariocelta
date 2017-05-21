<?php

namespace App\Http\Controllers;

use App\Models\Resultado;
use App\Models\Utils;
use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    /**
     * Comprueba que se facilitan los atributos obligatorios de la entidad
     * @param $resultadoPost
     * @return bool
     */
    private function camposObligatoriosPresentes($resultadoPost): bool
    {
        return isset($resultadoPost['usuarioId']) && isset($resultadoPost['puntos']);
    }

    /**
     * A partir de una petición, genera el nuevo registro en la base de datos una vez se ha validado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crear(Request $request)
    {
        $resultadoPost = $request->input();
        if (!$this->camposObligatoriosPresentes($resultadoPost)) {
            return response()->json(Utils::RES_ERROR_422, Utils::RES_ERROR_422['code']);
        }
        $usuarioId = $resultadoPost['usuarioId'];
        if (!Utils::usuarioValido($usuarioId)) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!Utils::checkPermisos($usuarioId)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $resultado = new Resultado();
        $resultado->fill($resultadoPost);
        if (!$resultado->save()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json($resultado, 201);
    }

    /**
     * A partir del identificador de resultado se genera una respuesta con el resultado registrado en la base de datos
     * @param Int $resultado
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtener(Int $resultado)
    {
        $resultadoDB = Resultado::find($resultado);
        if (!$resultadoDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!Utils::checkPermisos($resultadoDB->usuarioId)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        return response()->json($resultadoDB, 200);
    }

    /**
     * A partir del identificador de usuario genera una respuesta con todos los resultados del usuario
     * @param Int $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerDeUsuario(Int $usuario)
    {
        $resultados = Resultado::where('usuarioId', '=', $usuario)->get();
        if (!Utils::checkPermisos($usuario)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        if (!$resultados || count($resultados) == 0) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        return response()->json(['resultados' => $resultados], 200);
    }

    /**
     * A partir del identificador de resultado y tras unas validaciones previas, se elimina de la base de datos
     * @param Int $resultado
     * @return \Illuminate\Http\JsonResponse
     */
    public function borrar(Int $resultado)
    {
        if (!Utils::usuarioLogeadoEsAdmin()) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $resultadoDB = Resultado::find($resultado);
        if (!$resultadoDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!$resultadoDB->delete()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json([], 204);
    }

    /**
     * A partir del identificador de resultado y el cuerpo de la petición, se actualiza el registro en la base de datos con el cuerpo de la petición
     * @param Int $resultado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizar(Int $resultado, Request $request)
    {
        if (!Utils::usuarioLogeadoEsAdmin()) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $resultadoDB = Resultado::find($resultado);
        if (!$resultadoDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!$resultadoDB->update($request->input())) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json(Resultado::find($resultado), 209);
    }

    /**
     * Recupera todos los resultados almacenados en la base de datos
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTodos(){
        if (!Utils::usuarioLogeadoEsAdmin()){
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        return response()->json(['resultados' => Resultado::all()], 200);
    }
}
