<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ModifyAuthorizationHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtiene el valor del encabezado Authorization
        $authorizationHeader = $request->header('Authorization');

        // Verifica si el encabezado Authorization contiene el prefijo 'JWT'
        if (Str::startsWith($authorizationHeader, 'JWT ')) {
            // Reemplaza el prefijo 'JWT' por 'Bearer'
            $newAuthorizationHeader = Str::replaceFirst('JWT ', 'Bearer ', $authorizationHeader);
            // Establece el nuevo valor del encabezado Authorization
            $request->headers->set('Authorization', $newAuthorizationHeader);
        }

        return $next($request);
    }
}
