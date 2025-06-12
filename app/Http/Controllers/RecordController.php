<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecordController extends Controller
{
    public function create($table)
    {
        try {
            // Caso especial para formatos
            if ($table === 'formatos') {
                return redirect()->route('formatos.upload');
            }

            // Obtener estructura de la tabla
            $record = $this->getTableStructure($table);
            
            return view('record-create', [
                'selectedTable' => $table,
                'record' => $record
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating record form: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar el formulario');
        }
    }

    public function store(Request $request, $table)
    {
        try {
            if ($table === 'alumno') {
                return $this->storeAlumnoCompleto($request);
            } else {
                return $this->storeGeneric($request, $table);
            }
        } catch (\Exception $e) {
            Log::error('Error storing record: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al guardar el registro: ' . $e->getMessage()])->withInput();
        }
    }

    private function storeAlumnoCompleto(Request $request)
    {
        // Validación completa para alumno - CORREGIDA según estructura real de BD
        $validated = $request->validate([
            // Datos personales del alumno (según estructura real)
            'correo_institucional' => 'required|email|max:45|unique:alumno,correo_institucional',
            'apellido_p' => 'required|string|max:45',
            'apellido_m' => 'required|string|max:45', 
            'nombre' => 'required|string|max:45',
            'telefono' => 'required|integer',
            'sexo_id' => 'required|exists:sexo,id',
            'edad_id' => 'required|exists:edad,id',
            'rol_id' => 'required|exists:rol,id',
            
            // Campos de ubicación (para tabla ubicaciones)
            'localidad' => 'required|string|max:60',
            'cp' => 'required|integer',
            'municipio_id' => 'required|exists:municipios,id',
            
            // Datos de escolaridad
            'numero_control' => 'required|integer|unique:escolaridad_alumno,numero_control',
            'meses_servicio' => 'required|integer|min:1|max:24',
            'carreras_id' => 'required|exists:carreras,id',
            'semestres_id' => 'required|exists:semestres,id',
            'grupos_id' => 'required|exists:grupos,id',
            'modalidad_id' => 'required|exists:modalidad,id',
            
            // Datos del programa de servicio social
            'nombre_programa' => 'required|string|max:45',
            'encargado_nombre' => 'required|string|max:45',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after:fecha_inicio',
            'instituciones_id' => 'required|exists:instituciones,id',
            'titulos_id' => 'required|exists:titulos,id',
            'puesto_encargado' => 'required|string|max:45',
            'metodo_servicio_id' => 'required|exists:metodo_servicio,id',
            'tipos_programa_id' => 'required|exists:tipos_programa,id',
            'telefono_institucion' => 'required|integer',
            
            // Campos opcionales
            'otra_institucion' => 'nullable|string|max:50',
            'otro_programa' => 'nullable|string|max:45',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Crear alumno PRIMERO para obtener su ID
            $alumnoId = DB::table('alumno')->insertGetId([
                'correo_institucional' => $validated['correo_institucional'],
                'apellido_p' => $validated['apellido_p'],
                'apellido_m' => $validated['apellido_m'],
                'nombre' => $validated['nombre'],
                'telefono' => $validated['telefono'],
                'fecha_registro' => now()->setTimezone('America/Mexico_City'),
                'sexo_id' => $validated['sexo_id'],
                'status_id' => 1,
                'edad_id' => $validated['edad_id'],
                'rol_id' => $validated['rol_id'],
            ]);

            // 2. Crear ubicación DESPUÉS del alumno (con alumno_id)
            DB::table('ubicaciones')->insert([
                'alumno_id' => $alumnoId, // ✅ CORREGIDO: ahora ubicaciones tiene alumno_id
                'localidad' => $validated['localidad'],
                'cp' => $validated['cp'],
                'municipios_id' => $validated['municipio_id'],
            ]);

            // 3. Crear escolaridad del alumno
            DB::table('escolaridad_alumno')->insert([
                'numero_control' => $validated['numero_control'],
                'meses_servicio' => $validated['meses_servicio'],
                'alumno_id' => $alumnoId,
                'modalidad_id' => $validated['modalidad_id'],
                'carreras_id' => $validated['carreras_id'],
                'semestres_id' => $validated['semestres_id'],
                'grupos_id' => $validated['grupos_id'],
            ]);

            // 4. Crear programa de servicio social
            DB::table('programa_servicio_social')->insert([
                'alumno_id' => $alumnoId,
                'instituciones_id' => $validated['instituciones_id'],
                'otra_institucion' => $validated['otra_institucion'],
                'nombre_programa' => $validated['nombre_programa'],
                'encargado_nombre' => $validated['encargado_nombre'],
                'titulos_id' => $validated['titulos_id'],
                'puesto_encargado' => $validated['puesto_encargado'],
                'metodo_servicio_id' => $validated['metodo_servicio_id'],
                'telefono_institucion' => $validated['telefono_institucion'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_final' => $validated['fecha_final'],
                'tipos_programa_id' => $validated['tipos_programa_id'],
                'otro_programa' => $validated['otro_programa'],
                'status_id' => 1,
            ]);

            return redirect()->route('dashboard', ['table' => 'alumno'])
                           ->with('success', 'Alumno registrado exitosamente con toda su información académica y de servicio social');
        });
    }

    private function storeGeneric(Request $request, $table)
    {
        // Obtener campos de la tabla
        $columns = DB::select("DESCRIBE $table");
        $fillableColumns = [];
        
        foreach ($columns as $column) {
            if ($column->Field !== 'id') {
                $fillableColumns[] = $column->Field;
            }
        }

        // Validar y preparar datos
        $data = $request->only($fillableColumns);
        
        // Limpiar valores vacíos
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });

        // Agregar fecha de registro si es necesario
        if (in_array('fecha_registro', $fillableColumns) && !isset($data['fecha_registro'])) {
            $data['fecha_registro'] = now()->setTimezone('America/Mexico_City');
        }

        // Insertar registro
        DB::table($table)->insert($data);

        return redirect()->route('dashboard', ['table' => $table])
                       ->with('success', 'Registro creado exitosamente');
    }

    private function getTableStructure($table)
    {
        try {
            $columns = DB::select("DESCRIBE $table");
            $structure = [];
            
            foreach ($columns as $column) {
                if ($column->Field !== 'id') {
                    $structure[$column->Field] = '';
                }
            }
            
            return $structure;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function edit($table, $id)
    {
        try {
            $record = DB::table($table)->where('id', $id)->first();
            
            if (!$record) {
                return redirect()->route('dashboard', ['table' => $table])
                               ->with('error', 'Registro no encontrado');
            }

            return view('record-edit', [
                'selectedTable' => $table,
                'record' => $record
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing record: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar el registro');
        }
    }

    public function update(Request $request, $table, $id)
    {
        try {
            // Obtener campos de la tabla
            $columns = DB::select("DESCRIBE $table");
            $fillableColumns = [];
            
            foreach ($columns as $column) {
                if ($column->Field !== 'id') {
                    $fillableColumns[] = $column->Field;
                }
            }

            // Validar y preparar datos
            $data = $request->only($fillableColumns);
            
            // Limpiar valores vacíos
            $data = array_filter($data, function($value) {
                return $value !== null && $value !== '';
            });

            // Actualizar registro
            DB::table($table)->where('id', $id)->update($data);

            return redirect()->route('dashboard', ['table' => $table])
                           ->with('success', 'Registro actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error updating record: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al actualizar el registro: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($table, $id)
    {
        try {
            DB::table($table)->where('id', $id)->delete();

            return redirect()->route('dashboard', ['table' => $table])
                           ->with('success', 'Registro eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error deleting record: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }
}