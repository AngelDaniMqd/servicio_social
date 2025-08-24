<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpWord\TemplateProcessor;

class FormatoController extends Controller
{
    public function mostrarAlumnosDescargar()
    {
        $alumnos = DB::table('alumno')
            ->orderBy('fecha_registro', 'desc')
            ->get();

        return view('alumnos_descargar', ['alumnos' => $alumnos]);
    }

    public function downloadEditedWord($id, $tipo = 'word')
    {
        try {
            // Obtener datos completos del alumno con la nueva estructura de relaciones
            $alumno = $this->obtenerDatosCompletos($id);
            
            if (!$alumno) {
                return redirect()->back()->with('error', 'Alumno no encontrado.');
            }

            // Procesar según el tipo de descarga
            switch ($tipo) {
                case 'word':
                    return $this->generarFormatoWord($alumno);
                case 'reporte':
                    return $this->generarReporte($alumno);
                case 'reporte_final':
                    return $this->generarReporteFinal($alumno);
                default:
                    return redirect()->back()->with('error', 'Tipo de formato no válido.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el documento: ' . $e->getMessage());
        }
    }

    private function obtenerDatosCompletos($id)
    {
        try {
            return DB::table('alumno')
                ->leftJoin('sexo', 'alumno.sexo_id', '=', 'sexo.id')
                ->leftJoin('edad', 'alumno.edad_id', '=', 'edad.id')
                ->leftJoin('ubicaciones', 'alumno.id', '=', 'ubicaciones.alumno_id')
                ->leftJoin('municipios', 'ubicaciones.municipios_id', '=', 'municipios.id')
                ->leftJoin('estados', 'municipios.estado_id', '=', 'estados.id')
                ->leftJoin('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id')
                ->leftJoin('modalidad', 'escolaridad_alumno.modalidad_id', '=', 'modalidad.id')
                ->leftJoin('carreras', 'escolaridad_alumno.carreras_id', '=', 'carreras.id')
                ->leftJoin('semestres', 'escolaridad_alumno.semestres_id', '=', 'semestres.id')
                ->leftJoin('grupos', 'escolaridad_alumno.grupos_id', '=', 'grupos.id')
                ->leftJoin('programa_servicio_social', 'alumno.id', '=', 'programa_servicio_social.alumno_id')
                ->leftJoin('instituciones', 'programa_servicio_social.instituciones_id', '=', 'instituciones.id')
                ->leftJoin('titulos', 'programa_servicio_social.titulos_id', '=', 'titulos.id')
                ->leftJoin('metodo_servicio', 'programa_servicio_social.metodo_servicio_id', '=', 'metodo_servicio.id')
                ->leftJoin('tipos_programa', 'programa_servicio_social.tipos_programa_id', '=', 'tipos_programa.id')
                ->select([
                    // Información básica del alumno
                    'alumno.id',
                    'alumno.correo_institucional',
                    'alumno.apellido_p',
                    'alumno.apellido_m',
                    'alumno.nombre',
                    'alumno.telefono',
                    'alumno.fecha_registro',
                    'sexo.tipo as sexo_tipo',
                    'edad.edades',
                    
                    // Información de ubicación (nueva estructura)
                    'ubicaciones.localidad',
                    'ubicaciones.cp',
                    'municipios.nombre as municipio_nombre',
                    'estados.nombre as estado_nombre',
                    
                    // Información académica
                    'escolaridad_alumno.numero_control',
                    'escolaridad_alumno.meses_servicio',
                    'modalidad.nombre as modalidad_nombre',
                    'carreras.nombre as carrera_nombre',
                    'semestres.nombre as semestre_nombre',
                    'grupos.letra',
                    
                    // Información de servicio social
                    'instituciones.nombre as institucion_nombre',
                    'programa_servicio_social.otra_institucion',
                    'programa_servicio_social.nombre_programa',
                    'programa_servicio_social.encargado_nombre',
                    'titulos.titulo',
                    'programa_servicio_social.puesto_encargado',
                    'metodo_servicio.metodo',
                    'programa_servicio_social.telefono_institucion',
                    'programa_servicio_social.fecha_inicio',
                    'programa_servicio_social.fecha_final',
                    'tipos_programa.tipo as tipo_programa'
                ])
                ->where('alumno.id', $id)
                ->first();
        } catch (\Exception $e) {
            \Log::error('Error en obtenerDatosCompletos: ' . $e->getMessage());
            return null;
        }
    }

    private function generarFormatoWord($alumno)
    {
        if (!class_exists(\ZipArchive::class)) {
            \Log::error('Extensión ZIP no disponible en runtime');
            abort(500, 'Extensión ZIP no disponible en el servidor');
        }
        try {
            // Obtener la plantilla desde la base de datos
            $formato = DB::table('formatos')
                ->select('formato_word')
                ->where('usuario_id', 1) // O el usuario que corresponda
                ->first();

            if (!$formato || !$formato->formato_word) {
                return $this->crearDocumentoBasico($alumno);
            }

            // Crear archivo temporal con la plantilla desde la BD
            $tempTemplate = tempnam(sys_get_temp_dir(), 'template_') . '.docx';
            file_put_contents($tempTemplate, $formato->formato_word);

            // Crear el TemplateProcessor
            $templateProcessor = new TemplateProcessor($tempTemplate);

            // Reemplazar los placeholders con los datos del alumno
            $templateProcessor->setValue('nombre', $alumno->nombre ?? '');
            $templateProcessor->setValue('apellido_p', $alumno->apellido_p ?? '');
            $templateProcessor->setValue('apellido_m', $alumno->apellido_m ?? '');
            $templateProcessor->setValue('correo_institucional', $alumno->correo_institucional ?? '');
            $templateProcessor->setValue('telefono', $alumno->telefono ?? '');
            $templateProcessor->setValue('numero_control', $alumno->numero_control ?? '');
            $templateProcessor->setValue('carrera', $alumno->carrera_nombre ?? '');
            $templateProcessor->setValue('semestre', $alumno->semestre_nombre ?? '');
            $templateProcessor->setValue('grupo', $alumno->letra ?? '');
            $templateProcessor->setValue('modalidad', $alumno->modalidad_nombre ?? '');
            $templateProcessor->setValue('institucion', $alumno->institucion_nombre ?? '');
            $templateProcessor->setValue('nombre_programa', $alumno->nombre_programa ?? '');
            $templateProcessor->setValue('encargado', $alumno->encargado_nombre ?? '');
            $templateProcessor->setValue('encargado_nombre', $alumno->encargado_nombre ?? '');
            $templateProcessor->setValue('titulo', $alumno->titulo ?? '');
            $templateProcessor->setValue('puesto_encargado', $alumno->puesto_encargado ?? '');
            $templateProcessor->setValue('fecha_inicio', $alumno->fecha_inicio ?? '');
            $templateProcessor->setValue('fecha_final', $alumno->fecha_final ?? '');
            $templateProcessor->setValue('localidad', $alumno->localidad ?? '');
            $templateProcessor->setValue('municipio', $alumno->municipio_nombre ?? '');
            $templateProcessor->setValue('estado', $alumno->estado_nombre ?? '');
            $templateProcessor->setValue('cp', $alumno->cp ?? '');
            $templateProcessor->setValue('meses_servicio', $alumno->meses_servicio ?? '');
            $templateProcessor->setValue('metodo', $alumno->metodo ?? '');
            $templateProcessor->setValue('tipo_programa', $alumno->tipo_programa ?? '');
            $templateProcessor->setValue('telefono_institucion', $alumno->telefono_institucion ?? '');
            $templateProcessor->setValue('sexo_tipo', $alumno->sexo_tipo ?? '');
            $templateProcessor->setValue('edades', $alumno->edades ?? '');

            // Guardar el documento generado
            $tempFile = tempnam(sys_get_temp_dir(), 'formato_') . '.docx';
            $templateProcessor->saveAs($tempFile);

            // Limpiar archivo temporal de plantilla
            unlink($tempTemplate);

            // Enviar el archivo para descargar
            return response()->download($tempFile, 'carta_presentacion_' . $alumno->id . '.docx')
                           ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar el documento Word: ' . $e->getMessage(),
                'alumno' => $alumno
            ]);
        }
    }

    private function generarReporte($alumno)
    {
        try {
            // Obtener la plantilla de reporte desde la base de datos
            $formato = DB::table('formatos')
                ->select('formato_reporte')
                ->where('usuario_id', 1)
                ->first();

            if (!$formato || !$formato->formato_reporte) {
                return $this->crearReporteBasico($alumno);
            }

            // Crear archivo temporal con la plantilla desde la BD
            $tempTemplate = tempnam(sys_get_temp_dir(), 'reporte_template_') . '.docx';
            file_put_contents($tempTemplate, $formato->formato_reporte);

            // Crear el TemplateProcessor
            $templateProcessor = new TemplateProcessor($tempTemplate);

            // Reemplazar placeholders (similar al método anterior)
            $this->reemplazarPlaceholders($templateProcessor, $alumno);

            // Guardar el documento generado
            $tempFile = tempnam(sys_get_temp_dir(), 'reporte_') . '.docx';
            $templateProcessor->saveAs($tempFile);

            // Limpiar archivo temporal de plantilla
            unlink($tempTemplate);

            return response()->download($tempFile, 'reporte_mensual_' . $alumno->id . '.docx')
                           ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar el reporte: ' . $e->getMessage(),
                'alumno' => $alumno
            ]);
        }
    }

    private function generarReporteFinal($alumno)
    {
        try {
            // Obtener la plantilla de reporte final desde la base de datos
            $formato = DB::table('formatos')
                ->select('formato_reporte_final')
                ->where('usuario_id', 1)
                ->first();

            if (!$formato || !$formato->formato_reporte_final) {
                return $this->crearReporteFinalBasico($alumno);
            }

            // Crear archivo temporal con la plantilla desde la BD
            $tempTemplate = tempnam(sys_get_temp_dir(), 'reporte_final_template_') . '.docx';
            file_put_contents($tempTemplate, $formato->formato_reporte_final);

            // Crear el TemplateProcessor
            $templateProcessor = new TemplateProcessor($tempTemplate);

            // Reemplazar placeholders
            $this->reemplazarPlaceholders($templateProcessor, $alumno);

            // Guardar el documento generado
            $tempFile = tempnam(sys_get_temp_dir(), 'reporte_final_') . '.docx';
            $templateProcessor->saveAs($tempFile);

            // Limpiar archivo temporal de plantilla
            unlink($tempTemplate);

            return response()->download($tempFile, 'reporte_final_' . $alumno->id . '.docx')
                           ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al generar el reporte final: ' . $e->getMessage(),
                'alumno' => $alumno
            ]);
        }
    }

    private function reemplazarPlaceholders($templateProcessor, $alumno)
    {
        // Método auxiliar para reemplazar todos los placeholders
        $templateProcessor->setValue('nombre', $alumno->nombre ?? '');
        $templateProcessor->setValue('apellido_p', $alumno->apellido_p ?? '');
        $templateProcessor->setValue('apellido_m', $alumno->apellido_m ?? '');
        $templateProcessor->setValue('correo_institucional', $alumno->correo_institucional ?? '');
        $templateProcessor->setValue('telefono', $alumno->telefono ?? '');
        $templateProcessor->setValue('numero_control', $alumno->numero_control ?? '');
        $templateProcessor->setValue('carrera', $alumno->carrera_nombre ?? '');
        $templateProcessor->setValue('semestre', $alumno->semestre_nombre ?? '');
        $templateProcessor->setValue('grupo', $alumno->letra ?? '');
        $templateProcessor->setValue('modalidad', $alumno->modalidad_nombre ?? '');
        $templateProcessor->setValue('institucion', $alumno->institucion_nombre ?? '');
        $templateProcessor->setValue('nombre_programa', $alumno->nombre_programa ?? '');
        $templateProcessor->setValue('encargado', $alumno->encargado_nombre ?? '');
        $templateProcessor->setValue('encargado_nombre', $alumno->encargado_nombre ?? '');
        $templateProcessor->setValue('titulo', $alumno->titulo ?? '');
        $templateProcessor->setValue('puesto_encargado', $alumno->puesto_encargado ?? '');
        $templateProcessor->setValue('fecha_inicio', $alumno->fecha_inicio ?? '');
        $templateProcessor->setValue('fecha_final', $alumno->fecha_final ?? '');
        $templateProcessor->setValue('localidad', $alumno->localidad ?? '');
        $templateProcessor->setValue('municipio', $alumno->municipio_nombre ?? '');
        $templateProcessor->setValue('estado', $alumno->estado_nombre ?? '');
        $templateProcessor->setValue('cp', $alumno->cp ?? '');
        $templateProcessor->setValue('meses_servicio', $alumno->meses_servicio ?? '');
        $templateProcessor->setValue('metodo', $alumno->metodo ?? '');
        $templateProcessor->setValue('tipo_programa', $alumno->tipo_programa ?? '');
        $templateProcessor->setValue('telefono_institucion', $alumno->telefono_institucion ?? '');
        $templateProcessor->setValue('sexo_tipo', $alumno->sexo_tipo ?? '');
        $templateProcessor->setValue('edades', $alumno->edades ?? '');
    }

    private function crearDocumentoBasico($alumno)
    {
        try {
            // Crear un documento Word simple sin plantilla
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            
            // Crear una sección
            $section = $phpWord->addSection();
            
            // Agregar título
            $section->addText('CENTRO DE BACHILLERATO TECNOLÓGICO AGROPECUARIO No. 256', 
                ['bold' => true, 'size' => 16], ['alignment' => 'center']);
            $section->addText('CARTA DE PRESENTACIÓN', 
                ['bold' => true, 'size' => 14], ['alignment' => 'center']);
            
            $section->addTextBreak(2);
            
            // Información del alumno
            $section->addText("Nombre: {$alumno->nombre} {$alumno->apellido_p} {$alumno->apellido_m}");
            $section->addText("Número de Control: {$alumno->numero_control}");
            $section->addText("Carrera: {$alumno->carrera_nombre}");
            $section->addText("Semestre: {$alumno->semestre_nombre}");
            $section->addText("Grupo: {$alumno->letra}");
            $section->addText("Modalidad: {$alumno->modalidad_nombre}");
            
            $section->addTextBreak();
            
            $section->addText("Correo: {$alumno->correo_institucional}");
            $section->addText("Teléfono: {$alumno->telefono}");
            $section->addText("Domicilio: {$alumno->localidad}, {$alumno->municipio_nombre}, {$alumno->estado_nombre}");
            $section->addText("C.P.: {$alumno->cp}");
            
            $section->addTextBreak();
            
            // Información del servicio social
            $section->addText("SERVICIO SOCIAL", ['bold' => true]);
            $section->addText("Programa: {$alumno->nombre_programa}");
            $section->addText("Institución: {$alumno->institucion_nombre}");
            $section->addText("Método: {$alumno->metodo}");
            $section->addText("Tipo: {$alumno->tipo_programa}");
            $section->addText("Periodo: Del {$alumno->fecha_inicio} al {$alumno->fecha_final}");
            $section->addText("Duración: {$alumno->meses_servicio} meses");
            
            $section->addTextBreak();
            
            $section->addText("Encargado: {$alumno->titulo} {$alumno->encargado_nombre}");
            $section->addText("Puesto: {$alumno->puesto_encargado}");
            $section->addText("Teléfono de la institución: {$alumno->telefono_institucion}");
            
            // Guardar el documento
            $tempFile = tempnam(sys_get_temp_dir(), 'formato_basico_') . '.docx';
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);
            
            return response()->download($tempFile, 'carta_presentacion_' . $alumno->id . '.docx')
                           ->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear documento básico: ' . $e->getMessage(),
                'alumno' => $alumno
            ]);
        }
    }

    private function crearReporteBasico($alumno)
    {
        // Similar al documento básico pero para reporte mensual
        return response()->json([
            'message' => 'Plantilla de reporte no encontrada, generando básico para: ' . $alumno->nombre,
            'tipo' => 'reporte_basico',
            'data' => $alumno
        ]);
    }

    private function crearReporteFinalBasico($alumno)
    {
        // Similar al documento básico pero para reporte final
        return response()->json([
            'message' => 'Plantilla de reporte final no encontrada, generando básico para: ' . $alumno->nombre,
            'tipo' => 'reporte_final_basico',
            'data' => $alumno
        ]);
    }

    public function finalizarFormulario(Request $request)
    {
        try {
            // Validar datos del request
            $request->validate([
                'instituciones_id' => 'required',
                'telefono_institucion' => 'required|string|size:10',
                'nombre_programa' => 'required|string|max:100',
                'tipos_programa_id' => 'required',
                'metodo_servicio_id' => 'required',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after:fecha_inicio',
                'titulos_id' => 'required',
                'encargado_nombre' => 'required|string|max:100',
                'puesto_encargado' => 'required|string|max:100',
            ]);

            // Obtener datos de la sesión
            $alumnoId = Session::get('alumno_id');
            
            if (!$alumnoId) {
                return redirect('/datosalumno')->with('error', 'Sesión expirada. Inicia el registro nuevamente.');
            }

            // Manejar institución "otra"
            $institucionId = $request->instituciones_id;
            if ($request->instituciones_id === 'otra' && $request->otra_institucion) {
                // Insertar nueva institución
                $institucionId = DB::table('instituciones')->insertGetId([
                    'nombre' => $request->otra_institucion,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Manejar tipo de programa "otro"
            $tipoProgramaId = $request->tipos_programa_id;
            if ($request->tipos_programa_id === '0' && $request->otro_programa) {
                // Insertar nuevo tipo de programa
                $tipoProgramaId = DB::table('tipos_programa')->insertGetId([
                    'tipo' => $request->otro_programa,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Insertar programa de servicio social
            $programaId = DB::table('programa_servicio_social')->insertGetId([
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
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Obtener información para mostrar en la página de éxito
            $alumno = DB::table('alumno')->where('id', $alumnoId)->first();
            $escolaridad = DB::table('escolaridad_alumno')
                ->leftJoin('carreras', 'escolaridad_alumno.carreras_id', '=', 'carreras.id')
                ->where('escolaridad_alumno.alumno_id', $alumnoId)
                ->select('escolaridad_alumno.numero_control', 'carreras.nombre as carrera_nombre')
                ->first();
            
            $institucion = DB::table('instituciones')->where('id', $institucionId)->first();

            // Guardar información en la sesión para la página de éxito
            Session::put([
                'registro_exitoso' => true,
                'alumno_nombre' => $alumno->nombre . ' ' . $alumno->apellido_p . ' ' . $alumno->apellido_m,
                'numero_control' => $escolaridad->numero_control ?? 'No disponible',
                'carrera_nombre' => $escolaridad->carrera_nombre ?? 'No disponible',
                'programa_nombre' => $request->nombre_programa,
                'institucion_nombre' => $institucion->nombre ?? 'No disponible'
            ]);

            // CAMBIAR esta línea:
            return redirect('/registro-exitoso'); 

            // POR una de estas opciones:
            return redirect('/final'); // Opción simple
            // O
            return redirect()->route('registro.exitoso'); // Usando el nombre de la ruta

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar la información: ' . $e->getMessage());
        }
    }

    public function index()
    {
        return view('formatos-upload');
    }

    public function store(Request $request)
    {
        // Lógica para guardar formatos
        try {
            // Tu lógica aquí
            return redirect()->route('formatos.upload')->with('success', 'Formato subido correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir formato: ' . $e->getMessage());
        }
    }
}