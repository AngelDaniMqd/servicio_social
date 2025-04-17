<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DatabaseOverviewController extends Controller
{
    // Definir el mapeo de llaves foráneas para reutilizarlo en todos los métodos
    protected $fkMappings = [
        'usuario' => [
            'rol_id' => [
                'table' => 'rol',
                'display_column' => 'tipo'
            ]
        ],
        'escolaridad_alumno' => [
            'alumno_id' => [
                'table' => 'alumno',
                'display_column' => 'nombre'
            ],
        ],
        'municipios' => [
            'estado_id' => [
                'table' => 'estados',
                'display_column' => 'nombre'
            ]
        ],
        // Nuevo mapeo para la tabla alumno:
        'alumno' => [
            'sexo_id'                => [
                'table' => 'sexo',
                'display_column' => 'tipo'
            ],
            'status_id'              => [
                'table' => 'status',
                'display_column' => 'tipo'
            ],
            'edad_id'                => [
                'table' => 'edad',
                'display_column' => 'edades' // Ajustar según el nombre de la columna real
            ],
            'rol_id'                 => [
                'table' => 'rol',
                'display_column' => 'tipo'
            ],
            'instituciones_id'       => [
                'table' => 'instituciones',
                'display_column' => 'nombre'
            ],
            'titulos_id'             => [
                'table' => 'titulos',
                'display_column' => 'titulo'
            ],
            'metodo_servicio_id'     => [
                'table' => 'metodo_servicio',
                'display_column' => 'metodo'
            ],
            'programa_status_id'     => [
                'table' => 'status',
                'display_column' => 'tipo'
            ],
            'escolaridad_modalidad_id'=> [
                'table' => 'modalidad',
                'display_column' => 'nombre'
            ],
            'escolaridad_carreras_id' => [
                'table' => 'carreras',
                'display_column' => 'nombre'
            ],
            'escolaridad_semestres_id'=> [
                'table' => 'semestres',
                'display_column' => 'nombre'
            ],
            'escolaridad_grupos_id'   => [
                'table' => 'grupos',
                'display_column' => 'letra'
            ],
        ],
        // agrega otros mapeos según sea necesario
    ];

    public function index(Request $request)
    {
        $dbName = DB::getDatabaseName();

        // Obtener lista de tablas (para MySQL)
        $tablesRaw = DB::select("SHOW TABLES");
        $tableKey = "Tables_in_" . $dbName;
        $tables = array_map(function($table) use ($tableKey) {
            return $table->$tableKey;
        }, $tablesRaw);

        // Tabla seleccionada via query string ?table=
        $selectedTable = $request->query('table');
        if ($selectedTable && in_array($selectedTable, $tables)) {
            // Se elimina el limit para obtener todos los registros
            $rows = DB::table($selectedTable)->get();

            // Formatear las llaves foráneas usando el mapeo
            if(isset($this->fkMappings[$selectedTable])) {
                foreach($rows as $row) {
                    foreach($this->fkMappings[$selectedTable] as $field => $config) {
                        if(isset($row->$field)){
                            $foreignValue = DB::table($config['table'])
                                              ->where('id', $row->$field)
                                              ->value($config['display_column']);
                            $row->$field = $foreignValue;
                        }
                    }
                }
            }
        } else {
            $selectedTable = null;
            $rows = collect();
        }

        return view('database-overview', compact('tables', 'selectedTable', 'rows'));
    }

    // Método de ejemplo para editar un registro
    public function edit($table, $id)
    {
        // Recuperamos el registro a editar
        $record = DB::table($table)->where('id', $id)->first();
        if (!$record) {
            abort(404, "Registro no encontrado");
        }
        
        // Si hay mapeo para la tabla actual, obtenemos las opciones para cada campo foráneo
        $foreignOptions = [];
        if(isset($this->fkMappings[$table])){
            foreach($this->fkMappings[$table] as $field => $config) {
                $options = DB::table($config['table'])
                             ->select('id', $config['display_column'])
                             ->get();
                $foreignOptions[$field] = $options;
            }
        }
        
        return view('record-edit', [
            'selectedTable'   => $table,
            'record'          => $record,
            'foreignOptions'  => $foreignOptions
        ]);
    }

    // Método para mostrar el formulario de creación de un registro
    public function create($table)
    {
        // Obtener listado de columnas de la tabla
        $columns = Schema::getColumnListing($table);
        $record = new \stdClass;
        foreach($columns as $column){
            if($column !== 'id'){
                $record->$column = "";
            }
        }

        // Obtener opciones para campos foráneos (usado en select si lo prefieres) y además
        // pasar el mapeo para que se use en el autocompletado
        $foreignOptions = [];
        $foreignMapping = [];
        if(isset($this->fkMappings[$table])){
            $foreignMapping = $this->fkMappings[$table];
            foreach($this->fkMappings[$table] as $field => $config){
                $options = DB::table($config['table'])
                             ->select('id', $config['display_column'])
                             ->get();
                $foreignOptions[$field] = $options;
            }
        }

        return view('record-create', [
            'selectedTable'  => $table,
            'record'         => $record,
            'foreignOptions' => $foreignOptions,
            'foreignMapping' => $foreignMapping
        ]);
    }

    // Método para almacenar el nuevo registro
    public function store(Request $request, $table)
    {
        return DB::transaction(function() use ($request, $table) {
            // Se obtienen todos los datos del formulario
            $data = $request->except('_token');

            if ($table === 'alumno') {
                // Procesar campo fecha_registro (se espera un input de tipo date y se formatea a DATETIME)
                if (isset($data['fecha_registro'])) {
                    try {
                        if (strlen($data['fecha_registro']) == 10) {
                            $data['fecha_registro'] = Carbon::parse($data['fecha_registro'])
                                ->format('Y-m-d H:i:s');
                        }
                    } catch (\Exception $e) {
                        return redirect()->back()->withErrors("Formato de fecha incorrecto para 'fecha_registro'.");
                    }
                }

                // Extraer datos de UBICACIONES
                $ubicacionData = [
                    'localidad'    => $request->input('ubicacion_localidad'),
                    'cp'           => $request->input('ubicacion_cp'),
                    'municipios_id'=> $request->input('ubicacion_municipios_id'),
                ];
                // Insertar ubicación y obtener su id
                $ubicaciones_id = DB::table('ubicaciones')->insertGetId($ubicacionData);
                // Asignar al alumno el id obtenido de ubicaciones
                $data['ubicaciones_id'] = $ubicaciones_id;

                // Extraer y quitar campos de Ubicación para que no se inserten en la tabla alumno
                unset($data['ubicacion_localidad'], $data['ubicacion_cp'], $data['ubicacion_estado_id'], $data['ubicacion_municipios_id']);

                // Extraer datos de PROGRAMA SERVICIO SOCIAL
                $programaData = [
                    'alumno_id'            => null, // Se asignará después de insertar alumno
                    'instituciones_id'     => $request->input('programa_instituciones_id'),
                    'otra_institucion'     => $request->input('programa_otra_institucion'),
                    'nombre_programa'      => $request->input('programa_nombre_programa'),
                    'encargado_nombre'     => $request->input('programa_encargado_nombre'),
                    'titulos_id'           => $request->input('programa_titulos_id'),
                    'puesto_encargado'     => $request->input('programa_puesto_encargado'),
                    'metodo_servicio_id'   => $request->input('programa_metodo_servicio_id'),
                    'telefono_institucion' => $request->input('programa_telefono_institucion'),
                    'fecha_inicio'         => $request->input('programa_fecha_inicio'),
                    'fecha_final'          => $request->input('programa_fecha_final'),
                    'tipos_programa_id'    => $request->input('programa_tipos_programa_id'),
                    'otro_programa'        => $request->input('programa_otro_programa'),
                    'status_id'            => $request->input('programa_status_id'),
                ];
                // Convertir fechas de programa si fuese necesario
                if(isset($programaData['fecha_inicio']) && strlen($programaData['fecha_inicio']) == 10){
                    $programaData['fecha_inicio'] = Carbon::parse($programaData['fecha_inicio'])->format('Y-m-d H:i:s');
                }
                if(isset($programaData['fecha_final']) && strlen($programaData['fecha_final']) == 10){
                    $programaData['fecha_final'] = Carbon::parse($programaData['fecha_final'])->format('Y-m-d H:i:s');
                }
                // Quitar campos del array $data que pertenecen a programa para que no se inserten en alumno
                unset($data['programa_instituciones_id'], $data['programa_otra_institucion'], $data['programa_nombre_programa'],
                      $data['programa_encargado_nombre'], $data['programa_titulos_id'], $data['programa_puesto_encargado'],
                      $data['programa_metodo_servicio_id'], $data['programa_telefono_institucion'], $data['programa_fecha_inicio'],
                      $data['programa_fecha_final'], $data['programa_tipos_programa_id'], $data['programa_otro_programa'],
                      $data['programa_status_id']);

                // Extraer datos de ESCOLARIDAD ALUMNO
                $escolaridadData = [
                    'alumno_id'        => null, // Se asignará después de insertar alumno
                    'numero_control'   => $request->input('escolaridad_numero_control'),
                    'meses_servicio'   => $request->input('escolaridad_meses_servicio'),
                    'modalidad_id'     => $request->input('escolaridad_modalidad_id'),
                    'carreras_id'      => $request->input('escolaridad_carreras_id'),
                    'semestres_id'     => $request->input('escolaridad_semestres_id'),
                    'grupos_id'        => $request->input('escolaridad_grupos_id'),
                ];
                // Quitar campos escolaridad para alumno
                unset($data['escolaridad_numero_control'], $data['escolaridad_meses_servicio'], $data['escolaridad_modalidad_id'],
                      $data['escolaridad_carreras_id'], $data['escolaridad_semestres_id'], $data['escolaridad_grupos_id']);
            }

            // Insertar el registro en la tabla alumno y obtener su ID
            $alumno_id = DB::table($table)->insertGetId($data);

            if ($table === 'alumno') {
                // Asignar el alumno_id a programa y escolaridad
                $programaData['alumno_id'] = $alumno_id;
                DB::table('programa_servicio_social')->insert($programaData);

                $escolaridadData['alumno_id'] = $alumno_id;
                DB::table('escolaridad_alumno')->insert($escolaridadData);
            }

            return redirect()->route('dashboard', ['table' => $table])
                ->with('success', 'Registro creado exitosamente');
        });
    }

    // Método de ejemplo para eliminar un registro
    public function delete($table, $id)
    {
        // Realizar la eliminación (usar try/catch y validaciones en producción)
        DB::table($table)->where('id', $id)->delete();
        return redirect()->route('dashboard', ['table' => $table])->with('success', "Registro eliminado");
    }

    public function update(Request $request, $table, $id)
    {
        // Obtén todos los datos excepto _token y _method
        $data = $request->except(['_token', '_method']);

        // Actualiza el registro en la tabla dinámica
        DB::table($table)->where('id', $id)->update($data);

        // Redirige al dashboard o a la vista correspondiente con un mensaje de éxito
        return redirect()->route('dashboard', ['table' => $table])
            ->with('success', 'Registro actualizado exitosamente');
    }

    public function autocomplete(Request $request, $table, $column)
    {
        $term = $request->query('term');
        $data = DB::table($table)
                  ->select('id', $column)
                  ->where($column, 'LIKE', "%{$term}%")
                  ->limit(10)
                  ->get();
        return response()->json($data);
    }
}