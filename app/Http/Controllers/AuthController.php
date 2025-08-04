<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Usuario;
use App\Models\Rol; // Asegúrate de tener este modelo

class AuthController extends Controller
{
    // Mostrar formulario de registro con roles dinámicos
    public function showRegisterForm()
    {
        $roles = Rol::all();
        return view('auth.register', compact('roles'));
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:45',
            'apellidoP'    => 'required|string|max:45',
            'apellidoM'    => 'required|string|max:45',
            'telefono'     => 'required|numeric',
            'correo'       => 'required|email|max:45|unique:usuario,correo',
            'rol_id'       => 'required|integer',
            'password'     => 'required|string|min:6|confirmed'
        ]);

        $usuario = new Usuario();
        $usuario->nombre    = $request->nombre;
        $usuario->apellidoP = $request->apellidoP;
        $usuario->apellidoM = $request->apellidoM;
        $usuario->telefono  = $request->telefono;
        $usuario->correo    = $request->correo;
        $usuario->rol_id    = $request->rol_id;
        $usuario->password  = Hash::make($request->password);
        $usuario->save();

        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
    }

    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        // Rate limiting por IP
        $key = 'login-attempts.' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 20)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'correo' => "Demasiados intentos de login. Intente nuevamente en {$seconds} segundos.",
            ]);
        }

        $credentials = $request->validate([
            'correo' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        // Buscar usuario
        $user = DB::table('usuario')
            ->where('correo', $credentials['correo'])
            ->where('rol_id', 2) // Solo administradores
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($key, 300);
            return back()->withErrors(['correo' => 'Credenciales inválidas.']);
        }

        // Limpiar rate limiting exitoso
        RateLimiter::clear($key);

        // Crear sesión segura
        $request->session()->regenerate();
        
        // Guardar datos en sesión
        session([
            'admin_id' => $user->id,
            'admin_name' => $user->nombre,
            'admin_email' => $user->correo,
            'admin_logged_in' => true,
            'admin_login_time' => now(),
            'admin_role' => 'Administrador', // Para mostrar en la vista
        ]);

        // Log para verificar que la sesión se creó
        \Log::info('Login exitoso - Sesión creada', [
            'user_id' => $user->id,
            'user_name' => $user->nombre,
            'session_data' => [
                'admin_id' => session('admin_id'),
                'admin_name' => session('admin_name'),
                'admin_logged_in' => session('admin_logged_in'),
            ]
        ]);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Bienvenido, ' . $user->nombre);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        // Log de logout
        \Log::info('Admin logout', [
            'user_id' => session('admin_id'),
            'user_name' => session('admin_name'),
            'ip' => $request->ip(),
        ]);

        // Limpiar sesión completamente
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente');
    }
}