<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/solicitud', function () {
    return view('solicitud');
});

Route::get('/datos-alumno', [FormularioController::class, 'vistaDatosAlumno']);
Route::post('/guardar-datos-alumno', [FormularioController::class, 'guardarDatosAlumno']);

Route::get('/escolaridad', [FormularioController::class, 'vistaEscolaridad']);
Route::post('/guardar-escolaridad', [FormularioController::class, 'guardarEscolaridad']);

Route::get('/programa', [FormularioController::class, 'vistaPrograma']);
Route::post('/finalizar-formulario', [FormularioController::class, 'guardarTodo']);

Route::get('/final', function () {
    return view('final');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.auth');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Ejemplo de ruta protegida para el dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('check.usuario'); // usa el alias que definiste
