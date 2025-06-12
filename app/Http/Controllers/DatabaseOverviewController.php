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
            }
            
            // Aplicar paginación con 50 registros por página
            $rows = $query->paginate(50)->appends($request->query());
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
            ->leftJoin('ubicaciones', 'alumno.id', '=', 'ubicaciones.alumno_id') // Cambio aquí
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
            ->select([
                // Información básica del alumno con nombres reales de columnas
                'alumno.id',
                'alumno.nombre',
                'alumno.apellido_p',
                'alumno.apellido_m',
                'alumno.correo_institucional',
                'alumno.telefono',
                'alumno.fecha_registro',
                
                // Reemplazar IDs con nombres descriptivos
                'edad.edades as edad',
                'sexo.tipo as sexo',
                'rol.tipo as rol',
                'status.tipo as status',
                
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

            // Lógica específica para tabla alumno
            if ($table === 'alumno') {
                // No actualizar fecha_registro si ya existe
                unset($data['fecha_registro']);
                
                // Validar correo institucional
                if (isset($data['correo_institucional']) && !str_ends_with($data['correo_institucional'], '@cbta256.edu.mx')) {
                    throw new \Exception('El correo debe terminar en @cbta256.edu.mx');
                }
            }

            // Manejar campos de contraseña
            if (isset($data['password']) && empty($data['password'])) {
                unset($data['password']); // No actualizar si está vacío
            } elseif (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']); // Encriptar nueva contraseña
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

    public function updateAlumno(Request $request, $id)
    {
        try {
            // Validar TODOS los datos
            $request->validate([
                // Datos personales
                'nombre' => 'required|string|max:45',
                'apellido_p' => 'required|string|max:45',
                'apellido_m' => 'required|string|max:45',
                'correo_institucional' => 'required|email|ends_with:@cbta256.edu.mx',
                'telefono' => 'required|digits:10',
                'edad_id' => 'required|exists:edad,id',
                'sexo_id' => 'required|exists:sexo,id',
                'rol_id' => 'required|exists:rol,id',
                'status_id' => 'nullable|exists:status,id',
                
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

            return DB::transaction(function() use ($request, $id) {
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
                    'status_id' => $request->status_id ?? 1,
                ];

                DB::table('alumno')->where('id', $id)->update($alumnoData);

                // 2. Actualizar/Insertar ubicación
                $ubicacionData = [
                    'alumno_id' => $id,
                    'localidad' => $request->ubicacion_localidad,
                    'cp' => $request->ubicacion_cp,
                    'municipios_id' => $request->ubicacion_municipios_id,
                ];

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

                DB::table('programa_servicio_social')->updateOrInsert(
                    ['alumno_id' => $id],
                    $programaData
                );

                return redirect()->route('dashboard', ['table' => 'alumno'])
                           ->with('success', 'Información del alumno actualizada exitosamente');
            });

        } catch (\Exception $e) {
            return redirect()->back()
                       ->with('error', 'Error al actualizar: ' . $e->getMessage())
                       ->withInput();
        }
    }

    private function getForeignKeyOptions($table)
    {
        $options = [];
        
        try {
            // Mapear campos con sus tablas relacionadas
            $foreignKeyMap = [
                'edad_id' => 'edad',
                'sexo_id' => 'sexo', 
                'rol_id' => 'rol',
                'status_id' => 'status',
                'estado_id' => 'estados',
                'municipio_id' => 'municipios',
                'municipios_id' => 'municipios',
                'carreras_id' => 'carreras',
                'semestres_id' => 'semestres',
                'grupos_id' => 'grupos',
                'modalidad_id' => 'modalidad',
                'instituciones_id' => 'instituciones',
                'titulos_id' => 'titulos',
                'metodo_servicio_id' => 'metodo_servicio',
                'tipos_programa_id' => 'tipos_programa',
            ];

            foreach ($foreignKeyMap as $field => $relatedTable) {
                if (Schema::hasTable($relatedTable)) {
                    $options[$field] = DB::table($relatedTable)->get();
                }
            }

        } catch (\Exception $e) {
            // Log error pero continuar
            \Log::error("Error getting foreign key options: " . $e->getMessage());
        }

        return $options;
    }

    private function getValidationRules($table, $operation = 'create')
    {
        $baseRules = [
            'alumno' => [
                'nombre' => 'required|string|max:45',
                'apellido_p' => 'required|string|max:45',
                'apellido_m' => 'required|string|max:45',
                'correo_institucional' => 'required|email|ends_with:@cbta256.edu.mx',
                'telefono' => 'required|digits:10',
                'edad_id' => 'required|exists:edad,id',
                'sexo_id' => 'required|exists:sexo,id',
                'rol_id' => 'required|exists:rol,id',
                'status_id' => 'required|exists:status,id',
            ],
            'instituciones' => [
                'nombre' => 'required|string|max:255',
                'direccion' => 'nullable|string|max:500',
                'telefono' => 'nullable|digits:10',
            ],
            'usuario' => [
                'nombre' => 'required|string|max:45',
                'apellidoP' => 'required|string|max:45',
                'apellidoM' => 'required|string|max:45',
                'correo' => 'required|email|unique:usuario,correo',
                'telefono' => 'required|digits:10',
                'rol_id' => 'required|exists:rol,id',
            ],
        ];

        // Modificar reglas para actualización
        if ($operation === 'update' && isset($baseRules[$table])) {
            $rules = $baseRules[$table];
            
            // Hacer contraseña opcional en updates
            if (isset($rules['password'])) {
                $rules['password'] = 'nullable|min:6';
            }
            
            // Remover unique constraint en updates para email (necesitaría except)
            if (isset($rules['correo'])) {
                $rules['correo'] = 'required|email';
            }
            
            return $rules;
        }

        return $baseRules[$table] ?? [];
    }
}