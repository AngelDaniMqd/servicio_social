<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado en la sesión
        if (!session('admin_authenticated')) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión para acceder a esta área.');
        }

        return $next($request);
    }
}
