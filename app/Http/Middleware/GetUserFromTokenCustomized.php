<?php

namespace App\Http\Middleware;


use Tymon\JWTAuth\Middleware\BaseMiddleware;

class GetUserFromTokenCustomized extends BaseMiddleware
{

    const ERROR_401 = 'Petición no autorizada ';
    const ERROR_401_SUBTIPO = ['(ausencia de token de sesión)', '(token expirado)', '(token inválido)'];
    /**
     * Fire event and return the response. (Sobreescrito)
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        return $response ?: $this->response->json(['code' => $status,'message' => $error], $status);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent',self::ERROR_401.self::ERROR_401_SUBTIPO[0] , 401);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', self::ERROR_401.self::ERROR_401_SUBTIPO[1], 401, [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', self::ERROR_401.self::ERROR_401_SUBTIPO[2], 401, [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}