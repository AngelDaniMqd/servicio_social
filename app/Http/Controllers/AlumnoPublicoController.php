<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlumnoPublicoController extends Controller
{
    /**
     * Mostrar formulario de edición para el alumno público
     */
    public function editarAlumno($id)
    {
        try {
            // 1. Obtener datos del alumno
            $alumno = DB::table('alumno')->where('id', $id)->first();
            
            if (!$alumno) {
                return redirect('/solicitud')
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
                'status_id' => DB::table('status')->orderBy('tipo')->get(),
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

            // Usar la misma vista pero con contexto público
            return view('alumno-edit-publico', compact(
                'alumno',
                'ubicacion', 
                'escolaridad', 
                'programa', 
                'foreignOptions'
            ));

        } catch (\Exception $e) {
            return redirect('/solicitud')
                           ->with('error', 'Error al cargar el formulario de edición: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar información del alumno desde el lado público
     */
    public function actualizarAlumno(Request $request, $id)
    {
        try {
            // Debug: Ver qué datos llegan
            \Log::info('Datos recibidos para actualización pública:', $request->all());

            // Validación
            $request->validate([
                // Datos personales
                'nombre' => 'required|string|max:45',
                'apellido_p' => 'required|string|max:45',
                'apellido_m' => 'required|string|max:45',
                'correo_institucional' => 'required|email|max:255|ends_with:@cbta256.edu.mx',
                'telefono' => 'required|digits:10',
                'edad_id' => 'required|exists:edad,id',
                'sexo_id' => 'required|exists:sexo,id',
                
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
                    // Mantener rol_id existente o usar valor por defecto
                    'rol_id' => $request->rol_id ?? 1,
                ];

                \Log::info('Actualizando alumno público con datos:', $alumnoData);
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

                \Log::info('Transacción de actualización pública completada exitosamente');
            });

            // Redirigir a página de éxito
            return redirect('/actualizacion-exitosa')
                       ->with('success', 'Tu información ha sido actualizada exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación en actualización pública:', $e->errors());
            return redirect()->back()
                       ->withErrors($e->errors())
                       ->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al actualizar alumno público:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()
                       ->with('error', 'Error al actualizar: ' . $e->getMessage())
                       ->withInput();
        }
    }

    /**
     * Página de confirmación de actualización exitosa
     */
    public function actualizacionExitosa()
    {
        return view('actualizacion-exitosa');
    }
}