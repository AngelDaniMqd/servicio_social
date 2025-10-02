<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseOverviewController extends Controller
{
    public function index(Request $request)
    {
        $selectedTable = $request->query('table');
        $rows = null;
        $filterOptions = [];

        if ($selectedTable) {
            if ($selectedTable === 'alumno') {
                // Para la tabla alumno, usamos consulta con joins para obtener información relacionada
                $query = $this->buildAlumnoQuery();
                $this->applyAlumnoFilters($query, $request);
                $filterOptions = $this->getFilterOptions();
            } else {
                // Para otras tablas, consulta simple
                $query = DB::table($selectedTable);
                
                // Agregar búsqueda general
                if ($request->filled('search')) {
                    $searchTerm = $request->search;
                    $columns = Schema::getColumnListing($selectedTable);
                    
                    $query->where(function($q) use ($columns, $searchTerm) {
                        foreach ($columns as $column) {
                            $q->orWhere($column, 'like', '%' . $searchTerm . '%');
                        }
                    });
                }
            }
               // Orden global por ID (últimos primero)
            if ($selectedTable === 'alumno') {
                $query->orderByDesc('alumno.id');
            } elseif (Schema::hasColumn($selectedTable, 'id')) {
                $query->orderByDesc($selectedTable . '.id');
            }
            // Aplicar paginación con registros configurables
            $perPage = $request->input('per_page', 50); // Default 50
            $perPage = in_array($perPage, [25, 50, 100, 250]) ? $perPage : 50; // Validar valores permitidos
            $rows = $query->paginate($perPage)->appends($request->query());
        }

        return view('database-overview', compact('selectedTable', 'rows', 'filterOptions'));
    }

    private function buildAlumnoQuery()
    {
        return DB::table('alumno')
            ->leftJoin('edad', 'alumno.edad_id', '=', 'edad.id')
            ->leftJoin('sexo', 'alumno.sexo_id', '=', 'sexo.id')
            ->leftJoin('rol', 'alumno.rol_id', '=', 'rol.id')
            ->leftJoin('status', 'alumno.status_id', '=', 'status.id')
            ->leftJoin('ubicaciones', 'alumno.id', '=', 'ubicaciones.alumno_id')
            ->leftJoin('municipios', 'ubicaciones.municipios_id', '=', 'municipios.id')
            ->leftJoin('estados', 'municipios.estado_id', '=', 'estados.id')
            ->leftJoin('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id')
            ->leftJoin('carreras', 'escolaridad_alumno.carreras_id', '=', 'carreras.id')
            ->leftJoin('semestres', 'escolaridad_alumno.semestres_id', '=', 'semestres.id')
            ->leftJoin('grupos', 'escolaridad_alumno.grupos_id', '=', 'grupos.id')
            ->leftJoin('modalidad', 'escolaridad_alumno.modalidad_id', '=', 'modalidad.id')
            ->leftJoin('programa_servicio_social', 'alumno.id', '=', 'programa_servicio_social.alumno_id')
            ->leftJoin('instituciones', 'programa_servicio_social.instituciones_id', '=', 'instituciones.id')
            ->leftJoin('titulos', 'programa_servicio_social.titulos_id', '=', 'titulos.id')
            ->leftJoin('metodo_servicio', 'programa_servicio_social.metodo_servicio_id', '=', 'metodo_servicio.id')
            ->leftJoin('tipos_programa', 'programa_servicio_social.tipos_programa_id', '=', 'tipos_programa.id')
            ->where('alumno.status_id', 1) // Solo mostrar alumnos activos
            ->select([
                // Información básica del alumno con nombres reales de columnas
                'alumno.id',
                'alumno.nombre',
                'alumno.apellido_p',
                'alumno.apellido_m',
                'alumno.correo_institucional',
                'alumno.telefono',
                'alumno.fecha_registro',
                
                // IMPORTANTE: Incluir tanto el ID como el nombre del status
                'alumno.status_id',
                'status.tipo as status',
                
                // Reemplazar IDs con nombres descriptivos
                'edad.edades as edad',
                'sexo.tipo as sexo',
                'rol.tipo as rol',
                
                // Información de ubicación
                'ubicaciones.localidad',
                'ubicaciones.cp',
                'municipios.nombre as municipio',
                'estados.nombre as estado',
                
                // Información académica
                'escolaridad_alumno.numero_control',
                'escolaridad_alumno.meses_servicio',
                'carreras.nombre as carrera',
                'semestres.nombre as semestre',
                'grupos.letra as grupo',
                'modalidad.nombre as modalidad',
                
                // Información de servicio social
                'programa_servicio_social.nombre_programa',
                'programa_servicio_social.encargado_nombre',
                'programa_servicio_social.puesto_encargado',
                'programa_servicio_social.telefono_institucion',
                'programa_servicio_social.fecha_inicio',
                'programa_servicio_social.fecha_final',
                'instituciones.nombre as institucion',
                'titulos.titulo',
                'metodo_servicio.metodo',
                'tipos_programa.tipo as tipo_programa'
            ]);
    }

    private function applyAlumnoFilters($query, $request)
    {
        // Filtros de información personal
        if ($request->filled('nombre')) {
            $query->where('alumno.nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('apellidos')) {
            $query->where(function($q) use ($request) {
                $q->where('alumno.apellido_p', 'like', '%' . $request->apellidos . '%')
                  ->orWhere('alumno.apellido_m', 'like', '%' . $request->apellidos . '%');
            });
        }

        if ($request->filled('telefono')) {
            $query->where('alumno.telefono', 'like', '%' . $request->telefono . '%');
        }

        if ($request->filled('correo')) {
            $query->where('alumno.correo_institucional', 'like', '%' . $request->correo . '%');
        }

        // Filtros demográficos
        if ($request->filled('edad_id')) {
            $query->where('alumno.edad_id', $request->edad_id);
        }

        if ($request->filled('sexo_id')) {
            $query->where('alumno.sexo_id', $request->sexo_id);
        }

        if ($request->filled('rol_id')) {
            $query->where('alumno.rol_id', $request->rol_id);
        }

        if ($request->filled('status_id')) {
            $query->where('alumno.status_id', $request->status_id);
        }

        // Filtro de código postal - ahora en tabla ubicaciones
        if ($request->filled('cp')) {
            $query->where('ubicaciones.cp', 'like', '%' . $request->cp . '%');
        }

        // Filtros de fechas
        if ($request->filled('fecha_registro_desde')) {
            $query->whereDate('alumno.fecha_registro', '>=', $request->fecha_registro_desde);
        }

        if ($request->filled('fecha_registro_hasta')) {
            $query->whereDate('alumno.fecha_registro', '<=', $request->fecha_registro_hasta);
        }

        // Filtros académicos
        if ($request->filled('carrera_nombre')) {
            $query->where('carreras.nombre', $request->carrera_nombre);
        }

        if ($request->filled('semestre_nombre')) {
            $query->where('semestres.nombre', $request->semestre_nombre);
        }

        if ($request->filled('grupo_letra')) {
            $query->where('grupos.letra', $request->grupo_letra);
        }

        if ($request->filled('modalidad_nombre')) {
            $query->where('modalidad.nombre', $request->modalidad_nombre);
        }

        // Filtros de servicio social
        if ($request->filled('institucion_nombre')) {
            $query->where('instituciones.nombre', $request->institucion_nombre);
        }

        if ($request->filled('titulo_nombre')) {
            $query->where('titulos.titulo', $request->titulo_nombre);
        }

        if ($request->filled('metodo_nombre')) {
            $query->where('metodo_servicio.metodo', $request->metodo_nombre);
        }

        if ($request->filled('tipo_programa_nombre')) {
            $query->where('tipos_programa.tipo', $request->tipo_programa_nombre);
        }

        // Evitar duplicados
        $query->distinct();
    }

    private function getFilterOptions()
    {
        try {
            return [
                'edades' => DB::table('edad')->select('id', 'edades as nombre')->orderBy('edades')->get(),
                'sexos' => DB::table('sexo')->select('id', 'tipo as nombre')->orderBy('tipo')->get(),
                'roles' => DB::table('rol')->select('id', 'tipo as nombre')->orderBy('tipo')->get(),
                'status' => DB::table('status')->select('id', 'tipo as nombre')->orderBy('tipo')->get(),
                'carreras' => DB::table('carreras')->select('id', 'nombre')->orderBy('nombre')->get(),
                'semestres' => DB::table('semestres')->select('id', 'nombre')->orderBy('nombre')->get(),
                'grupos' => DB::table('grupos')->select('id', 'letra as nombre')->orderBy('letra')->get(),
                'modalidades' => DB::table('modalidad')->select('id', 'nombre')->orderBy('nombre')->get(),
                'instituciones' => DB::table('instituciones')->select('id', 'nombre')->orderBy('nombre')->get(),
                'titulos' => DB::table('titulos')->select('id', 'titulo', 'titulo as nombre')->orderBy('titulo')->get(),
                'metodos' => DB::table('metodo_servicio')->select('id', 'metodo', 'metodo as nombre')->orderBy('metodo')->get(),
                'tipos_programa' => DB::table('tipos_programa')->select('id', 'tipo', 'tipo as nombre')->orderBy('tipo')->get(),
            ];
        } catch (\Exception $e) {
            return [
                'edades' => collect([]),
                'sexos' => collect([]),
                'roles' => collect([]),
                'status' => collect([]),
                'carreras' => collect([]),
                'semestres' => collect([]),
                'grupos' => collect([]),
                'modalidades' => collect([]),
                'instituciones' => collect([]),
                'titulos' => collect([]),
                'metodos' => collect([]),
                'tipos_programa' => collect([]),
            ];
        }
    }

    public function getTables()
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $tableNames[] = $tableName;
        }
        
        return $tableNames;
    }

    public function getTableData($table, $page = 1, $perPage = 50)
    {
        if ($table === 'alumno') {
            $query = $this->buildAlumnoQuery();
        } else {
            $query = DB::table($table);
        }
        
        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function edit($table, $id)
    {
        if ($table === 'alumno') {
            return $this->editAlumno($id);
        }

        // Para otras tablas, usar el método genérico
        $record = DB::table($table)->where('id', $id)->first();
        
        if (!$record) {
            return redirect()->route('dashboard', ['table' => $table])
                           ->with('error', 'Registro no encontrado');
        }

        $foreignOptions = $this->getForeignKeyOptions($table);

        return view('record-edit', [
            'selectedTable' => $table,
            'record' => $record,
            'foreignOptions' => $foreignOptions
        ]);
    }

    private function editAlumno($id)
    {
        try {
            // 1. Obtener datos del alumno
            $alumno = DB::table('alumno')->where('id', $id)->first();
            
            if (!$alumno) {
                return redirect()->route('dashboard', ['table' => 'alumno'])
                               ->with('error', 'Alumno no encontrado');
            }

            // 2. Obtener datos relacionados
            $ubicacion = DB::table('ubicaciones')->where('alumno_id', $id)->first();
            $escolaridad = DB::table('escolaridad_alumno')->where('alumno_id', $id)->first();
            $programa = DB::table('programa_servicio_social')->where('alumno_id', $id)->first();

            // 3. Obtener opciones para los selects (Foreign Keys)
            $foreignOptions = [
                'edad_id' => DB::table('edad')->orderBy('edades')->get(),
                'sexo_id' => DB::table('sexo')->orderBy('tipo')->get(),
                'rol_id' => DB::table('rol')->orderBy('tipo')->get(),
                'status_id' => DB::table('status')->orderBy('nombre')->get(),
                'estado_id' => DB::table('estado')->orderBy('nombre')->get(),
                'municipios_id' => DB::table('municipios')->orderBy('nombre')->get(),
                'modalidad_id' => DB::table('modalidad')->orderBy('nombre')->get(),
                'carreras_id' => DB::table('carreras')->orderBy('nombre')->get(),
                'semestres_id' => DB::table('semestres')->orderBy('nombre')->get(),
                'grupos_id' => DB::table('grupos')->orderBy('letra')->get(),
                'instituciones_id' => DB::table('instituciones')->orderBy('nombre')->get(),
                'titulos_id' => DB::table('titulos')->orderBy('titulo')->get(),
                'metodo_servicio_id' => DB::table('metodo_servicio')->orderBy('metodo')->get(),
                'tipos_programa_id' => DB::table('tipos_programa')->orderBy('tipo')->get(),
            ];

            return view('alumno-edit', compact(
                'alumno',
                'ubicacion', 
                'escolaridad', 
                'programa', 
                'foreignOptions'
            ));

        } catch (\Exception $e) {
            return redirect()->route('dashboard', ['table' => 'alumno'])
                           ->with('error', 'Error al cargar el formulario de edición: ' . $e->getMessage());
        }
    }


public function update(Request $request, $table, $id)
{
    // ✅ LOG INICIAL - Siempre se ejecuta
    \Log::info('=== UPDATE MÉTODO INICIADO ===', [
        'table' => $table,
        'id' => $id,
        'method' => $request->method(),
        'all_data' => $request->all()
    ]);

    try {
        if ($table === 'alumno') {
            \Log::info('Redirigiendo a updateAlumnoFromRecord');
            return $this->updateAlumnoFromRecord($request, $id);
        }

        // Validaciones específicas por tabla
        $rules = $this->getValidationRules($table, 'update');
        \Log::info('Reglas de validación obtenidas', ['rules' => $rules]);
        
        $request->validate($rules);
        \Log::info('Validación exitosa');

        // Preparar datos para actualización
        $data = $request->except(['_token', '_method']);
        
        \Log::info('Datos preparados (antes de procesar)', [
            'tabla' => $table,
            'data_keys' => array_keys($data),
            'password_existe' => isset($data['password']),
            'password_valor' => isset($data['password']) ? $data['password'] : 'NO EXISTE'
        ]);
        
        // Agregar timestamp de actualización si la tabla lo soporta
        if (Schema::hasColumn($table, 'updated_at')) {
            $data['updated_at'] = now();
        }

        // Lógica específica para tabla alumno
        if ($table === 'alumno') {
            unset($data['fecha_registro']);
            
            if (isset($data['correo_institucional']) && !str_ends_with($data['correo_institucional'], '@cbta256.edu.mx')) {
                throw new \Exception('El correo debe terminar en @cbta256.edu.mx');
            }
        }

        // ✅ PROCESAR CONTRASEÑA PARA TABLA USUARIO
        if ($table === 'usuario') {
            \Log::info('=== PROCESANDO CONTRASEÑA DE USUARIO ===');
            \Log::info('Datos recibidos completos:', $data);
            
            if (array_key_exists('password', $data)) {
                $password = trim($data['password']);
                
                \Log::info('Password encontrado:', [
                    'valor_original' => $data['password'],
                    'valor_trimmed' => $password,
                    'es_vacio' => empty($password),
                    'longitud' => strlen($password)
                ]);
                
                if (empty($password)) {
                    \Log::info('❌ Password vacío - NO se actualizará');
                    unset($data['password']);
                } else {
                    \Log::info('✅ Encriptando nueva contraseña');
                    $data['password'] = bcrypt($password);
                    \Log::info('Password encriptado exitosamente:', [
                        'hash_inicio' => substr($data['password'], 0, 30) . '...',
                        'longitud_hash' => strlen($data['password'])
                    ]);
                }
            } else {
                \Log::info('⚠️ Campo password NO existe en los datos recibidos');
            }
            
            \Log::info('Datos finales a actualizar:', [
                'campos' => array_keys($data),
                'password_en_data' => isset($data['password'])
            ]);
        }

        // Actualizar registro
        \Log::info('Ejecutando UPDATE en base de datos', [
            'table' => $table,
            'id' => $id,
            'campos_a_actualizar' => array_keys($data)
        ]);
        
        $updated = DB::table($table)->where('id', $id)->update($data);

        \Log::info('Resultado del UPDATE', [
            'filas_afectadas' => $updated,
            'registro_existe' => DB::table($table)->where('id', $id)->exists()
        ]);

        if ($updated || DB::table($table)->where('id', $id)->exists()) {
            \Log::info('✅ Registro actualizado exitosamente');
            return redirect()->route('dashboard', ['table' => $table])
                           ->with('success', 'Registro actualizado exitosamente');
        } else {
            \Log::warning('⚠️ No se actualizó ningún registro');
            return redirect()->back()
                           ->with('error', 'No se pudo actualizar el registro')
                           ->withInput();
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ Error de validación:', [
            'errors' => $e->errors()
        ]);
        throw $e;
        
    } catch (\Exception $e) {
        \Log::error('❌ Error al actualizar:', [
            'table' => $table,
            'id' => $id,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
                       ->with('error', 'Error al actualizar: ' . $e->getMessage())
                       ->withInput();
    }
}
    private function updateAlumnoFromRecord(Request $request, $id)
    {
        try {
            // Debug: Ver qué datos llegan
            \Log::info('Datos recibidos:', $request->all());

            // Usar la misma validación que updateAlumno
            $request->validate([
                // Datos personales
                'nombre' => 'required|string|max:45',
                'apellido_p' => 'required|string|max:45',
                'apellido_m' => 'required|string|max:45',
                'correo_institucional' => 'required|email|max:255',
                'telefono' => 'required|digits:10',
                'edad_id' => 'required|exists:edad,id',
                'sexo_id' => 'required|exists:sexo,id',
                'rol_id' => 'required|exists:rol,id',
                
                // Ubicación
                'ubicacion_localidad' => 'required|string|max:100',
                'ubicacion_cp' => 'required|digits:5',
                'ubicacion_municipios_id' => 'required|exists:municipios,id',
                
                // Escolaridad
                'escolaridad_numero_control' => 'required|string|max:14',
                'escolaridad_meses_servicio' => 'required|integer|min:1|max:12',
                'escolaridad_modalidad_id' => 'required|exists:modalidad,id',
                'escolaridad_carreras_id' => 'required|exists:carreras,id',
                'escolaridad_semestres_id' => 'required|exists:semestres,id',
                'escolaridad_grupos_id' => 'required|exists:grupos,id',
                
                // Programa
                'programa_instituciones_id' => 'required|exists:instituciones,id',
                'programa_nombre_programa' => 'required|string|max:255',
                'programa_encargado_nombre' => 'required|string|max:100',
                'programa_titulos_id' => 'required|exists:titulos,id',
                'programa_puesto_encargado' => 'required|string|max:100',
                'programa_telefono_institucion' => 'required|digits:10',
                'programa_metodo_servicio_id' => 'required|exists:metodo_servicio,id',
                'programa_fecha_inicio' => 'required|date',
                'programa_fecha_final' => 'required|date|after:programa_fecha_inicio',
                'programa_tipos_programa_id' => 'required|exists:tipos_programa,id',
                
                // Campos opcionales
                'programa_otra_institucion' => 'nullable|string|max:255',
                'programa_otro_programa' => 'nullable|string|max:255',
            ]);

            // Validación manual del correo (más controlada)
            if (!str_ends_with($request->correo_institucional, '@cbta256.edu.mx')) {
                return redirect()->back()
                    ->withErrors(['correo_institucional' => 'El correo debe terminar en @cbta256.edu.mx'])
                    ->withInput();
            }

            // Ejecutar la transacción
            DB::transaction(function() use ($request, $id) {
                // 1. Actualizar datos del alumno
                $alumnoData = [
                    'nombre' => $request->nombre,
                    'apellido_p' => $request->apellido_p,
                    'apellido_m' => $request->apellido_m,
                    'correo_institucional' => $request->correo_institucional,
                    'telefono' => $request->telefono,
                    'edad_id' => $request->edad_id,
                    'sexo_id' => $request->sexo_id,
                    'rol_id' => $request->rol_id,
                ];

                \Log::info('Actualizando alumno con datos:', $alumnoData);
                $alumnoUpdated = DB::table('alumno')->where('id', $id)->update($alumnoData);
                \Log::info('Alumno actualizado:', ['success' => $alumnoUpdated]);

                // 2. Actualizar/Insertar ubicación
                $ubicacionData = [
                    'alumno_id' => $id,
                    'localidad' => $request->ubicacion_localidad,
                    'cp' => $request->ubicacion_cp,
                    'municipios_id' => $request->ubicacion_municipios_id,
                ];

                \Log::info('Actualizando ubicación con datos:', $ubicacionData);
                DB::table('ubicaciones')->updateOrInsert(
                    ['alumno_id' => $id],
                    $ubicacionData
                );

                // 3. Actualizar/Insertar escolaridad
                $escolaridadData = [
                    'alumno_id' => $id,
                    'numero_control' => $request->escolaridad_numero_control,
                    'meses_servicio' => $request->escolaridad_meses_servicio,
                    'modalidad_id' => $request->escolaridad_modalidad_id,
                    'carreras_id' => $request->escolaridad_carreras_id,
                    'semestres_id' => $request->escolaridad_semestres_id,
                    'grupos_id' => $request->escolaridad_grupos_id,
                ];

                \Log::info('Actualizando escolaridad con datos:', $escolaridadData);
                DB::table('escolaridad_alumno')->updateOrInsert(
                    ['alumno_id' => $id],
                    $escolaridadData
                );

                // 4. Actualizar/Insertar programa
                $programaData = [
                    'alumno_id' => $id,
                    'instituciones_id' => $request->programa_instituciones_id,
                    'otra_institucion' => $request->programa_otra_institucion,
                    'nombre_programa' => $request->programa_nombre_programa,
                    'encargado_nombre' => $request->programa_encargado_nombre,
                    'titulos_id' => $request->programa_titulos_id,
                    'puesto_encargado' => $request->programa_puesto_encargado,
                    'telefono_institucion' => $request->programa_telefono_institucion,
                    'metodo_servicio_id' => $request->programa_metodo_servicio_id,
                    'fecha_inicio' => $request->programa_fecha_inicio,
                    'fecha_final' => $request->programa_fecha_final,
                    'tipos_programa_id' => $request->programa_tipos_programa_id,
                    'otro_programa' => $request->programa_otro_programa,
                    'status_id' => 1, // Status por defecto
                ];

                \Log::info('Actualizando programa con datos:', $programaData);
                DB::table('programa_servicio_social')->updateOrInsert(
                    ['alumno_id' => $id],
                    $programaData
                );

                \Log::info('Transacción completada exitosamente');
            });

            // Si llegamos aquí, la transacción fue exitosa
            return redirect()->route('dashboard', ['table' => 'alumno'])
                       ->with('success', 'Información del alumno actualizada exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación:', $e->errors());
            return redirect()->back()
                       ->withErrors($e->errors())
                       ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al actualizar alumno:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                       ->with('error', 'Error al actualizar: ' . $e->getMessage())
                       ->withInput();
        }
    }

    // Agregar método para eliminar registros
    public function delete($table, $id)
    {
        \Log::info('=== DELETE DATABASEOVERVIEWCONTROLLER EJECUTADO ===');
        \Log::info('Tabla: ' . $table);
        \Log::info('ID: ' . $id);
        \Log::info('URL: ' . request()->fullUrl());
        
        try {
            // Validar que la tabla sea permitida
            $allowedTables = [
                'alumno', 'ubicaciones', 'escolaridad_alumno', 
                'programa_servicio_social', 'instituciones', 
                'municipios', 'estados', 'tipos_programa',
                'metodo_servicio', 'titulos', 'carreras',
                'modalidad', 'semestres', 'grupos', 'edad', 'sexo'
            ];
            
            if (!in_array($table, $allowedTables)) {
                \Log::error('Tabla no permitida: ' . $table);
                return redirect()->route('dashboard', ['table' => $table])
                    ->with('error', 'Tabla no permitida para eliminación');
            }

            \Log::info('Tabla permitida, procediendo...');

            if ($table === 'alumno') {
                \Log::info('Eliminando alumno...');
                return $this->deleteAlumno($id);
            }

            // Para instituciones, verificar dependencias
            if ($table === 'instituciones') {
                $dependencias = DB::table('programa_servicio_social')
                    ->where('instituciones_id', $id)
                    ->count();
                
                \Log::info('Verificando dependencias de institución. Encontradas: ' . $dependencias);
                
                if ($dependencias > 0) {
                    return redirect()->route('dashboard', ['table' => $table])
                        ->with('error', "No se puede eliminar la institución porque tiene {$dependencias} programa(s) de servicio social asociado(s).");
                }
            }

            if ($table === 'tipos_programa') {
                $dependencias = DB::table('programa_servicio_social')
                    ->where('tipos_programa_id', $id)
                    ->count();
                    
                \Log::info('Verificando dependencias de tipo programa. Encontradas: ' . $dependencias);
                
                if ($dependencias > 0) {
                    return redirect()->route('dashboard', ['table' => $table])
                        ->with('error', "No se puede eliminar el tipo de programa porque tiene {$dependencias} programa(s) de servicio social asociado(s).");
                }
            }

            // Verificar que el registro existe antes de eliminarlo
            $record = DB::table($table)->where('id', $id)->first();
            if (!$record) {
                \Log::error('Registro no encontrado para eliminar: ' . $table . ' ID: ' . $id);
                return redirect()->route('dashboard', ['table' => $table])
                    ->with('error', 'El registro que intenta eliminar no existe');
            }

            \Log::info('Registro encontrado, eliminando...');
            
            // Eliminar el registro
            $deleted = DB::table($table)->where('id', $id)->delete();
            \Log::info('Registros eliminados: ' . $deleted);
            
            if ($deleted) {
                \Log::info('Eliminación exitosa');
                return redirect()->route('dashboard', ['table' => $table])
                               ->with('success', 'Registro eliminado exitosamente');
            } else {
                \Log::error('No se pudo eliminar el registro');
                return redirect()->back()
                               ->with('error', 'No se pudo eliminar el registro');
            }

        } catch (\Exception $e) {
            \Log::error('Error al eliminar registro:', [
                'table' => $table,
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                           ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    private function deleteAlumno($id)
    {
        try {
            return DB::transaction(function() use ($id) {
                // Eliminar en orden de dependencias
                DB::table('programa_servicio_social')->where('alumno_id', $id)->delete();
                DB::table('escolaridad_alumno')->where('alumno_id', $id)->delete();
                DB::table('ubicaciones')->where('alumno_id', $id)->delete();
                
                // Finalmente eliminar el alumno
                $deleted = DB::table('alumno')->where('id', $id)->delete();
                
                return redirect()->route('dashboard', ['table' => 'alumno'])
                           ->with('success', 'Alumno y toda su información relacionada eliminada exitosamente');
            });

        } catch (\Exception $e) {
            \Log::error('Error al eliminar alumno:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                       ->with('error', 'Error al eliminar alumno: ' . $e->getMessage());
        }
    }

    // Método para autocompletado
    public function autocomplete($table, $column)
    {
        try {
            $query = request('q', '');
            
            $results = DB::table($table)
                ->where($column, 'like', '%' . $query . '%')
                ->distinct()
                ->limit(10)
                ->pluck($column);
                
            return response()->json($results);
            
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    // Método para crear nuevos registros
    public function create($table)
    {
        try {
            $foreignOptions = $this->getForeignKeyOptions($table);
            
            return view('record-create', [
                'selectedTable' => $table,
                'foreignOptions' => $foreignOptions
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard', ['table' => $table])
                           ->with('error', 'Error al cargar el formulario de creación: ' . $e->getMessage());
        }
    }

    // Método para guardar nuevos registros
    public function store(Request $request, $table)
    {
      
    try {
        $rules = $this->getValidationRules($table, 'create');
        $request->validate($rules);

        $data = $request->except(['_token']);
        
        // ✅ AGREGAR: Encriptar contraseña para tabla usuario
        if ($table === 'usuario' && isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        
        if (Schema::hasColumn($table, 'created_at')) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        $id = DB::table($table)->insertGetId($data);

        return redirect()->route('dashboard', ['table' => $table])
                       ->with('success', 'Registro creado exitosamente');

    } catch (\Exception $e) {
        return redirect()->back()
                       ->with('error', 'Error al crear registro: ' . $e->getMessage())
                       ->withInput();
    }
    }

    /**
     * Obtener reglas de validación para diferentes tablas
     */
    private function getValidationRules($table, $action = 'create')
    {
        $rules = [];
        
    switch($table) {
        case 'usuario':
            $rules = [
                'nombre' => 'required|string|max:45',
                'apellidoP' => 'required|string|max:45',
                'apellidoM' => 'required|string|max:45',
                'correo' => 'required|email|max:255' . ($action === 'create' ? '|unique:usuario,correo' : ''),
                'password' => ($action === 'create' ? 'required' : 'nullable') . '|string|min:8',
                'telefono' => 'required|digits:10',
                'rol_id' => 'required|exists:rol,id',
            ];
            break;
            
       
            case 'titulos':
                $rules = [
                    'titulo' => 'required|string|max:45' . ($action === 'update' ? '' : '|unique:titulos,titulo')
                ];
                break;
                
            case 'instituciones':
                $rules = [
                    'nombre' => 'required|string|max:100' . ($action === 'update' ? '' : '|unique:instituciones,nombre'),
                    'direccion' => 'nullable|string|max:255',
                    'telefono' => 'nullable|string|max:15'
                ];
                break;
                
            case 'carreras':
                $rules = [
                    'nombre' => 'required|string|max:100' . ($action === 'update' ? '' : '|unique:carreras,nombre'),
                    'descripcion' => 'nullable|string|max:255'
                ];
                break;
                
            case 'semestres':
                $rules = [
                    'nombre' => 'required|string|max:45' . ($action === 'update' ? '' : '|unique:semestres,nombre')
                ];
                break;
                
            case 'grupos':
                $rules = [
                    'letra' => 'required|string|max:5' . ($action === 'update' ? '' : '|unique:grupos,letra')
                ];
                break;
                
            case 'modalidad':
                $rules = [
                    'nombre' => 'required|string|max:45' . ($action === 'update' ? '' : '|unique:modalidad,nombre')
                ];
                break;
                
            case 'sexo':
                $rules = [
                    'tipo' => 'required|string|max:45' . ($action === 'update' ? '' : '|unique:sexo,tipo')
                ];
                break;
                
            case 'edad':
                $rules = [
                    'edades' => 'required|integer|min:15|max:70' . ($action === 'update' ? '' : '|unique:edad,edades')
                ];
                break;
                
            case 'rol':
                $rules = [
                    'tipo' => 'required|string|max:45' . ($action === 'update' ? '' : '|unique:rol,tipo')
                ];
                break;
                
            case 'status':
                $rules = [
                    'tipo' => 'required|string|max:45' . ($action === 'update' ? '' : '|unique:status,tipo')
                ];
                break;
                
            case 'metodo_servicio':
                $rules = [
                    'metodo' => 'required|string|max:100' . ($action === 'update' ? '' : '|unique:metodo_servicio,metodo')
                ];
                break;
                
            case 'tipos_programa':
                $rules = [
                    'tipo' => 'required|string|max:100' . ($action === 'update' ? '' : '|unique:tipos_programa,tipo')
                ];
                break;
                
            case 'estados':
                $rules = [
                    'nombre' => 'required|string|max:100' . ($action === 'update' ? '' : '|unique:estados,nombre'),
                    'clave' => 'nullable|string|max:5'
                ];
                break;
                
            case 'municipios':
                $rules = [
                    'nombre' => 'required|string|max:100',
                    'estado_id' => 'required|exists:estados,id'
                ];
                break;
                
            case 'programa_servicio_social':
                $rules = [
                    'alumno_id' => 'required|exists:alumno,id',
                    'instituciones_id' => 'required|exists:instituciones,id',
                    'otra_institucion' => 'nullable|string|max:50',
                    'nombre_programa' => 'required|string|max:45',
                    'encargado_nombre' => 'required|string|max:45',
                    'titulos_id' => 'required|exists:titulos,id',
                    'puesto_encargado' => 'required|string|max:45',
                    'metodo_servicio_id' => 'required|exists:metodo_servicio,id',
                    'telefono_institucion' => 'required|digits:10',
                    'fecha_inicio' => 'required|date',
                    'fecha_final' => 'required|date|after:fecha_inicio',
                    'tipos_programa_id' => 'required|exists:tipos_programa,id',
                    'otro_programa' => 'nullable|string|max:45',
                    'status_id' => 'required|exists:status,id',
                ];
                break;
                
            default:
                // Para tablas no específicas, crear reglas dinámicas
                $rules = $this->getDynamicValidationRules($table);
                break;
        }
        
        return $rules;
    }

    /**
     * Generar reglas de validación dinámicas basadas en la estructura de la tabla
     */
    private function getDynamicValidationRules($table)
    {
        $rules = [];
        
        try {
            $columns = DB::select("DESCRIBE $table");
            
            foreach ($columns as $column) {
                if ($column->Field === 'id') continue;
                
                $fieldRules = [];
                
                // Si el campo no puede ser nulo, es requerido
                if ($column->Null === 'NO' && $column->Default === null && $column->Extra !== 'auto_increment') {
                    $fieldRules[] = 'required';
                }
                
                // Determinar tipo de validación según el tipo de columna
                if (strpos($column->Type, 'varchar') !== false || strpos($column->Type, 'text') !== false) {
                    $fieldRules[] = 'string';
                    
                    // Extraer longitud máxima
                    if (preg_match('/varchar\((\d+)\)/', $column->Type, $matches)) {
                        $fieldRules[] = 'max:' . $matches[1];
                    }
                } elseif (strpos($column->Type, 'int') !== false) {
                    $fieldRules[] = 'integer';
                } elseif (strpos($column->Type, 'decimal') !== false || strpos($column->Type, 'float') !== false) {
                    $fieldRules[] = 'numeric';
                } elseif (strpos($column->Type, 'date') !== false) {
                    $fieldRules[] = 'date';
                } elseif (strpos($column->Type, 'time') !== false) {
                    $fieldRules[] = 'date_format:H:i:s';
                }
                
                // Campos específicos
                if (strpos($column->Field, 'email') !== false || strpos($column->Field, 'correo') !== false) {
                    $fieldRules[] = 'email';
                }
                
                if (!empty($fieldRules)) {
                    $rules[$column->Field] = implode('|', $fieldRules);
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Error generando reglas dinámicas para tabla ' . $table . ': ' . $e->getMessage());
        }
        
        return $rules;
    }

    /**
     * Obtener opciones para campos de llaves foráneas
     */
    private function getForeignKeyOptions($table)
    {
        $options = [];
        
        try {
            // Para la tabla municipios, agregar manualmente la relación con estados
            if ($table === 'municipios') {
                $options['estado_id'] = DB::table('estados')
                    ->select('id', 'nombre as display_name')
                    ->orderBy('nombre')
                    ->get();
            }
            
            // Obtener información de llaves foráneas desde la base de datos
            $foreignKeys = DB::select("
                SELECT 
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE REFERENCED_TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ?
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [$table]);
            
            foreach ($foreignKeys as $fk) {
                $referencedTable = $fk->REFERENCED_TABLE_NAME;
                $columnName = $fk->COLUMN_NAME;
                
                // Determinar qué columna mostrar como texto
                $displayColumn = $this->getDisplayColumnForTable($referencedTable);
                
                $options[$columnName] = DB::table($referencedTable)
                    ->select('id', $displayColumn . ' as display_name')
                    ->orderBy($displayColumn)
                    ->get();
            }
            
            // Agregar relaciones manuales para tablas específicas que podrían no tener FK definidas
            switch($table) {
                case 'ubicaciones':
                    if (!isset($options['municipios_id'])) {
                        $options['municipios_id'] = DB::table('municipios')
                            ->select('id', 'nombre as display_name')
                            ->orderBy('nombre')
                            ->get();
                    }
                    break;
                    
                case 'escolaridad_alumno':
                    if (!isset($options['alumno_id'])) {
                        $options['alumno_id'] = DB::table('alumno')
                            ->select('id', DB::raw("CONCAT(nombre, ' ', apellido_p, ' ', apellido_m) as display_name"))
                            ->orderBy('nombre')
                            ->get();
                    }
                    if (!isset($options['modalidad_id'])) {
                        $options['modalidad_id'] = DB::table('modalidad')
                            ->select('id', 'nombre as display_name')
                            ->orderBy('nombre')
                            ->get();
                    }
                    if (!isset($options['carreras_id'])) {
                        $options['carreras_id'] = DB::table('carreras')
                            ->select('id', 'nombre as display_name')
                            ->orderBy('nombre')
                            ->get();
                    }
                    if (!isset($options['semestres_id'])) {
                        $options['semestres_id'] = DB::table('semestres')
                            ->select('id', 'nombre as display_name')
                            ->orderBy('nombre')
                            ->get();
                    }
                    if (!isset($options['grupos_id'])) {
                        $options['grupos_id'] = DB::table('grupos')
                            ->select('id', 'letra as display_name')
                            ->orderBy('letra')
                            ->get();
                    }
                    break;
                    
                case 'programa_servicio_social':
                    if (!isset($options['alumno_id'])) {
                        $options['alumno_id'] = DB::table('alumno')
                            ->select('id', DB::raw("CONCAT(nombre, ' ', apellido_p, ' ', apellido_m) as display_name"))
                            ->orderBy('nombre')
                            ->get();
                    }
                    if (!isset($options['instituciones_id'])) {
                        $options['instituciones_id'] = DB::table('instituciones')
                            ->select('id', 'nombre as display_name')
                            ->orderBy('nombre')
                            ->get();
                    }
                    if (!isset($options['titulos_id'])) {
                        $options['titulos_id'] = DB::table('titulos')
                            ->select('id', 'titulo as display_name')
                            ->orderBy('titulo')
                            ->get();
                    }
                    if (!isset($options['metodo_servicio_id'])) {
                        $options['metodo_servicio_id'] = DB::table('metodo_servicio')
                            ->select('id', 'metodo as display_name')
                            ->orderBy('metodo')
                            ->get();
                    }
                    if (!isset($options['tipos_programa_id'])) {
                        $options['tipos_programa_id'] = DB::table('tipos_programa')
                            ->select('id', 'tipo as display_name')
                            ->orderBy('tipo')
                            ->get();
                    }
                    break;
                    
                case 'alumno':
                    if (!isset($options['edad_id'])) {
                        $options['edad_id'] = DB::table('edad')
                            ->select('id', 'edades as display_name')
                            ->orderBy('edades')
                            ->get();
                    }
                    if (!isset($options['sexo_id'])) {
                        $options['sexo_id'] = DB::table('sexo')
                            ->select('id', 'tipo as display_name')
                            ->orderBy('tipo')
                            ->get();
                    }
                    if (!isset($options['rol_id'])) {
                        $options['rol_id'] = DB::table('rol')
                            ->select('id', 'tipo as display_name')
                            ->orderBy('tipo')
                            ->get();
                    }
                    if (!isset($options['status_id'])) {
                        $options['status_id'] = DB::table('status')
                            ->select('id', 'tipo as display_name')
                            ->orderBy('tipo')
                            ->get();
                    }
                    break;
            }
            
        } catch (\Exception $e) {
            \Log::error('Error obteniendo opciones de FK para tabla ' . $table . ': ' . $e->getMessage());
        }
        
        return $options;
    }

    /**
     * Determinar qué columna usar como display para cada tabla
     */
    private function getDisplayColumnForTable($table)
    {
        $displayColumns = [
            'edad' => 'edades',
            'sexo' => 'tipo',
            'rol' => 'tipo',
            'status' => 'tipo',
            'estados' => 'nombre',
            'municipios' => 'nombre',
            'carreras' => 'nombre',
            'semestres' => 'nombre',
            'grupos' => 'letra',
            'modalidad' => 'nombre',
            'instituciones' => 'nombre',
            'titulos' => 'titulo',
            'metodo_servicio' => 'metodo',
            'tipos_programa' => 'tipo',
            'alumno' => 'nombre', // Para mostrar el nombre del alumno
        ];
        
        return $displayColumns[$table] ?? 'nombre';
    }

    /**
     * Método auxiliar para actualizar registros genéricos
     */
    public function updateGeneric(Request $request, $table, $id)
    {
        try {
            // Validaciones específicas por tabla
            $rules = $this->getValidationRules($table, 'update');
            $request->validate($rules);

            // Preparar datos para actualización
            $data = $request->except(['_token', '_method']);
            
            // Agregar timestamp de actualización si la tabla lo soporta
            if (Schema::hasColumn($table, 'updated_at')) {
                $data['updated_at'] = now();
            }

            // Actualizar registro
            $updated = DB::table($table)->where('id', $id)->update($data);

            if ($updated) {
                return redirect()->route('dashboard', ['table' => $table])
                               ->with('success', 'Registro actualizado exitosamente');
            } else {
                return redirect()->back()
                               ->with('error', 'No se pudo actualizar el registro')
                               ->withInput();
            }

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar: ' . $e->getMessage())
                           ->withInput();
        }
    }

public function updateRecord(Request $request, $table, $id)
{
    \Log::info('=== updateRecord MÉTODO INICIADO ===', [
        'table' => $table,
        'id' => $id,
        'all_data' => $request->all()
    ]);
    
    try {
        // Validación especial para programa_servicio_social ANTES de la validación general
        if ($table === 'programa_servicio_social') {
            $currentRecord = DB::table($table)->where('id', $id)->first();
            
            $fechaFinal = \Carbon\Carbon::parse($request->fecha_final ?? $currentRecord->fecha_final)->startOfDay();
            $hoy = \Carbon\Carbon::now()->startOfDay();
            
            \Log::info('=== VALIDACIÓN SERVIDOR ===');
            \Log::info('Fecha final del programa: ' . $fechaFinal->format('Y-m-d'));
            \Log::info('Fecha de hoy: ' . $hoy->format('Y-m-d'));
            \Log::info('¿Fecha pasada?: ' . ($hoy->gt($fechaFinal) ? 'SÍ' : 'NO'));
            \Log::info('Status solicitado: ' . ($request->status_id ?? 'No especificado'));
            
            if ($hoy->gt($fechaFinal) && isset($request->status_id) && $request->status_id == 3) {
                // ✅ CAMBIAR: Redirigir al dashboard con error
                return redirect()->route('dashboard', ['table' => $table])
                    ->withErrors([
                        'status_id' => 'No se puede marcar como "En proceso" cuando la fecha de finalización ya ha pasado.'
                    ]);
            }
        }

        // Validación general
        $rules = $this->getValidationRules($table, 'update');
        $validatedData = $request->validate($rules);
        
        \Log::info('Datos validados:', $validatedData);

        // ✅ PROCESAR CONTRASEÑA PARA TABLA USUARIO
        if ($table === 'usuario') {
            \Log::info('=== PROCESANDO CONTRASEÑA DE USUARIO ===');
            
            if (array_key_exists('password', $validatedData)) {
                $password = trim($validatedData['password']);
                
                \Log::info('Password encontrado:', [
                    'valor_original' => $validatedData['password'],
                    'valor_trimmed' => $password,
                    'es_vacio' => empty($password),
                    'longitud' => strlen($password)
                ]);
                
                if (empty($password)) {
                    \Log::info('❌ Password vacío - NO se actualizará');
                    unset($validatedData['password']);
                } else {
                    \Log::info('✅ Encriptando nueva contraseña');
                    $validatedData['password'] = bcrypt($password);
                    \Log::info('Password encriptado exitosamente:', [
                        'hash_inicio' => substr($validatedData['password'], 0, 30) . '...',
                        'longitud_hash' => strlen($validatedData['password'])
                    ]);
                }
            } else {
                \Log::info('⚠️ Campo password NO existe en validatedData');
            }
        }

        \Log::info('Datos finales a actualizar:', $validatedData);

        // Actualizar el registro
        $updated = DB::table($table)->where('id', $id)->update($validatedData);
        
        \Log::info('Resultado UPDATE:', ['filas_afectadas' => $updated]);
        
        if ($updated || DB::table($table)->where('id', $id)->exists()) {
            // Verificar la actualización
            $updatedRecord = DB::table($table)->where('id', $id)->first();
            \Log::info('Registro después de actualizar:', (array)$updatedRecord);

            // ✅ CAMBIAR: Redirigir al dashboard en lugar de back()
            \Log::info('✅ Redirigiendo al dashboard con tabla: ' . $table);
            return redirect()->route('dashboard', ['table' => $table])
                ->with('success', 'Registro actualizado correctamente');
        } else {
            \Log::warning('⚠️ No se actualizó ningún registro');
            
            // ✅ CAMBIAR: Redirigir al dashboard con mensaje de info
            return redirect()->route('dashboard', ['table' => $table])
                ->with('info', 'No se detectaron cambios en el registro');
        }

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ Error de validación:', ['errors' => $e->errors()]);
        
        // ✅ CAMBIAR: Redirigir al dashboard con errores
        return redirect()->route('dashboard', ['table' => $table])
            ->withErrors($e->validator)
            ->with('error', 'Error de validación. Revise los campos.');
            
    } catch (\Exception $e) {
        \Log::error('❌ Error al actualizar registro:', [
            'table' => $table,
            'id' => $id,
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
         // ✅ CAMBIAR: Redirigir al dashboard con errores
        return redirect()->route('dashboard', ['table' => $table])
            ->withErrors($e->validator)
            ->with('error', 'Error de validación. Revise los campos.');
            
    } catch (\Exception $e) {
        \Log::error('❌ Error al actualizar registro:', [
            'table' => $table,
            'id' => $id,
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ]);
        
        // ✅ CAMBIAR: Redirigir al dashboard con error
        return redirect()->route('dashboard', ['table' => $table])
            ->with('error', 'Error al actualizar el registro: ' . $e->getMessage());
    }
}

    // AGREGAR este método si no existe
public function cancelarAlumno($id)
{
    try {
        \Log::info('=== CANCELANDO ALUMNO ===');
        \Log::info('ID del alumno: ' . $id);
        \Log::info('Método HTTP: ' . request()->method());
        \Log::info('URL completa: ' . request()->fullUrl());
        
        // Verificar que el alumno existe
        $alumno = DB::table('alumno')->where('id', $id)->first();
        if (!$alumno) {
            \Log::error('Alumno no encontrado con ID: ' . $id);
            return redirect()->route('dashboard', ['table' => 'alumno'])
                ->with('error', 'Alumno no encontrado');
        }

        \Log::info('Alumno encontrado:', (array)$alumno);
        
        // Cambiar status a "Eliminado" (asumiendo que el ID 2 es "Eliminado")
        $result = DB::table('alumno')
            ->where('id', $id)
            ->update(['status_id' => 2]);
        
        \Log::info('Resultado de la actualización. Filas afectadas: ' . $result);
        
        if ($result > 0) {
            \Log::info('Alumno cancelado exitosamente');
            return redirect()->route('dashboard', ['table' => 'alumno'])
                ->with('success', 'Alumno eliminado exitosamente');
        } else {
            \Log::warning('No se actualizó ninguna fila. Posiblemente ya tenía status_id = 2');
            return redirect()->route('dashboard', ['table' => 'alumno'])
                ->with('info', 'El alumno ya estaba marcado como eliminado');
        }
        
    } catch (\Exception $e) {
        \Log::error('Error al cancelar alumno:', [
            'id' => $id,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('dashboard', ['table' => 'alumno'])
            ->with('error', 'Error al eliminar el alumno: ' . $e->getMessage());
    }
}
}