<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;  
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
            // Verificar si estamos en modo edición
            $editandoId = Session::get('editando_alumno_id');
            
            if ($editandoId) {
                Log::info('Formulario en modo edición para alumno ID: ' . $editandoId);
            }
            
            // Recuperar datos guardados en sesión (o los datos del alumno si estamos editando)
            $datosGuardados = Session::get('datos_alumno', []);
            
            // Obtener datos para los selects
            $estados = DB::table('estados')->select('id','nombre')->get();
            $municipios = DB::table('municipios')->select('id','nombre','estado_id')->get();
            $edades = DB::table('edad')->select('id','edades')->get();
            
            return view('datosalumno', compact('datosGuardados', 'estados', 'municipios', 'edades', 'editandoId'));
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
                'apellido_p' => 'required|string|max:45',  // ✅ CORREGIDO
                'apellido_m' => 'required|string|max:45',  // ✅ CORREGIDO
                'nombre' => 'required|string|max:45',
                'edad' => 'required|exists:edad,id',
                'sexo' => 'required|in:1,2',
                'localidad' => 'required|string|max:100',
                'cp' => 'required|digits:5',
                'estado' => 'required|exists:estados,id',
                'municipio' => 'required|exists:municipios,id',
            ]);

            // Guardar TODOS los datos en sesión
          $datosAlumno = $request->only([
    'correo_institucional', 'telefono', 'apellido_p', 'apellido_m', 
    'nombre', 'edad', 'sexo', 'localidad', 'cp', 'estado', 'municipio'
]);

            Session::put('datos_alumno', $datosAlumno);
            
            return redirect('/escolaridad')->with('success', 'Datos guardados correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al validar datos: ' . $e->getMessage());
        }
    }

    public function vistaEscolaridad()
    {
        try {
            // Recuperar datos guardados en sesión si existen
            $datosEscolaridadGuardados = Session::get('datos_escolaridad', []);
            
            // Obtener datos para los selects
            $modalidades = DB::table('modalidad')->select('id','nombre')->get();
            $carreras = DB::table('carreras')->select('id','nombre')->get();
            $semestres = DB::table('semestres')->select('id','nombre')->get();
            $grupos = DB::table('grupos')->select('id','letra')->get();
            
            return view('escolaridad', compact('datosEscolaridadGuardados', 'modalidades', 'carreras', 'semestres', 'grupos'));
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
            
            return redirect('/programa')->with('success', 'Datos de escolaridad guardados correctamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al validar datos de escolaridad: ' . $e->getMessage());
        }
    }

    public function vistaPrograma()
    {
        try {
            // Recuperar datos guardados en sesión si existen
            $datosProgramaGuardados = Session::get('datos_programa', []);
            
            // Obtener datos para los selects
            $instituciones = DB::table('instituciones')->select('id','nombre')->get();
            $titulos = DB::table('titulos')->select('id','titulo')->get();
            $metodos = DB::table('metodo_servicio')->select('id','metodo')->get();
            $tipos = DB::table('tipos_programa')->select('id','tipo')->get();
            $status = DB::table('status')->select('id','tipo')->get();
            
            return view('programa', compact('datosProgramaGuardados', 'instituciones', 'titulos', 'metodos', 'tipos', 'status'));
        } catch (\Throwable $e) {
            return redirect('/escolaridad')->with('error', 'No se pudo cargar el formulario del programa.');
        }
    }

    public function guardarTodo(Request $request)
    {
        \Log::info('MÉTODO GUARDARTODO INICIADO');
        \Log::info('Datos recibidos: ', $request->all());
        
        try {
            // OBTENER datos de la sesión de los formularios anteriores
            $datosAlumno = Session::get('datos_alumno');
            $datosEscolaridad = Session::get('datos_escolaridad');
            
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

            // AGREGAR AQUÍ - ANTES del DB::transaction:
            // Guardar datos de programa en sesión también
            $datosPrograma = $request->only([
                'instituciones_id', 'telefono_institucion', 'nombre_programa',
                'tipos_programa_id', 'metodo_servicio_id', 'fecha_inicio',
                'fecha_final', 'titulos_id', 'encargado_nombre', 'puesto_encargado',
                'otra_institucion', 'otro_programa'
            ]);
            Session::put('datos_programa', $datosPrograma);

            $alumnoId = null;

            // CAMBIAR ESTA LÍNEA - QUITAR EL RETURN:
            DB::transaction(function () use ($request, $datosAlumno, $datosEscolaridad, &$alumnoId) {
                // 1. CREAR EL ALUMNO usando datos de la sesión
                $alumnoId = DB::table('alumno')->insertGetId([
                    'correo_institucional' => $datosAlumno['correo_institucional'],
                    'apellido_p' => $datosAlumno['apellido_p'],
                    'apellido_m' => $datosAlumno['apellido_m'],
                    'nombre' => $datosAlumno['nombre'],
                    'telefono' => $datosAlumno['telefono'],
                    'fecha_registro' => now()->setTimezone('America/Mexico_City'), // ✅ CAMBIAR: Carbon::now() por now()
                    'sexo_id' => $datosAlumno['sexo'],
                    'status_id' => 1,
                    'edad_id' => $datosAlumno['edad'],
                    'rol_id' => 1
                ]);

                \Log::info('Alumno creado con ID: ' . $alumnoId);

                // 2. INSERTAR UBICACIÓN usando datos de la sesión
                DB::table('ubicaciones')->insert([
                    'alumno_id' => $alumnoId,
                    'localidad' => $datosAlumno['localidad'],
                    'cp' => $datosAlumno['cp'],
                    'municipios_id' => $datosAlumno['municipio']  // ✅ CAMBIAR: era 'municipios_id'
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

                // 4. MANEJAR INSTITUCIÓN (del formulario actual) - CORREGIDO
                $institucionId = $request->instituciones_id;

                // Si seleccionó "otra", usar NULL como ID y guardar el nombre en otra_institucion
                if ($request->instituciones_id === 'otra') {
                    if (empty($request->otra_institucion)) {
                        throw new \Exception('Debe especificar el nombre de la institución');
                    }
                    $institucionId = null; // No crear nueva institución
                    $otraInstitucion = trim($request->otra_institucion);
                    \Log::info('Usando institución personalizada: ' . $otraInstitucion);
                } else {
                    $otraInstitucion = null;
                    \Log::info('Usando institución existente ID: ' . $institucionId);
                }

                // 5. MANEJAR TIPO DE PROGRAMA (del formulario actual) - CORREGIDO
                $tipoProgramaId = $request->tipos_programa_id;

                // Si seleccionó "otro", usar NULL como ID y guardar el tipo en otro_programa
                if ($request->tipos_programa_id === '0') {
                    if (empty($request->otro_programa)) {
                        throw new \Exception('Debe especificar el tipo de programa');
                    }
                    $tipoProgramaId = null; // No crear nuevo tipo
                    $otroPrograma = trim($request->otro_programa);
                    \Log::info('Usando tipo de programa personalizado: ' . $otroPrograma);
                } else {
                    $otroPrograma = null;
                    \Log::info('Usando tipo de programa existente ID: ' . $tipoProgramaId);
                }

                // 6. INSERTAR PROGRAMA DE SERVICIO SOCIAL (del formulario actual) - CORREGIDO
                $programaData = [
                    'alumno_id' => $alumnoId,
                    'instituciones_id' => $institucionId ?: 1, // Usar ID 1 por defecto para "otras"
                    'otra_institucion' => $otraInstitucion,
                    'titulos_id' => $request->titulos_id,
                    'metodo_servicio_id' => $request->metodo_servicio_id,
                    'tipos_programa_id' => $tipoProgramaId ?: 1, // Usar ID 1 por defecto para "otros"
                    'otro_programa' => $otroPrograma,
                    'nombre_programa' => $request->nombre_programa,
                    'encargado_nombre' => $request->encargado_nombre,
                    'puesto_encargado' => $request->puesto_encargado,
                    'telefono_institucion' => $request->telefono_institucion,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_final' => $request->fecha_final,
                    'status_id' => 1
                ];

                \Log::info('Datos del programa a insertar:', $programaData);

                DB::table('programa_servicio_social')->insert($programaData);
                \Log::info('Programa de servicio social creado para alumno: ' . $alumnoId);
            }); // Fin del DB::transaction

            // AGREGAR LOGS AQUÍ DESPUÉS DE LA TRANSACCIÓN:
            \Log::info('TRANSACCIÓN COMPLETADA, alumnoId: ' . $alumnoId);

            // Verificar que el alumno se creó
            if (!$alumnoId) {
                \Log::error('ALUMNO ID ES NULL O VACÍO');
                throw new \Exception('No se pudo crear el registro del alumno');
            }

            \Log::info('PREPARANDO DATOS PARA LA VISTA');

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
                'alumno_nombre' => $datosAlumno['nombre'] . ' ' . $datosAlumno['apellido_p'] . ' ' . $datosAlumno['apellido_m'],
                'numero_control' => $datosEscolaridad['numero_control'] ?? 'No disponible',
                'programa_nombre' => $request->nombre_programa
            ]);

            // Limpiar datos temporales de la sesión
            Session::forget(['datos_alumno', 'datos_escolaridad', 'datos_programa']);

            // PREPARAR datos para la vista final
            $alumnoNombre = $datosAlumno['nombre'] . ' ' . $datosAlumno['apellido_p'] . ' ' . $datosAlumno['apellido_m'];

            \Log::info('RETORNANDO VISTA registro-exitoso con alumnoId: ' . $alumnoId);
            
            return view('registro-exitoso', [
                'alumnoId' => $alumnoId,
                'alumnoNombre' => $alumnoNombre,
                'numeroControl' => $datosEscolaridad['numero_control'],
                'programaNombre' => $request->nombre_programa
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en guardarTodo: ' . $e->getMessage());
            return view('registro-exitoso', [
                'alumnoId' => 'ERROR',
                'alumnoNombre' => 'Error en el registro',
                'numeroControl' => 'N/A',
                'programaNombre' => 'Error: ' . $e->getMessage()
            ]);
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

    public function guardarPrograma(Request $request)
    {
        // Guardar datos de programa en sesión cuando va hacia atrás
        $datosPrograma = $request->only([
            'instituciones_id', 'telefono_institucion', 'nombre_programa',
            'tipos_programa_id', 'metodo_servicio_id', 'fecha_inicio',
            'fecha_final', 'titulos_id', 'encargado_nombre', 'puesto_encargado',
            'otra_institucion', 'otro_programa'
        ]);
        
        Session::put('datos_programa', $datosPrograma);
        
        return redirect('/escolaridad')->with('success', 'Datos guardados temporalmente');
    }

    public function exportSolicitud($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId);
    }

    public function exportEscolaridad($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId);
    }

    public function exportPrograma($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId);
    }

    public function exportReporteFinal($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId);
    }

    public function buscarRegistro(Request $request)
    {
        try {
            // Validar que al menos un campo esté lleno
            $request->validate([
                'folio' => 'nullable|numeric',
                'numero_control' => 'nullable|string|max:14',
                'correo' => 'nullable|email'
            ]);

            $folio = $request->folio;
            $numeroControl = $request->numero_control;
            $correo = $request->correo;

            // Verificar que el folio esté lleno Y al menos uno de los otros dos
            if (empty($folio) || (empty($numeroControl) && empty($correo))) {
                return redirect('/solicitud')->with('error', 'Debe proporcionar el FOLIO + (Matrícula O Correo institucional) para buscar el registro.');
            }

            $alumno = null;

            // Buscar por folio + número de control
            if (!empty($folio) && !empty($numeroControl)) {
                $alumno = DB::table('alumno')
                    ->leftJoin('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id') // ✅ CORREGIR TABLA
                    ->where('alumno.id', $folio)
                    ->where('escolaridad_alumno.numero_control', $numeroControl)
                    ->select('alumno.*', 'escolaridad_alumno.numero_control')
                    ->first();
            }
            
            // Si no se encontró por folio + número de control, buscar por folio + correo
            if (!$alumno && !empty($folio) && !empty($correo)) {
                $alumno = DB::table('alumno')
                    ->leftJoin('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id') // ✅ CORREGIR TABLA
                    ->where('alumno.id', $folio)
                    ->where('alumno.correo_institucional', $correo)
                    ->select('alumno.*', 'escolaridad_alumno.numero_control')
                    ->first();
            }

            if (!$alumno) {
                return redirect('/solicitud')->with('error', 'No se encontró ningún registro con los datos proporcionados. Verifique que el FOLIO + (Matrícula O Correo) sean correctos.');
            }

            // Redirigir al formulario de edición con el ID del alumno encontrado
            return redirect()->route('alumno.edit', $alumno->id);

    } catch (\Exception $e) {
        \Log::error('Error en buscarRegistro: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect('/solicitud')->with('error', 'Ocurrió un error al buscar el registro. Intente nuevamente.');
    }
}

    private function obtenerDatosCompletos($alumnoId)
    {
        try {
            // Obtener datos del alumno
            $alumno = DB::table('alumno')
                ->leftJoin('ubicaciones', 'alumno.id', '=', 'ubicaciones.alumno_id')
                ->where('alumno.id', $alumnoId)
                ->select('alumno.*', 'ubicaciones.localidad', 'ubicaciones.cp', 'ubicaciones.municipios_id')
                ->first();

            // Obtener datos de escolaridad - ✅ CORREGIR TABLA
            $escolaridad = DB::table('escolaridad_alumno')
                ->where('alumno_id', $alumnoId)
                ->first();

            // Obtener datos del programa
            $programa = DB::table('programa_servicio_social')
                ->where('alumno_id', $alumnoId)
                ->first();

            return [
                'alumno' => [
                    'nombre' => $alumno->nombre ?? '',
                    'apellido_p' => $alumno->apellido_p ?? '',
                    'apellido_m' => $alumno->apellido_m ?? '',
                    'correo_institucional' => $alumno->correo_institucional ?? '',
                    'telefono' => $alumno->telefono ?? '',
                    'sexo' => $alumno->sexo_id ?? '',
                    'edad' => $alumno->edad_id ?? '',
                    'localidad' => $alumno->localidad ?? '',
                    'cp' => $alumno->cp ?? '',
                    'municipio' => $alumno->municipios_id ?? ''
                ],
                'escolaridad' => [
                    'numero_control' => $escolaridad->numero_control ?? '',
                    'meses_servicio' => $escolaridad->meses_servicio ?? '',
                    'modalidad_id' => $escolaridad->modalidad_id ?? '',
                    'carreras_id' => $escolaridad->carreras_id ?? '',
                    'semestres_id' => $escolaridad->semestres_id ?? '',
                    'grupos_id' => $escolaridad->grupos_id ?? ''
                ],
                'programa' => [
                    'instituciones_id' => $programa->instituciones_id ?? '',
                    'telefono_institucion' => $programa->telefono_institucion ?? '',
                    'nombre_programa' => $programa->nombre_programa ?? '',
                    'tipos_programa_id' => $programa->tipos_programa_id ?? '',
                    'metodo_servicio_id' => $programa->metodo_servicio_id ?? '',
                    'fecha_inicio' => $programa->fecha_inicio ?? '',
                    'fecha_final' => $programa->fecha_final ?? '',
                    'titulos_id' => $programa->titulos_id ?? '',
                    'encargado_nombre' => $programa->encargado_nombre ?? '',
                    'puesto_encargado' => $programa->puesto_encargado ?? ''
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Error en obtenerDatosCompletos: ' . $e->getMessage());
            return [
                'alumno' => [],
                'escolaridad' => [],
                'programa' => []
            ];
        }
    }

    public function editarAlumnoPublico($id)
    {
        try {
            // Buscar el alumno
            $alumno = DB::table('alumno')->where('id', $id)->first();
            if (!$alumno) {
                return redirect('/solicitud')->with('error', 'Alumno no encontrado');
            }

            // Obtener datos relacionados CON JOINS para obtener nombres
            $ubicacion = DB::table('ubicaciones')
                ->leftJoin('municipios', 'ubicaciones.municipios_id', '=', 'municipios.id')
                ->leftJoin('estados', 'municipios.estado_id', '=', 'estados.id')
                ->where('ubicaciones.alumno_id', $id)
                ->select(
                    'ubicaciones.*',
                    'municipios.nombre as municipio_nombre',
                    'municipios.estado_id as estado_id',
                    'estados.nombre as estado_nombre'
                )
                ->first();

            \Log::info('Datos de ubicación encontrados:', (array)$ubicacion);

            $escolaridad = DB::table('escolaridad_alumno')->where('alumno_id', $id)->first();
            $programa = DB::table('programa_servicio_social')->where('alumno_id', $id)->first();

            // Obtener opciones para los selects
            $foreignOptions = [
                'edad_id' => DB::table('edad')->orderBy('edades')->get(),
                'sexo_id' => DB::table('sexo')->orderBy('tipo')->get(),
                'modalidad_id' => DB::table('modalidad')->orderBy('nombre')->get(),
                'carreras_id' => DB::table('carreras')->orderBy('nombre')->get(),
                'semestres_id' => DB::table('semestres')->orderBy('nombre')->get(),
                'grupos_id' => DB::table('grupos')->orderBy('letra')->get(),
                'instituciones_id' => DB::table('instituciones')->orderBy('nombre')->get(),
                'titulos_id' => DB::table('titulos')->orderBy('titulo')->get(),
                'metodo_servicio_id' => DB::table('metodo_servicio')->orderBy('metodo')->get(),
                'tipos_programa_id' => DB::table('tipos_programa')->orderBy('tipo')->get(),
                'estados_id' => DB::table('estados')->orderBy('nombre')->get(),
                'municipios_id' => DB::table('municipios')->orderBy('nombre')->get(),
            ];

            // Si hay ubicación, obtener municipios del estado actual
            if ($ubicacion && $ubicacion->estado_id) {
                $foreignOptions['municipios_del_estado'] = DB::table('municipios')
                    ->where('estado_id', $ubicacion->estado_id)
                    ->orderBy('nombre')
                    ->get();
            } else {
                $foreignOptions['municipios_del_estado'] = collect();
            }

            \Log::info('Enviando datos a la vista:', [
                'alumno_id' => $alumno->id,
                'estado_id' => $ubicacion->estado_id ?? 'null',
                'municipio_id' => $ubicacion->municipios_id ?? 'null',
                'municipios_disponibles' => $foreignOptions['municipios_del_estado']->count()
            ]);

            return view('alumno-edit-publico', compact(
                'alumno', 
                'ubicacion', 
                'escolaridad', 
                'programa', 
                'foreignOptions'
            ));

        } catch (\Exception $e) {
            \Log::error('Error al cargar formulario de edición pública:', [
                'alumno_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/solicitud')->with('error', 'Error al cargar el formulario');
        }
    }
    public function actualizarAlumnoPublico(Request $request, $id)
    {
        // Debug inicial
        \Log::info('=== INICIO ACTUALIZACIÓN PÚBLICA ===');
        \Log::info('ID del alumno: ' . $id);
        \Log::info('Método HTTP: ' . $request->method());
        \Log::info('URL completa: ' . $request->fullUrl());
        \Log::info('Datos recibidos:', $request->all());
        
        try {
            // Verificar que el alumno existe ANTES de la validación
            $alumno = DB::table('alumno')->where('id', $id)->first();
            if (!$alumno) {
                \Log::error('Alumno no encontrado con ID: ' . $id);
                return redirect('/solicitud')
                           ->with('error', 'Alumno no encontrado');
            }
            
            \Log::info('Alumno encontrado:', (array)$alumno);

            // Validación completa paso a paso
            $rules = [
                // Datos personales
                'nombre' => 'required|string|max:45|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
                'apellido_p' => 'required|string|max:45|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
                'apellido_m' => 'required|string|max:45|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
                'correo_institucional' => 'required|email|max:255|ends_with:@cbta256.edu.mx',
                'telefono' => 'required|digits:10',
                'edad_id' => 'required|integer|exists:edad,id',
                'sexo_id' => 'required|integer|exists:sexo,id',
                
                // Ubicación
                'ubicacion_estados_id' => 'required|integer|exists:estados,id',
                'ubicacion_localidad' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-\,0-9]+$/',
                'ubicacion_cp' => 'required|digits:5',
                'ubicacion_municipios_id' => 'required|integer|exists:municipios,id',
                
                // Escolaridad
                'escolaridad_numero_control' => 'required|string|max:14|regex:/^[0-9]{8,14}$/',
                'escolaridad_meses_servicio' => 'required|integer|min:1|max:12',
                'escolaridad_modalidad_id' => 'required|integer|exists:modalidad,id',
                'escolaridad_carreras_id' => 'required|integer|exists:carreras,id',
                'escolaridad_semestres_id' => 'required|integer|exists:semestres,id',
                'escolaridad_grupos_id' => 'required|integer|exists:grupos,id',
                
                // Programa
                'programa_instituciones_id' => 'required',
                'programa_nombre_programa' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.\-\,]+$/',
                'programa_encargado_nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/',
                'programa_titulos_id' => 'required|integer|exists:titulos,id',
                'programa_puesto_encargado' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.\-]+$/',
                'programa_telefono_institucion' => 'required|digits:10',
                'programa_metodo_servicio_id' => 'required|integer|exists:metodo_servicio,id',
                'programa_fecha_inicio' => 'required|date|after_or_equal:2020-01-01|before_or_equal:2030-12-31',
                'programa_fecha_final' => 'required|date|after:programa_fecha_inicio|before_or_equal:2030-12-31',
                'programa_tipos_programa_id' => 'required', // Quitar |exists:tipos_programa,id
                
                // Campos opcionales
                'programa_otra_institucion' => 'nullable|string|max:255',
                'programa_otro_programa' => 'nullable|string|max:255',

                // Ubicación (actualizar estos mensajes)
'ubicacion_estados_id.required' => 'Seleccione un estado',
'ubicacion_estados_id.exists' => 'El estado seleccionado no es válido',
'ubicacion_localidad.required' => 'La localidad es obligatoria',
'ubicacion_localidad.regex' => 'La localidad contiene caracteres no válidos',
'ubicacion_cp.required' => 'El código postal es obligatorio',
'ubicacion_cp.digits' => 'El código postal debe tener 5 dígitos',
'ubicacion_municipios_id.required' => 'Seleccione un municipio',
'ubicacion_municipios_id.exists' => 'El municipio seleccionado no es válido',
            ];

            $messages = [
                // Datos personales
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.regex' => 'El nombre solo puede contener letras y espacios',
                'apellido_p.required' => 'El apellido paterno es obligatorio',
                'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios',
                'apellido_m.required' => 'El apellido materno es obligatorio',
                'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios',
                'correo_institucional.required' => 'El correo institucional es obligatorio',
                'correo_institucional.email' => 'Ingrese un correo electrónico válido',
                'correo_institucional.ends_with' => 'El correo debe terminar en @cbta256.edu.mx',
                'telefono.required' => 'El teléfono es obligatorio',
                'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos',
                'edad_id.required' => 'Seleccione una edad',
                'edad_id.exists' => 'La edad seleccionada no es válida',
                'sexo_id.required' => 'Seleccione el sexo',
                'sexo_id.exists' => 'El sexo seleccionado no es válido',
                
                // Ubicación
                'ubicacion_estados_id.required' => 'Seleccione un estado',
                'ubicacion_estados_id.exists' => 'El estado seleccionado no es válido',
                'ubicacion_localidad.required' => 'La localidad es obligatoria',
                'ubicacion_localidad.regex' => 'La localidad contiene caracteres no válidos',
                'ubicacion_cp.required' => 'El código postal es obligatorio',
                'ubicacion_cp.digits' => 'El código postal debe tener 5 dígitos',
                'ubicacion_municipios_id.required' => 'Seleccione un municipio',
                'ubicacion_municipios_id.exists' => 'El municipio seleccionado no es válido',
                
                // Escolaridad
                'escolaridad_numero_control.required' => 'El número de control es obligatorio',
                'escolaridad_numero_control.regex' => 'El número de control debe tener entre 8 y 14 dígitos',
                'escolaridad_meses_servicio.required' => 'Los meses de servicio son obligatorios',
                'escolaridad_meses_servicio.min' => 'Mínimo 1 mes de servicio',
                'escolaridad_meses_servicio.max' => 'Máximo 12 meses de servicio',
                'escolaridad_modalidad_id.required' => 'Seleccione una modalidad',
                'escolaridad_modalidad_id.exists' => 'La modalidad seleccionada no es válida',
                'escolaridad_carreras_id.required' => 'Seleccione una carrera',
                'escolaridad_carreras_id.exists' => 'La carrera seleccionada no es válida',
                'escolaridad_semestres_id.required' => 'Seleccione un semestre',
                'escolaridad_semestres_id.exists' => 'El semestre seleccionado no es válido',
                'escolaridad_grupos_id.required' => 'Seleccione un grupo',
                'escolaridad_grupos_id.exists' => 'El grupo seleccionado no es válido',
                
                // Programa
                'programa_instituciones_id.required' => 'Seleccione una institución',
                'programa_instituciones_id.exists' => 'La institución seleccionada no es válida',
                'programa_nombre_programa.required' => 'El nombre del programa es obligatorio',
                'programa_nombre_programa.regex' => 'El nombre del programa contiene caracteres no válidos',
                'programa_encargado_nombre.required' => 'El nombre del encargado es obligatorio',
                'programa_encargado_nombre.regex' => 'El nombre del encargado solo puede contener letras y espacios',
                'programa_titulos_id.required' => 'Seleccione un título',
                'programa_titulos_id.exists' => 'El título seleccionado no es válido',
                'programa_puesto_encargado.required' => 'El puesto del encargado es obligatorio',
                'programa_puesto_encargado.regex' => 'El puesto del encargado contiene caracteres no válidos',
                'programa_telefono_institucion.required' => 'El teléfono de la institución es obligatorio',
                'programa_telefono_institucion.digits' => 'El teléfono debe tener exactamente 10 dígitos',
                'programa_metodo_servicio_id.required' => 'Seleccione un método de servicio',
                'programa_metodo_servicio_id.exists' => 'El método de servicio seleccionado no es válido',
                'programa_fecha_inicio.required' => 'La fecha de inicio es obligatoria',
                'programa_fecha_inicio.date' => 'Ingrese una fecha válida',
                'programa_fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a 2020',
                'programa_fecha_inicio.before_or_equal' => 'La fecha de inicio no puede ser posterior a 2030',
                'programa_fecha_final.required' => 'La fecha final es obligatoria',
                'programa_fecha_final.date' => 'Ingrese una fecha válida',
                'programa_fecha_final.after' => 'La fecha final debe ser posterior a la fecha de inicio',
                'programa_fecha_final.before_or_equal' => 'La fecha final no puede ser posterior a 2030',
                'programa_tipos_programa_id.required' => 'Seleccione un tipo de programa',
                'programa_tipos_programa_id.exists' => 'El tipo de programa seleccionado no es válido',
            ];

            // Agregar después de la validación básica:

            // Validaciones condicionales personalizadas
            if ($request->programa_instituciones_id === 'otra' && empty(trim($request->programa_otra_institucion))) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['programa_otra_institucion' => ['Debe especificar el nombre de la institución']]
                );
            }

            if ($request->programa_tipos_programa_id === '0' && empty(trim($request->programa_otro_programa))) {
                throw new \Illuminate\Validation\ValidationException(
                    validator([], []),
                    ['programa_otro_programa' => ['Debe especificar el tipo de programa']]
                );
            }

            // Validar que instituciones_id existe si no es "otra"
            if ($request->programa_instituciones_id !== 'otra') {
                $institucionExiste = DB::table('instituciones')->where('id', $request->programa_instituciones_id)->exists();
                if (!$institucionExiste) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['programa_instituciones_id' => ['La institución seleccionada no es válida']]
                    );
                }
            }

            // Validar que tipos_programa_id existe si no es "0"
            if ($request->programa_tipos_programa_id !== '0') {
                $tipoExiste = DB::table('tipos_programa')->where('id', $request->programa_tipos_programa_id)->exists();
                if (!$tipoExiste) {
                    throw new \Illuminate\Validation\ValidationException(
                        validator([], []),
                        ['programa_tipos_programa_id' => ['El tipo de programa seleccionado no es válido']]
                    );
                }
            }

            \Log::info('Iniciando validación...');
            $validatedData = $request->validate($rules, $messages);
            \Log::info('Validación exitosa');

            // Ejecutar transacción
            DB::transaction(function() use ($request, $id) {
                
                // 1. Actualizar datos del alumno
                $alumnoData = [
                    'correo_institucional' => trim($request->correo_institucional),
                    'apellido_p' => trim($request->apellido_p),
                    'apellido_m' => trim($request->apellido_m),
                    'nombre' => trim($request->nombre),
                    'telefono' => $request->telefono,
                    'fecha_registro' => now()->setTimezone('America/Mexico_City'),
                    'sexo_id' => $request->sexo_id,
                    'status_id' => 1,
                    'edad_id' => $request->edad_id,
                    'rol_id' => 1,
                ];

                \Log::info('Actualizando alumno con datos:', $alumnoData);
                $result1 = DB::table('alumno')->where('id', $id)->update($alumnoData);
                \Log::info('Filas actualizadas en alumno: ' . $result1);

                // 2. Actualizar/Insertar ubicación
                $ubicacionData = [
                   'alumno_id' => $id,
    'localidad' => trim($request->ubicacion_localidad),
    'cp' => $request->ubicacion_cp,
    'municipios_id' => $request->ubicacion_municipios_id,
                ];

                // Si existe el campo estado_id en la tabla ubicaciones, agregarlo
                if (Schema::hasColumn('ubicaciones', 'estado_id')) {
                    $ubicacionData['estado_id'] = $request->ubicacion_estados_id;
                }

                \Log::info('Datos de ubicación a actualizar:', $ubicacionData);

                $ubicacionExistente = DB::table('ubicaciones')->where('alumno_id', $id)->first();
                if ($ubicacionExistente) {
                    $result2 = DB::table('ubicaciones')->where('alumno_id', $id)->update($ubicacionData);
                    \Log::info('Ubicación actualizada: ' . $result2);
                } else {
                    DB::table('ubicaciones')->insert($ubicacionData);
                    \Log::info('Ubicación creada');
                }

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

                $escolaridadExistente = DB::table('escolaridad_alumno')->where('alumno_id', $id)->first();
                if ($escolaridadExistente) {
                    $result3 = DB::table('escolaridad_alumno')->where('alumno_id', $id)->update($escolaridadData);
                    \Log::info('Escolaridad actualizada: ' . $result3);
                } else {
                    DB::table('escolaridad_alumno')->insert($escolaridadData);
                    \Log::info('Escolaridad creada');
                }

                // 4. Actualizar/Insertar programa - CORREGIDO
                $institucionId = $request->programa_instituciones_id;
$otraInstitucion = null;

// Manejar institución personalizada
if ($request->programa_instituciones_id === 'otra') {
    if (empty($request->programa_otra_institucion)) {
        throw new \Exception('Debe especificar el nombre de la institución');
    }
    $institucionId = 12; // ID por defecto para "otras"
    $otraInstitucion = trim($request->programa_otra_institucion);
} else {
    $otraInstitucion = null;
}

$tipoProgramaId = $request->programa_tipos_programa_id;
$otroPrograma = null;

// Manejar tipo de programa personalizado
if ($request->programa_tipos_programa_id === '0') {
    if (empty($request->programa_otro_programa)) {
        throw new \Exception('Debe especificar el tipo de programa');
    }
    $tipoProgramaId = 1; // ID por defecto para "otros"
    $otroPrograma = trim($request->programa_otro_programa);
} else {
    $otroPrograma = null;
}

$programaData = [
    'alumno_id' => $id,
    'instituciones_id' => $institucionId, // Ahora será 12 si es "otra"
    'otra_institucion' => $otraInstitucion, // El nombre de la institución se guarda aquí
    'nombre_programa' => trim($request->programa_nombre_programa),
    'encargado_nombre' => trim($request->programa_encargado_nombre),
    'titulos_id' => $request->programa_titulos_id,
    'puesto_encargado' => trim($request->programa_puesto_encargado),
    'telefono_institucion' => $request->programa_telefono_institucion,
    'metodo_servicio_id' => $request->programa_metodo_servicio_id,
    'fecha_inicio' => $request->programa_fecha_inicio,
    'fecha_final' => $request->programa_fecha_final,
    'tipos_programa_id' => $tipoProgramaId,
    'otro_programa' => $otroPrograma,
    'status_id' => 1,
];

\Log::info('Datos del programa a actualizar:', $programaData);

$programaExistente = DB::table('programa_servicio_social')->where('alumno_id', $id)->first();
if ($programaExistente) {
    $result4 = DB::table('programa_servicio_social')->where('alumno_id', $id)->update($programaData);
    \Log::info('Programa actualizado: ' . $result4);
} else {
    DB::table('programa_servicio_social')->insert($programaData);
    \Log::info('Programa creado');
}

                \Log::info('Transacción completada exitosamente');
            });

            \Log::info('Redirigiendo a página de éxito...');
            return redirect('/actualizacion-exitosa')
                   ->with('success', 'Tu información ha sido actualizada exitosamente')
                   ->with('alumno_nombre', $request->nombre . ' ' . $request->apellido_p)
                   ->with('alumno_id', $id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación:', $e->errors());
            return redirect()->back()
                   ->withErrors($e->errors())
                   ->withInput()
                   ->with('error', 'Por favor corrige los errores del formulario');
        
        } catch (\Exception $e) {
            \Log::error('Error general:', [
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
}



//ERROOOOOOOOOOOR EN LAS INSTITUCIONES Y TIPOS DE PROGRAMAS CUANDO ELIMINAS COSAS