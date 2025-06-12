<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseOverviewController;
use App\Http\Controllers\FormatoController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AlumnosDescargaController;

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

Route::get('/descargar-formato/{id}', [FormatoController::class, 'downloadEditedWord'])->name('formatos.download');
Route::get('/alumnos-descargar', [AlumnosDescargaController::class, 'index'])->name('alumnos.descargar');

// Ruta para descarga (recibe parámetro "tipo")
Route::get('/descargar-formato/{id}/{tipo}', [FormatoController::class, 'downloadEditedWord'])
    ->name('formatos.download');

// Ruta para mostrar formulario de carga de formatos (a crear)
Route::get('/subir-formatos/{id}', [FormatoController::class, 'mostrarFormularioSubida'])
    ->name('formatos.upload');
// Ruta para procesar la subida (POST)
Route::post('/subir-formatos/{id}', [FormatoController::class, 'procesarSubidaFormatos'])
    ->name('formatos.upload.post');

Route::get('/export/excel/{table}', [DatabaseOverviewController::class, 'exportToExcel'])->name('export.excel');
Route::get('/export/pdf/{table}', [DatabaseOverviewController::class, 'exportToPdf'])->name('export.pdf');
Route::get('/municipios-por-estado/{estadoId}', function($estadoId) {
    $municipios = DB::table('municipios')
        ->where('estado_id', $estadoId)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
    
    return response()->json($municipios);
});

// Rutas para gestión de registros
Route::get('/record/create/{table}', [RecordController::class, 'create'])->name('record.create');
Route::post('/record/store/{table}', [RecordController::class, 'store'])->name('record.store');
Route::get('/record/edit/{table}/{id}', [RecordController::class, 'edit'])->name('record.edit');
Route::put('/record/update/{table}/{id}', [RecordController::class, 'update'])->name('record.update');
Route::delete('/record/delete/{table}/{id}', [RecordController::class, 'destroy'])->name('record.delete');

// Rutas específicas para formatos - SIMPLIFICADAS
Route::get('/formatos/upload', [App\Http\Controllers\FormatosController::class, 'index'])->name('formatos.upload');
Route::post('/formatos/upload', [App\Http\Controllers\FormatosController::class, 'store'])->name('formatos.upload.store');

// IMPORTANTE: Esta ruta debe ir ANTES de la ruta genérica
Route::put('/alumno/{id}', [DatabaseOverviewController::class, 'updateAlumno'])->name('alumno.update');

// Ruta genérica para otras tablas (debe ir DESPUÉS)
Route::put('/record/{table}/{id}', [DatabaseOverviewController::class, 'update'])->name('record.update');
