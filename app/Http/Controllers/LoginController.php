<?php

namespace App\Http\Controllers;
use App\Models\Utils;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credenciales = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credenciales)) {
                return response()->json(Utils::ERROR_404, Utils::ERROR_404['code']);
            }
        } catch (JWTException $e) {
            return response()->json(Utils::ERROR_500, Utils::ERROR_500['code']);
        }

        return response()->json(compact('token'));
    }
}
