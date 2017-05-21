<?php

namespace App\Http\Controllers;

use App\models\PartidaSinFinalizar;
use App\Models\Utils;
use Illuminate\Http\Request;

class PartidaSinFinalizarController extends Controller
{
    /**
     * Comprueba que se facilitan los atributos obligatorios de la entidad
     * @param $resultadoPost
     * @return bool
     */
    private function camposObligatoriosPresentes($resultadoPost): bool
    {
        return isset($resultadoPost['usuarioId']) && isset($resultadoPost['estadoJson']);
    }

    /**
     * A partir de una petición, genera el nuevo registro en la base de datos una vez se ha validado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crear(Request $request){
        $partidaPost = $request->input();
        if (!$this->camposObligatoriosPresentes($partidaPost)) {
            return response()->json(Utils::PAR_ERROR_422, Utils::RES_ERROR_422['code']);
        }
        $usuarioId = $partidaPost['usuarioId'];
        if (!Utils::usuarioValido($usuarioId)) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!Utils::checkPermisos($usuarioId)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $partida = new PartidaSinFinalizar();
        $partida->fill($partidaPost);
        if (!$partida->save()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json($partida, 201);
    }

    /**
     * A partir del identificador de partida se genera una respuesta con la partida registrada en la base de datos
     * @param Int $partida
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtener(Int $partida){
        $partidaDB = PartidaSinFinalizar::find($partida);
        if (!$partidaDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!Utils::checkPermisos($partidaDB->usuarioId)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        return response()->json($partidaDB, 200);
    }

    /**
     * A partir del identificador de usuario genera una respuesta con todas las partidas del usuario
     * @param Int $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerDeUsuario(Int $usuario){
        $partidas = PartidaSinFinalizar::where('usuarioId', '=', $usuario)->get();
        if (!Utils::checkPermisos($usuario)) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        if (!$partidas || count($partidas) == 0) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        return response()->json(['partidas' => $partidas], 200);
    }

    /**
     * A partir del identificador de partida y tras unas validaciones previas, se elimina de la base de datos
     * @param Int $partida
     * @return \Illuminate\Http\JsonResponse
     */
    public function borrar(Int $partida){
        if (!Utils::usuarioLogeadoEsAdmin()) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $partidaDB = PartidaSinFinalizar::find($partida);
        if (!$partidaDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!$partidaDB->delete()) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json([], 204);
    }

    /**
     * A partir del identificador de partida y el cuerpo de la petición, se actualiza el registro en la base de datos con el cuerpo de la petición
     * @param Int $partida
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizar (Int $partida, Request $request){
        if (!Utils::usuarioLogeadoEsAdmin()) {
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        $partidaDB = PartidaSinFinalizar::find($partida);
        if (!$partidaDB) {
            return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
        }
        if (!$partidaDB->update($request->input())) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }
        return response()->json(Resultado::find($partida), 209);
    }

    /**
     * Recupera todas las partidas almacenadas en la base de datos
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTodos(){
        if (!Utils::usuarioLogeadoEsAdmin()){
            return response()->json(Utils::ERROR_403, Utils::ERROR_403['code']);
        }
        return response()->json(['partidas' => PartidaSinFinalizar::all()], 200);
    }
}
