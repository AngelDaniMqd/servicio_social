<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        // agrega más mapeos según tus tablas
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
        // Crear un objeto vacío (con valores en blanco) para cada columna excepto id
        $record = new \stdClass;
        foreach($columns as $column){
            if($column !== 'id'){
                $record->$column = "";
            }
        }

        // Obtener opciones para campos foráneos (si existen)
        $foreignOptions = [];
        if(isset($this->fkMappings[$table])){
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
        ]);
    }

    // Método para almacenar el nuevo registro
    public function store(Request $request, $table)
    {
        // Obtener todos los datos del formulario (excepto _token)
        $data = $request->except('_token');
        DB::table($table)->insert($data);
        return redirect()->route('dashboard', ['table' => $table])
            ->with('success', 'Registro creado exitosamente');
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
}