<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🔍 DEBUG: Verificar si el middleware se ejecuta
        \Log::info('AdminMiddleware ejecutándose', [
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'session_admin_logged_in' => session('admin_logged_in'),
            'session_admin_id' => session('admin_id'),
        ]);

        // 🔒 VERIFICACIÓN ESTRICTA DE AUTENTICACIÓN
        if (!session('admin_logged_in') || !session('admin_id')) {
            \Log::warning('ACCESO DENEGADO - No hay sesión admin', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'time' => now(),
                'admin_logged_in' => session('admin_logged_in'),
                'admin_id' => session('admin_id'),
            ]);
            
            // 🚨 FORZAR REDIRECCIÓN
            return redirect()->route('login')
                ->with('error', 'ACCESO DENEGADO: Debe iniciar sesión para acceder al área administrativa.');
        }

        // 🔒 VERIFICAR QUE EL USUARIO SIGA SIENDO VÁLIDO
        $user = \DB::table('usuario')
            ->where('id', session('admin_id'))
            ->where('rol_id', 2) // Solo administradores
            ->first();

        if (!$user) {
            // Limpiar sesión comprometida
            session()->flush();
            
            \Log::warning('SESIÓN INVÁLIDA - Usuario no encontrado o sin permisos', [
                'session_admin_id' => session('admin_id'),
                'ip' => $request->ip()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'ACCESO DENEGADO: Su sesión ha expirado o su cuenta ha sido desactivada.');
        }

        // 🔒 VERIFICAR TIEMPO DE SESIÓN (4 horas máximo)
        if (session('admin_login_time')) {
            $loginTime = session('admin_login_time');
            if (now()->diffInHours($loginTime) > 4) {
                session()->flush();
                
                \Log::info('Sesión admin expirada por tiempo', [
                    'user_id' => $user->id,
                    'login_time' => $loginTime
                ]);
                
                return redirect()->route('login')
                    ->with('error', 'ACCESO DENEGADO: Sesión expirada por tiempo. Inicie sesión nuevamente.');
            }
        }

        // 🔒 RENOVAR TIEMPO DE SESIÓN
        session(['admin_login_time' => now()]);

        \Log::info('AdminMiddleware: Acceso autorizado', [
            'user_id' => $user->id,
            'url' => $request->fullUrl()
        ]);

        $response = $next($request);
        
        // 🔒 HEADERS DE SEGURIDAD
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // ✅ CSP CORREGIDO - Incluir 'unsafe-eval' para Alpine.js
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
               "https://cdn.tailwindcss.com " .
               "https://code.jquery.com " .
               "https://cdn.jsdelivr.net " .
               "https://unpkg.com; " .
               "style-src 'self' 'unsafe-inline' " .
               "https://fonts.googleapis.com " .
               "https://cdnjs.cloudflare.com " .
               "https://cdn.tailwindcss.com; " .
               "font-src 'self' " .
               "https://fonts.gstatic.com " .
               "https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: " .
               "https:; " .
               "connect-src 'self' " .
               "https://cdn.tailwindcss.com;";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        return $response;
    }
}