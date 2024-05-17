<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $username = 'devsoft';
        $password = 'Soft_';

        // Decodificar el encabezado de autorización
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader || substr($authorizationHeader, 0, 6) !== 'Basic ') {
            return response('Unauthorized', 401)
                ->header('WWW-Authenticate', 'Basic realm="Access to the API"');
        }

        // Obtener las credenciales
        $encodedCredentials = substr($authorizationHeader, 6);
        $decodedCredentials = base64_decode($encodedCredentials);
        list($requestUsername, $requestPassword) = explode(':', $decodedCredentials, 2);

        // Verificar las credenciales
        if ($requestUsername !== $username || $requestPassword !== $password) {
            return response('Unauthorized', 401)
                ->header('WWW-Authenticate', 'Basic realm="Access to the API"');
        }

        return $next($request);
    }
}
