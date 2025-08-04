<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        // LOGS SIMPLES SIN EMOJIS
        \Log::info('=== DESTROY RECORDCONTROLLER EJECUTADO ===');
        \Log::info('Tabla: ' . $table);
        \Log::info('ID: ' . $id);
        \Log::info('URL: ' . request()->fullUrl());
        
        try {
            // ==================== VALIDACIÓN DE TABLA ====================
            $allowedTables = [
                'instituciones', 'tipos_programa', 'metodo_servicio', 
                'titulos', 'carreras', 'modalidad', 'semestres', 
                'grupos', 'edad', 'sexo', 'municipios', 'estados'
            ];
            
            \Log::info('📋 Tablas permitidas: ' . implode(', ', $allowedTables));
            
            if (!in_array($table, $allowedTables)) {
                \Log::error('❌ TABLA NO PERMITIDA: ' . $table);
                return redirect()->route('dashboard', ['table' => $table])
                    ->with('error', 'Tabla no permitida para eliminación: ' . $table);
            }

            \Log::info('✅ Tabla validada correctamente');

            // ==================== VERIFICAR QUE EL REGISTRO EXISTE ====================
            \Log::info('🔍 Verificando existencia del registro...');
            
            $record = DB::table($table)->where('id', $id)->first();
            
            if (!$record) {
                \Log::error('❌ REGISTRO NO ENCONTRADO');
                \Log::error('📊 Query ejecutada: SELECT * FROM ' . $table . ' WHERE id = ' . $id);
                
                // Verificar cuántos registros hay en la tabla
                $totalRecords = DB::table($table)->count();
                \Log::info('📈 Total de registros en ' . $table . ': ' . $totalRecords);
                
                // Mostrar IDs disponibles para debug
                $availableIds = DB::table($table)->pluck('id')->take(10);
                \Log::info('🆔 IDs disponibles (primeros 10): ' . $availableIds->implode(', '));
                
                return redirect()->route('dashboard', ['table' => $table])
                    ->with('error', "El registro ID {$id} no existe en la tabla {$table}");
            }

            \Log::info('✅ Registro encontrado:', (array)$record);

            // ==================== VERIFICAR DEPENDENCIAS ====================
            \Log::info('🔍 Verificando dependencias...');
            
            $dependencias = $this->checkDependenciesDetailed($table, $id);
            
            if ($dependencias['count'] > 0) {
                \Log::warning('⚠️ ELIMINACIÓN BLOQUEADA POR DEPENDENCIAS');
                \Log::warning('📊 Detalles: ', $dependencias);
                
                return redirect()->route('dashboard', ['table' => $table])
                    ->with('error', "No se puede eliminar porque tiene {$dependencias['count']} registro(s) relacionado(s) en {$dependencias['tables']}");
            }

            \Log::info('✅ Sin dependencias, procediendo a eliminar...');
            
            // ==================== ELIMINACIÓN CON TRANSACCIÓN ====================
            return DB::transaction(function() use ($table, $id, $record) {
                \Log::info('🗑️ Iniciando transacción de eliminación...');
                \Log::info('🎯 Query que se ejecutará: DELETE FROM ' . $table . ' WHERE id = ' . $id);
                
                $deleted = DB::table($table)->where('id', $id)->delete();
                
                \Log::info('📊 Resultado de eliminación:');
                \Log::info('   - Filas afectadas: ' . $deleted);
                \Log::info('   - Registro eliminado: ' . json_encode($record));
                
                if ($deleted > 0) {
                    \Log::info('🎉 ELIMINACIÓN EXITOSA');
                    
                    // Verificar que realmente se eliminó
                    $verificacion = DB::table($table)->where('id', $id)->first();
                    if ($verificacion) {
                        \Log::error('❌ ERROR: El registro AÚN EXISTE después de DELETE');
                        throw new \Exception('El registro no se eliminó correctamente');
                    } else {
                        \Log::info('✅ Verificación: Registro efectivamente eliminado');
                    }
                    
                    return redirect()->route('dashboard', ['table' => $table])
                        ->with('success', "Registro eliminado exitosamente de {$table}");
                } else {
                    \Log::error('❌ NO SE ELIMINÓ NINGUNA FILA');
                    \Log::error('🤔 Posibles causas:');
                    \Log::error('   - El ID no existe (aunque verificamos que sí)');
                    \Log::error('   - Problemas de permisos en la base de datos');
                    \Log::error('   - Restricciones de foreign key no detectadas');
                    
                    return redirect()->route('dashboard', ['table' => $table])
                        ->with('error', 'No se pudo eliminar el registro - 0 filas afectadas');
                }
            });

        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('💥 ERROR DE BASE DE DATOS:', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'errorInfo' => $e->errorInfo,
                'code' => $e->getCode()
            ]);
            
            return redirect()->route('dashboard', ['table' => $table])
                ->with('error', 'Error de base de datos: ' . $e->getMessage());
            
        } catch (\Exception $e) {
            \Log::error('💥 ERROR GENERAL:', [
                'table' => $table,
                'id' => $id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('dashboard', ['table' => $table])
                ->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }

    // ==================== MÉTODO PARA VERIFICAR DEPENDENCIAS DETALLADAMENTE ====================
    private function checkDependenciesDetailed($table, $id)
    {
        $result = [
            'count' => 0,
            'tables' => '',
            'details' => []
        ];
        
        try {
            \Log::info("🔍 Verificando dependencias para {$table} ID {$id}");
            
            $dependencies = [];
            
            switch($table) {
                case 'instituciones':
                    $count = DB::table('programa_servicio_social')->where('instituciones_id', $id)->count();
                    if ($count > 0) {
                        $dependencies[] = "programa_servicio_social ({$count})";
                        $result['details']['programa_servicio_social'] = $count;
                    }
                    break;
                    
                case 'tipos_programa':
                    $count = DB::table('programa_servicio_social')->where('tipos_programa_id', $id)->count();
                    if ($count > 0) {
                        $dependencies[] = "programa_servicio_social ({$count})";
                        $result['details']['programa_servicio_social'] = $count;
                    }
                    break;
                    
                case 'carreras':
                    $count = DB::table('escolaridad_alumno')->where('carreras_id', $id)->count();
                    if ($count > 0) {
                        $dependencies[] = "escolaridad_alumno ({$count})";
                        $result['details']['escolaridad_alumno'] = $count;
                    }
                    break;
                    
                case 'modalidad':
                    $count = DB::table('escolaridad_alumno')->where('modalidad_id', $id)->count();
                    if ($count > 0) {
                        $dependencies[] = "escolaridad_alumno ({$count})";
                        $result['details']['escolaridad_alumno'] = $count;
                    }
                    break;
                    
                // Agregar más casos según necesites...
            }
            
            $result['count'] = array_sum($result['details']);
            $result['tables'] = implode(', ', $dependencies);
            
            \Log::info("📊 Dependencias encontradas: " . json_encode($result));
            
        } catch (\Exception $e) {
            \Log::error('❌ Error verificando dependencias: ' . $e->getMessage());
        }
        
        return $result;
    }
}