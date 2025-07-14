<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Session;

class FormularioController extends Controller
{
    public function mostrarSolicitud()
    {
        try {
            return view('solicitud');
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Hubo un error al cargar la solicitud. Intente más tarde.');
        }
    }

    public function vistaDatosAlumno()
    {
        try {
            return view('datosalumno');
        } catch (\Throwable $e) {
            return redirect('/solicitud')->with('error', 'No se pudo cargar el formulario de datos del alumno. Intenta más tarde.');
        }
    }

    public function guardarDatosAlumno(Request $request)
    {
        try {
            // VALIDAR los datos del alumno
            $request->validate([
                'correo_institucional' => 'required|email|ends_with:@cbta256.edu.mx',
                'telefono' => 'required|digits:10',
                'apellido_paterno' => 'required|string|max:45',
                'apellido_materno' => 'required|string|max:45',
                'nombre' => 'required|string|max:45',
                'edad' => 'required|exists:edad,id',
                'sexo' => 'required|in:1,2',
                'localidad' => 'required|string|max:100',
                'cp' => 'required|digits:5',
                'estado' => 'required|exists:estados,id',
                'municipio' => 'required|exists:municipios,id',
            ]);

            // MAPEAR correctamente los campos
            $datosAlumno = [
                'correo_institucional' => $request->correo_institucional,
                'apellido_p' => $request->apellido_paterno, // MAPEO CORRECTO
                'apellido_m' => $request->apellido_materno,  // MAPEO CORRECTO
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'sexo_id' => $request->sexo,                 // MAPEO CORRECTO
                'edad_id' => $request->edad,                 // MAPEO CORRECTO
                'localidad' => $request->localidad,
                'cp' => $request->cp,
                'municipios_id' => $request->municipio,      // MAPEO CORRECTO
            ];

            // Guardar en sesión
            Session::put('datos_alumno', $datosAlumno);
            
            return redirect('/escolaridad');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al validar datos: ' . $e->getMessage());
        }
    }

    public function vistaEscolaridad()
    {
        try {
            return view('escolaridad');
        } catch (\Throwable $e) {
            return redirect('/datos-alumno')->with('error', 'No se pudo cargar el formulario de escolaridad.');
        }
    }

    public function guardarEscolaridad(Request $request)
    {
        try {
            // VALIDAR los datos de escolaridad
            $request->validate([
                'matricula' => 'required|digits:14',
                'meses_servicio' => 'required|integer|min:1|max:12',
                'modalidad_id' => 'required|exists:modalidad,id',
                'carreras_id' => 'required|exists:carreras,id',
                'semestres_id' => 'required|exists:semestres,id',
                'grupos_id' => 'required|exists:grupos,id',
            ]);

            // MAPEAR correctamente los campos
            $datosEscolaridad = [
                'numero_control' => $request->matricula,    // MAPEO CORRECTO
                'meses_servicio' => $request->meses_servicio,
                'modalidad_id' => $request->modalidad_id,
                'carreras_id' => $request->carreras_id,
                'semestres_id' => $request->semestres_id,
                'grupos_id' => $request->grupos_id,
            ];

            // Guardar en sesión
            Session::put('datos_escolaridad', $datosEscolaridad);
            
            return redirect('/programa');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al validar datos de escolaridad: ' . $e->getMessage());
        }
    }

    public function vistaPrograma()
    {
        try {
            return view('programa');
        } catch (\Throwable $e) {
            return redirect('/escolaridad')->with('error', 'No se pudo cargar el formulario del programa.');
        }
    }

    public function guardarTodo(Request $request)
    {
        try {
            // OBTENER datos de la sesión de los formularios anteriores
            $datosAlumno = Session::get('datos_alumno');
            $datosEscolaridad = Session::get('datos_escolaridad');
            
            // DEBUG: Ver qué hay en la sesión
            \Log::info('Datos de alumno en sesión:', $datosAlumno ?? []);
            \Log::info('Datos de escolaridad en sesión:', $datosEscolaridad ?? []);
            \Log::info('Datos de programa recibidos:', $request->all());
            
            // Validar que existan los datos previos
            if (!$datosAlumno || !$datosEscolaridad) {
                return redirect('/datos-alumno')->with('error', 'Sesión expirada. Inicia el registro nuevamente.');
            }

            // VALIDAR datos del programa
            $request->validate([
                'instituciones_id' => 'required',
                'telefono_institucion' => 'required|digits:10',
                'nombre_programa' => 'required|string|max:100',
                'tipos_programa_id' => 'required',
                'metodo_servicio_id' => 'required',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after:fecha_inicio',
                'titulos_id' => 'required',
                'encargado_nombre' => 'required|string|max:100',
                'puesto_encargado' => 'required|string|max:100',
            ]);

            $alumnoId = null;

            DB::transaction(function () use ($request, $datosAlumno, $datosEscolaridad, &$alumnoId) {
                // 1. CREAR EL ALUMNO usando datos de la sesión
                $alumnoId = DB::table('alumno')->insertGetId([
                    'correo_institucional' => $datosAlumno['correo_institucional'],
                    'apellido_p' => $datosAlumno['apellido_p'],
                    'apellido_m' => $datosAlumno['apellido_m'],
                    'nombre' => $datosAlumno['nombre'],
                    'telefono' => $datosAlumno['telefono'],
                    'fecha_registro' => Carbon::now(),
                    'sexo_id' => $datosAlumno['sexo_id'],
                    'status_id' => 1,
                    'edad_id' => $datosAlumno['edad_id'],
                    'rol_id' => 2
                ]);

                \Log::info('Alumno creado con ID: ' . $alumnoId);

                // 2. INSERTAR UBICACIÓN usando datos de la sesión
                DB::table('ubicaciones')->insert([
                    'alumno_id' => $alumnoId,
                    'localidad' => $datosAlumno['localidad'],
                    'cp' => $datosAlumno['cp'],
                    'municipios_id' => $datosAlumno['municipios_id']
                ]);

                \Log::info('Ubicación creada para alumno: ' . $alumnoId);

                // 3. INSERTAR ESCOLARIDAD usando datos de la sesión
                DB::table('escolaridad_alumno')->insert([
                    'numero_control' => $datosEscolaridad['numero_control'],
                    'meses_servicio' => $datosEscolaridad['meses_servicio'],
                    'alumno_id' => $alumnoId,
                    'modalidad_id' => $datosEscolaridad['modalidad_id'],
                    'carreras_id' => $datosEscolaridad['carreras_id'],
                    'semestres_id' => $datosEscolaridad['semestres_id'],
                    'grupos_id' => $datosEscolaridad['grupos_id']
                ]);

                \Log::info('Escolaridad creada para alumno: ' . $alumnoId);

                // 4. MANEJAR INSTITUCIÓN (del formulario actual)
                $institucionId = $request->instituciones_id;
                
                if ($request->instituciones_id === 'otra' && !empty($request->otra_institucion)) {
                    \Log::info('Creando nueva institución: ' . $request->otra_institucion);
                    
                    // Verificar si la tabla tiene timestamps
                    $hasTimestamps = DB::getSchemaBuilder()->hasColumn('instituciones', 'created_at');
                    
                    if ($hasTimestamps) {
                        $institucionId = DB::table('instituciones')->insertGetId([
                            'nombre' => $request->otra_institucion,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } else {
                        $institucionId = DB::table('instituciones')->insertGetId([
                            'nombre' => $request->otra_institucion
                        ]);
                    }
                    
                    \Log::info('Nueva institución creada con ID: ' . $institucionId);
                }

                // 5. MANEJAR TIPO DE PROGRAMA (del formulario actual)
                $tipoProgramaId = $request->tipos_programa_id;
                
                if ($request->tipos_programa_id === '0' && !empty($request->otro_programa)) {
                    \Log::info('Creando nuevo tipo de programa: ' . $request->otro_programa);
                    
                    // Verificar si la tabla tiene timestamps
                    $hasTimestamps = DB::getSchemaBuilder()->hasColumn('tipos_programa', 'created_at');
                    
                    if ($hasTimestamps) {
                        $tipoProgramaId = DB::table('tipos_programa')->insertGetId([
                            'tipo' => $request->otro_programa,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    } else {
                        $tipoProgramaId = DB::table('tipos_programa')->insertGetId([
                            'tipo' => $request->otro_programa
                        ]);
                    }
                    
                    \Log::info('Nuevo tipo de programa creado con ID: ' . $tipoProgramaId);
                }

                // 6. INSERTAR PROGRAMA DE SERVICIO SOCIAL (del formulario actual)
                $hasTimestamps = DB::getSchemaBuilder()->hasColumn('programa_servicio_social', 'created_at');
                
                $programaData = [
                    'alumno_id' => $alumnoId,
                    'instituciones_id' => $institucionId,
                    'titulos_id' => $request->titulos_id,
                    'metodo_servicio_id' => $request->metodo_servicio_id,
                    'tipos_programa_id' => $tipoProgramaId,
                    'nombre_programa' => $request->nombre_programa,
                    'encargado_nombre' => $request->encargado_nombre,
                    'puesto_encargado' => $request->puesto_encargado,
                    'telefono_institucion' => $request->telefono_institucion,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_final' => $request->fecha_final,
                    'status_id' => 1
                ];

                if ($hasTimestamps) {
                    $programaData['created_at'] = now();
                    $programaData['updated_at'] = now();
                }

                DB::table('programa_servicio_social')->insert($programaData);

                \Log::info('Programa de servicio social creado para alumno: ' . $alumnoId);
            });

            // Verificar que el alumno se creó
            if (!$alumnoId) {
                throw new \Exception('No se pudo crear el registro del alumno');
            }

            // Obtener datos para la página de éxito
            $alumno = DB::table('alumno')->where('id', $alumnoId)->first();
            $escolaridad = DB::table('escolaridad_alumno')
                ->leftJoin('carreras', 'escolaridad_alumno.carreras_id', '=', 'carreras.id')
                ->where('escolaridad_alumno.alumno_id', $alumnoId)
                ->select('escolaridad_alumno.numero_control', 'carreras.nombre as carrera_nombre')
                ->first();

            // Obtener el nombre de la institución
            $institucionNombre = '';
            if ($request->instituciones_id === 'otra') {
                $institucionNombre = $request->otra_institucion;
            } else {
                $institucion = DB::table('instituciones')->where('id', $request->instituciones_id)->first();
                $institucionNombre = $institucion ? $institucion->nombre : 'No disponible';
            }

            // Guardar información en la sesión para la página de éxito
            Session::put([
                'registro_exitoso' => true,
                'alumno_id' => $alumnoId,
                'alumno_nombre' => $alumno->nombre . ' ' . $alumno->apellido_p . ' ' . $alumno->apellido_m,
                'numero_control' => $escolaridad->numero_control ?? 'No disponible',
                'carrera_nombre' => $escolaridad->carrera_nombre ?? 'No disponible',
                'programa_nombre' => $request->nombre_programa,
                'institucion_nombre' => $institucionNombre
            ]);

            // Limpiar datos temporales de la sesión
            Session::forget(['datos_alumno', 'datos_escolaridad']);

            return redirect('/final')->with('success', 'Registro completado exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error en guardarTodo: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar la información: ' . $e->getMessage());
        }
    }

    public function downloadEditedWord($id)
    {
        // Obtener el alumno por id
        $alumno = DB::table('alumno')->where('id', $id)->first();
        if (!$alumno) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }

        // Ruta de la plantilla, puedes cambiarla o bien extraerla de la tabla "formatos"
        $templatePath = storage_path('app/templates/template.docx');

        // Crear el TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // Reemplazar los placeholders por la información del alumno
        // Asegúrate que en la plantilla de Word los marcadores estén escritos igual, por ejemplo: {{nombre}}, {{apellido_m}}, etc.
        $templateProcessor->setValue('nombre', $alumno->nombre);
        $templateProcessor->setValue('apellido_p', $alumno->apellido_p);
        $templateProcessor->setValue('apellido_m', $alumno->apellido_m);
        $templateProcessor->setValue('correo_institucional', $alumno->correo_institucional);
        // Agrega más reemplazos según la información que necesites

        // Guardar el documento generado en un archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'edited_') . '.docx';
        $templateProcessor->saveAs($tempFile);

        // Enviar el archivo para descargar
        return response()->download($tempFile, 'archivo_' . $alumno->id . '.docx')->deleteFileAfterSend(true);
    }
}
