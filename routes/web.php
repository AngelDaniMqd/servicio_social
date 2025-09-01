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

// ==================== RUTAS PÚBLICAS CON RATE LIMITING ====================
Route::middleware(['throttle:10,1'])->group(function () {
    Route::get('/', function () {
        return view('solicitud');
    })->name('solicitud');

    Route::get('/solicitud', function () {
        return view('solicitud');
    });

    // Vistas del formulario público
    Route::get('/datos-alumno', [FormularioController::class, 'vistaDatosAlumno']);
    Route::get('/escolaridad', [FormularioController::class, 'vistaEscolaridad']);
    Route::get('/programa', [FormularioController::class, 'vistaPrograma']);

    // Vista final
    Route::get('/final', function(){
        if (!Session::get('registro_exitoso')) {
            return redirect('/datos-alumno')->with('error', 'Complete el formulario primero.');
        }
        
        $alumnoId = Session::get('alumno_id');
        $alumnoNombre = Session::get('alumno_nombre');
        
        return view('final', compact('alumnoId', 'alumnoNombre'));
    })->name('final');

    // Vista de registro exitoso
    Route::get('/registro-exitoso', function(){
        return view('registro-exitoso', [
            'alumnoId' => session('alumno_id', 'DEMO'),
            'alumnoNombre' => session('alumno_nombre', 'Usuario Demo'),
            'numeroControl' => session('numero_control', '12345678'),
            'programaNombre' => session('programa_nombre', 'Programa Demo')
        ]);
    })->name('registro.exitoso');

    // Rutas para edición pública de alumnos
    Route::get('/alumno/{id}/edit', [FormularioController::class, 'editarAlumnoPublico'])
        ->where('id', '^[0-9]+$')
        ->name('alumno.edit');
    Route::put('/alumno/{id}/actualizar', [FormularioController::class, 'actualizarAlumnoPublico'])
        ->where('id', '^[0-9]+$')
        ->name('alumno.actualizar');
    Route::get('/actualizacion-exitosa', function() {
        return view('actualizacion-exitosa');
    })->name('actualizacion.exitosa');
});

// Formulario público con protección estricta
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/guardar-datos-alumno', [FormularioController::class, 'guardarDatosAlumno']);
    Route::post('/guardar-escolaridad', [FormularioController::class, 'guardarEscolaridad']);
    Route::post('/guardar-programa', [FormularioController::class, 'guardarPrograma']);
    Route::post('/enviar-registro', [FormularioController::class, 'guardarTodo'])->name('enviar.registro');
    Route::post('/final-registro', [FormularioController::class, 'guardarTodo'])->name('finalizar.registro');
    Route::post('/completar-registro', [FormularioController::class, 'guardarTodo'])->name('completar.registro');
    Route::post('/procesar-registro', [FormularioController::class, 'guardarTodo'])->name('procesar.registro');
    Route::post('/buscar-registro', [FormularioController::class, 'buscarRegistro'])->name('buscar.registro');
});

// API endpoints con validación estricta
Route::middleware(['throttle:30,1'])->group(function () {
    Route::get('/api/municipios-por-estado/{estadoId}', function($estadoId) {
        if (!ctype_digit($estadoId)) {
            return response()->json(['error' => 'Formato inválido'], 400);
        }
        
        $estadoId = (int) $estadoId;
        if ($estadoId < 1 || $estadoId > 32) {
            return response()->json(['error' => 'Estado fuera de rango'], 400);
        }
        
        try {
            $municipios = DB::table('municipios')
                ->where('estado_id', '=', $estadoId)
                ->orderBy('nombre', 'asc')
                ->select(['id', 'nombre'])
                ->get();
            
            return response()->json($municipios);
        } catch (\Exception $e) {
            \Log::error('Error API municipios:', [
                'estado_id' => $estadoId,
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);
            
            return response()->json(['error' => 'Error interno'], 500);
        }
    })->where('estadoId', '^[1-9][0-9]*$');
});

// ==================== DOCUMENTOS PÚBLICOS CON VALIDACIÓN DE SESIÓN ====================
Route::middleware(['throttle:10,1'])->group(function () {
    Route::get('/export/solicitud/{alumnoId}', function($alumnoId) {
        // Validar que el usuario tenga permiso para descargar este alumno
        if (!session('alumno_id') || session('alumno_id') != $alumnoId) {
            abort(403, 'No tienes permiso para descargar este documento');
        }
        return app(FormatoController::class)->exportSolicitud($alumnoId);
    })->where('alumnoId', '^[0-9]+$')->name('export.solicitud');
    
    Route::get('/export/escolaridad/{alumnoId}', function($alumnoId) {
        if (!session('alumno_id') || session('alumno_id') != $alumnoId) {
            abort(403, 'No tienes permiso para descargar este documento');
        }
        return app(FormatoController::class)->exportEscolaridad($alumnoId);
    })->where('alumnoId', '^[0-9]+$')->name('export.escolaridad');
    
    Route::get('/export/programa/{alumnoId}', function($alumnoId) {
        if (!session('alumno_id') || session('alumno_id') != $alumnoId) {
            abort(403, 'No tienes permiso para descargar este documento');
        }
        return app(FormatoController::class)->exportPrograma($alumnoId);
    })->where('alumnoId', '^[0-9]+$')->name('export.programa');
    
    Route::get('/export/final/{alumnoId}', function($alumnoId) {
        if (!session('alumno_id') || session('alumno_id') != $alumnoId) {
            abort(403, 'No tienes permiso para descargar este documento');
        }
        return app(FormatoController::class)->exportReporteFinal($alumnoId);
    })->where('alumnoId', '^[0-9]+$')->name('export.final');
});

// ==================== AUTENTICACIÓN CON RATE LIMITING RELAJADO ====================

// 🔧 Login form - SIN rate limiting restrictivo
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// 🔧 Login attempts - Rate limiting más permisivo
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:20,1')  // 20 intentos por minuto en lugar de 5
    ->name('admin.auth');

// Redirigir /admin a login
Route::get('/admin', function () {
    return redirect()->route('login');
});

// ==================== RUTAS ADMIN TOTALMENTE PROTEGIDAS ====================
Route::middleware(['admin'])->prefix('admin')->group(function () {
    
    // 🔧 Logout SIN rate limiting
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
    
    // 🔧 Rutas admin con rate limiting MUY permisivo
    Route::middleware(['throttle:500,1'])->group(function () {
        
        // Dashboard y administración
        Route::get('/dashboard', [DatabaseOverviewController::class, 'index'])->name('dashboard');
        Route::get('/database-overview', [DatabaseOverviewController::class, 'index'])->name('database.overview');
        
        // Alumnos
        Route::get('/alumnos-recientes', [AlumnosDescargaController::class, 'index'])->name('alumnos.recientes');
        Route::get('/alumnos-descargar', [AlumnosDescargaController::class, 'index'])->name('alumnos.descargar');
        
        // Gestión de registros
        Route::get('/record/edit/{table}/{id}', [RecordController::class, 'edit'])
            ->where(['table' => '^[a-zA-Z_]+$', 'id' => '^[0-9]+$'])
            ->name('record.edit');
        Route::put('/record/update/{table}/{id}', [RecordController::class, 'update'])
            ->where(['table' => '^[a-zA-Z_]+$', 'id' => '^[0-9]+$'])
            ->name('record.update');
        Route::delete('/record/delete/{table}/{id}', [DatabaseOverviewController::class, 'delete'])
            ->where(['table' => '^[a-zA-Z_]+$', 'id' => '^[0-9]+$'])
            ->name('record.delete');
        Route::get('/record/create/{table}', [DatabaseOverviewController::class, 'create'])
            ->where('table', '^[a-zA-Z_]+$')
            ->name('record.create');
        Route::post('/record/store/{table}', [DatabaseOverviewController::class, 'store'])
            ->where('table', '^[a-zA-Z_]+$')
            ->name('record.store');
        
        // Autocompletado
        Route::get('/autocomplete/{table}/{column}', [DatabaseOverviewController::class, 'autocomplete'])
            ->where(['table' => '^[a-zA-Z_]+$', 'column' => '^[a-zA-Z_]+$'])
            ->name('autocomplete');
        
        // Formatos
        Route::get('/formatos/upload', [App\Http\Controllers\FormatosController::class, 'index'])->name('formatos.upload');
        Route::post('/formatos/upload', [App\Http\Controllers\FormatosController::class, 'store'])->name('formatos.upload.store');
        
        // Subida de formatos por alumno
        Route::get('/subir-formatos/{id}', [FormatoController::class, 'mostrarFormularioSubida'])
            ->where('id', '^[0-9]+$')
            ->name('formatos.upload.form');
        Route::post('/subir-formatos/{id}', [FormatoController::class, 'procesarSubidaFormatos'])
            ->where('id', '^[0-9]+$')
            ->name('formatos.upload.post');
        
        // Descargas de formatos
        Route::get('/descargar-formato/{id}', [FormatoController::class, 'downloadEditedWord'])
            ->where('id', '^[0-9]+$')
            ->name('formatos.download');
        Route::get('/descargar-formato/{id}/{tipo}', [FormatoController::class, 'downloadEditedWord'])
            ->where(['id' => '^[0-9]+$', 'tipo' => '^[a-zA-Z_]+$'])
            ->name('formatos.download.tipo');
        
        // Exportación
        Route::get('/export/excel', [App\Http\Controllers\ExportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [App\Http\Controllers\ExportController::class, 'exportPdf'])->name('export.pdf');
        
        // Alumnos específicos
        Route::put('/alumno/cancelar/{id}', [DatabaseOverviewController::class, 'cancelarAlumno'])
            ->where('id', '^[0-9]+$')
            ->name('alumno.cancelar');
        Route::put('/alumno/update/{id}', [DatabaseOverviewController::class, 'updateAlumno'])
            ->where('id', '^[0-9]+$')
            ->name('alumno.update');
        
        // Rutas de exportación de documentos admin
        Route::get('/export/solicitud/{alumnoId}', [FormatoController::class, 'exportSolicitud'])
            ->where('alumnoId', '^[0-9]+$')
            ->name('export.solicitud.admin');
        Route::get('/export/escolaridad/{alumnoId}', [FormatoController::class, 'exportEscolaridad'])
            ->where('alumnoId', '^[0-9]+$')
            ->name('export.escolaridad.admin');
        Route::get('/export/programa/{alumnoId}', [FormatoController::class, 'exportPrograma'])
            ->where('alumnoId', '^[0-9]+$')
            ->name('export.programa.admin');
        Route::get('/export/final/{alumnoId}', [FormatoController::class, 'exportReporteFinal'])
            ->where('alumnoId', '^[0-9]+$')
            ->name('export.final.admin');
    });
});
