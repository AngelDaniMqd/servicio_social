<?php
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormularioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatabaseOverviewController;
use App\Http\Controllers\FormatoController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\AlumnosDescargaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlumnosController;
use App\Http\Controllers\AlumnoPublicoController; // ← AGREGAR ESTA LÍNEA

// ==================== RUTAS PÚBLICAS (sin autenticación) ====================
Route::get('/', function () {
    return view('solicitud');
})->name('solicitud');


// Rutas del formulario público de registro
Route::get('/solicitud', function () {
    return view('solicitud');
});

Route::get('/datos-alumno', [FormularioController::class, 'vistaDatosAlumno']);
Route::post('/guardar-datos-alumno', [FormularioController::class, 'guardarDatosAlumno']);

Route::get('/escolaridad', [FormularioController::class, 'vistaEscolaridad']);
Route::post('/guardar-escolaridad', [FormularioController::class, 'guardarEscolaridad']);

Route::get('/programa', [FormularioController::class, 'vistaPrograma']);
Route::post('/guardar-programa', [FormularioController::class, 'guardarPrograma']);

// Rutas de procesamiento del formulario
Route::post('/enviar-registro', [FormularioController::class, 'guardarTodo'])->name('enviar.registro');
Route::post('/final-registro', [FormularioController::class, 'guardarTodo'])->name('finalizar.registro');
Route::post('/completar-registro', [FormularioController::class, 'guardarTodo'])->name('completar.registro');
Route::post('/procesar-registro', [FormularioController::class, 'guardarTodo'])->name('procesar.registro');

// Vistas de resultado
Route::get('/final', function(){
    // Verificar si hay datos de registro exitoso en sesión
    if (!Session::get('registro_exitoso')) {
        return redirect('/datos-alumno')->with('error', 'Complete el formulario primero.');
    }
    
    $alumnoId = Session::get('alumno_id');
    $alumnoNombre = Session::get('alumno_nombre');
    
    return view('final', compact('alumnoId', 'alumnoNombre'));
})->name('final');

Route::get('/registro-exitoso', function(){
    return view('registro-exitoso', [
        'alumnoId' => session('alumno_id', 'DEMO'),
        'alumnoNombre' => session('alumno_nombre', 'Usuario Demo'),
        'numeroControl' => session('numero_control', '12345678'),
        'programaNombre' => session('programa_nombre', 'Programa Demo')
    ]);
})->name('registro.exitoso');

// AGREGAR ESTA NUEVA RUTA CON URL DIFERENTE:
Route::post('/completar-registro', [FormularioController::class, 'guardarTodo'])->name('completar.registro');

// Rutas para descargar documentos (PÚBLICAS para el registro)
Route::get('/export/solicitud/{alumnoId}', [FormatoController::class, 'exportSolicitud'])->name('export.solicitud');
Route::get('/export/escolaridad/{alumnoId}', [FormatoController::class, 'exportEscolaridad'])->name('export.escolaridad');
Route::get('/export/programa/{alumnoId}', [FormatoController::class, 'exportPrograma'])->name('export.programa');
Route::get('/export/final/{alumnoId}', [FormatoController::class, 'exportReporteFinal'])->name('export.final');

// ELIMINAR esta línea:
// Route::get('/finalizar-formulario', function () {
//     if (Session::get('registro_exitoso')) {
//         return redirect('/final');
//     }
//     return redirect('/datos-alumno')->with('error', 'Complete el formulario primero.');
// });

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.auth');

// Ruta para municipios (puede ser pública)
Route::get('/municipios-por-estado/{estado}', function($estado) {
    return DB::table('municipios')->where('estado_id', $estado)->select('id','nombre')->get();
});

Route::get('/municipios-por-estado/{estadoId}', function($estadoId) {
    $municipios = DB::table('municipios')
        ->where('estado_id', $estadoId)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);
    
    return response()->json($municipios);
});

// API para municipios
Route::get('/api/municipios-por-estado/{estadoId}', function($estadoId) {
    try {
        \Log::info('Solicitando municipios para estado ID: ' . $estadoId);
        
        $municipios = DB::table('municipios')
            ->where('estado_id', $estadoId)
            ->orderBy('nombre')
            ->select('id', 'nombre')
            ->get();
        
        \Log::info('Municipios encontrados: ' . $municipios->count());
        
        return response()->json($municipios);
    } catch (\Exception $e) {
        \Log::error('Error al obtener municipios por estado:', [
            'estado_id' => $estadoId,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([], 500);
    }
});

// ==================== RUTAS PROTEGIDAS (requieren autenticación) ====================
Route::prefix('admin')->group(function () {
    
    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
    
    // Dashboard y administración
    Route::get('/dashboard', [DatabaseOverviewController::class, 'index'])->name('dashboard');
    Route::get('/database-overview', [DatabaseOverviewController::class, 'index'])->name('database.overview');
    Route::get('/alumnos-recientes', [AlumnosDescargaController::class, 'index'])->name('alumnos.recientes');
    Route::get('/alumnos-descargar', [AlumnosDescargaController::class, 'index'])->name('alumnos.descargar');
    
    // Gestión de registros - CORREGIR ESTAS RUTAS
    Route::get('/record/edit/{table}/{id}', [DatabaseOverviewController::class, 'edit'])->name('record.edit');
    Route::put('/record/update/{table}/{id}', [DatabaseOverviewController::class, 'updateRecord'])->name('record.update');
    
    // ⚠️ AGREGAR ESTA RUTA SI NO EXISTE:
    Route::delete('/record/delete/{table}/{id}', [DatabaseOverviewController::class, 'delete'])->name('record.delete');
    
    Route::get('/record/create/{table}', [DatabaseOverviewController::class, 'create'])->name('record.create');
    Route::post('/record/store/{table}', [DatabaseOverviewController::class, 'store'])->name('record.store');
    
    // Autocompletado
    Route::get('/autocomplete/{table}/{column}', [DatabaseOverviewController::class, 'autocomplete'])->name('autocomplete');
    
    // Descargas y formatos
    Route::get('/descargar-formato/{id}', [FormatoController::class, 'downloadEditedWord'])->name('formatos.download');
    Route::get('/alumnos-descargar', [AlumnosDescargaController::class, 'index'])->name('alumnos.descargar');
    Route::get('/descargar-formato/{id}/{tipo}', [FormatoController::class, 'downloadEditedWord'])->name('formatos.download');
    
    // Subida de formatos
    Route::get('/subir-formatos/{id}', [FormatoController::class, 'mostrarFormularioSubida'])->name('formatos.upload');
    Route::post('/subir-formatos/{id}', [FormatoController::class, 'procesarSubidaFormatos'])->name('formatos.upload.post');
    
    // Exportación
    Route::get('/export/excel', [App\Http\Controllers\ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [App\Http\Controllers\ExportController::class, 'exportPdf'])->name('export.pdf');
    
    // Autocompletado
    Route::get('/autocomplete/{table}/{column}', [DatabaseOverviewController::class, 'autocomplete'])->name('autocomplete');
    
    // Rutas para gestión de registros (RecordController)
    Route::get('/record/create/{table}', [RecordController::class, 'create'])->name('record.create');
    Route::post('/record/store/{table}', [RecordController::class, 'store'])->name('record.store');
    Route::get('/record/edit/{table}/{id}', [RecordController::class, 'edit'])->name('record.edit');
    Route::put('/record/update/{table}/{id}', [RecordController::class, 'update'])->name('record.update');
    Route::delete('/record/delete/{table}/{id}', [RecordController::class, 'destroy'])->name('record.delete');
    
        // Formatos
        Route::get('/formatos/upload', [App\Http\Controllers\FormatosController::class, 'index'])->name('formatos.upload');
        Route::post('/formatos/upload', [App\Http\Controllers\FormatosController::class, 'store'])->name('formatos.upload.store'); // AGREGAR ESTA LÍNEA
        Route::put('/alumno/cancelar/{id}', [DatabaseOverviewController::class, 'cancelarAlumno'])->name('alumno.cancelar');
        Route::put('/record/{table}/{id}', [DatabaseOverviewController::class, 'update'])->name('record.update');
        
        // Alumnos específicos
        Route::put('/alumno/update/{id}', [DatabaseOverviewController::class, 'updateAlumno'])->name('alumno.update');
        
        // Rutas de exportación de documentos
        Route::get('/export/solicitud/{alumnoId}', [FormatoController::class, 'exportSolicitud'])->name('export.solicitud.admin');
        Route::get('/export/escolaridad/{alumnoId}', [FormatoController::class, 'exportEscolaridad'])->name('export.escolaridad.admin');
        Route::get('/export/programa/{alumnoId}', [FormatoController::class, 'exportPrograma'])->name('export.programa.admin');
        Route::get('/export/final/{alumnoId}', [FormatoController::class, 'exportReporteFinal'])->name('export.final.admin');
    });

// RUTA QUE PROCESA Y MUESTRA RESULTADO:
// REEMPLAZAR ESTA RUTA PROBLEMÁTICA:
/*
Route::post('/procesar-registro', function(Request $request) {
    try {
        // Llamar al método guardarTodo del controlador
        $controller = new \App\Http\Controllers\FormularioController();
        $resultado = $controller->guardarTodo($request);
        
        // Si el resultado es una vista, retornarla
        if ($resultado instanceof \Illuminate\View\View) {
            return $resultado;
        }
        
        // Si es una redirección, seguirla
        return $resultado;
        
    } catch (\Exception $e) {
        return view('registro-exitoso', [
            'alumnoId' => 'ERROR',
            'alumnoNombre' => 'Error en el procesamiento',
            'numeroControl' => 'N/A',
            'programaNombre' => 'Error: ' . $e->getMessage()
        ]);
    }
});
*/

// POR ESTA RUTA CORREGIDA:
Route::post('/procesar-registro', [FormularioController::class, 'guardarTodo'])->name('procesar.registro');

// Ruta para buscar registro existente
Route::post('/buscar-registro', [FormularioController::class, 'buscarRegistro'])->name('buscar.registro');

// Rutas para edición pública de alumnos - USAR NUEVO CONTROLADOR
Route::get('/alumno/{id}/edit', [FormularioController::class, 'editarAlumnoPublico'])->name('alumno.edit');
Route::put('/alumno/{id}/actualizar', [FormularioController::class, 'actualizarAlumnoPublico'])->name('alumno.actualizar');
Route::get('/actualizacion-exitosa', function() {
    return view('actualizacion-exitosa');
})->name('actualizacion.exitosa');
