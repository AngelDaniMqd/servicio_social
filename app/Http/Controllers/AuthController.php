<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()->withErrors([
                'correo' => 'Las credenciales son incorrectas.'
            ]);
        }

        // AGREGAR ESTAS LÍNEAS para establecer la sesión:
        session(['admin_authenticated' => true]);
        session(['admin_user' => $usuario]);
        session(['usuario_id' => $usuario->id]);

        return redirect()->route('dashboard')->with('success', 'Bienvenido al panel de administración');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        // Limpiar todas las sesiones de autenticación
        session()->forget(['admin_authenticated', 'admin_user', 'usuario_id']);
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}