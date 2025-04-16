<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseOverviewController;

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
Route::get('/dashboard', [DatabaseOverviewController::class, 'index'])->name('dashboard');

Route::get('/database-overview', [DatabaseOverviewController::class, 'index'])->name('database.overview');

Route::get('/record/edit/{table}/{id}', [DatabaseOverviewController::class, 'edit'])->name('record.edit');
Route::delete('/record/delete/{table}/{id}', [DatabaseOverviewController::class, 'delete'])->name('record.delete');
Route::put('/record/update/{table}/{id}', [DatabaseOverviewController::class, 'update'])->name('record.update');
Route::get('/record/create/{table}', [DatabaseOverviewController::class, 'create'])->name('record.create');
Route::post('/record/store/{table}', [DatabaseOverviewController::class, 'store'])->name('record.store');
Route::get('/autocomplete/{table}/{column}', [DatabaseOverviewController::class, 'autocomplete'])->name('autocomplete');

Route::get('/municipios-por-estado/{estado}', function($estado) {
    return DB::table('municipios')->where('estado_id', $estado)->select('id','nombre')->get();
});
