<?php
// filepath: c:\Users\100097567\Desktop\servicio_social\app\Http\Middleware\CheckUsuario.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUsuario
{
    /**
     * Maneja la petición entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('usuario_id')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}