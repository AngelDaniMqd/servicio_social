<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function downloadEditedWord(Request $request, $id)
    {
        try {
            // Obtener el tipo desde la query string, por defecto 'word'
            $tipo = $request->query('tipo', 'word');
            
            \Log::info('=== INICIANDO DESCARGA RAILWAY ===', [
                'alumno_id' => $id,
                'tipo' => $tipo,
                'query_params' => $request->all(),
                'full_url' => $request->fullUrl()
            ]);

            // Verificar extensiones críticas
            $missingExtensions = [];
            if (!extension_loaded('zip')) $missingExtensions[] = 'zip';
            if (!extension_loaded('xml')) $missingExtensions[] = 'xml';
            if (!extension_loaded('mbstring')) $missingExtensions[] = 'mbstring';
            
            if (!empty($missingExtensions)) {
                throw new \Exception('Extensiones faltantes: ' . implode(', ', $missingExtensions));
            }

            // Verificar PhpWord
            if (!class_exists('\PhpOffice\PhpWord\TemplateProcessor')) {
                throw new \Exception('PhpOffice\PhpWord\TemplateProcessor no disponible');
            }

            // Obtener datos del alumno
            $alumno = $this->obtenerDatosCompletos($id);
            if (!$alumno) {
                throw new \Exception('Alumno no encontrado con ID: ' . $id);
            }

            // Log para debugging del tipo recibido
            \Log::info('Tipo de documento solicitado:', [
                'tipo' => $tipo,
                'switch_case' => match($tipo) {
                    'word' => 'CARTA DE PRESENTACIÓN',
                    'reporte' => 'REPORTE MENSUAL',
                    'reporte_final' => 'REPORTE FINAL',
                    default => 'TIPO DESCONOCIDO'
                }
            ]);

            // Procesar según tipo
            switch ($tipo) {
                case 'word':
                    \Log::info('Entrando a generarFormatoWordRailway');
                    return $this->generarFormatoWordRailway($alumno);
                    
                case 'reporte':
                    \Log::info('Entrando a generarReporteRailway');
                    return $this->generarReporteRailway($alumno);
                    
                case 'reporte_final':
                    \Log::info('Entrando a generarReporteFinalRailway');
                    return $this->generarReporteFinalRailway($alumno);
                    
                default:
                    throw new \Exception('Tipo de formato no válido: ' . $tipo);
            }

        } catch (\Exception $e) {
            \Log::error('Error en downloadEditedWord (Railway):', [
                'alumno_id' => $id,
                'tipo' => $request->query('tipo', 'TIPO_NO_ENCONTRADO'),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al generar documento: ' . $e->getMessage(),
                'debug_info' => [
                    'alumno_id' => $id,
                    'tipo' => $request->query('tipo', 'no_especificado'),
                    'url' => $request->fullUrl(),
                    'extensions' => [
                        'zip' => extension_loaded('zip'),
                        'xml' => extension_loaded('xml')
                    ]
                ]
            ], 500);
        }
    }

    private function generarFormatoWordRailway($alumno)
    {
        try {
            \Log::info('Generando CARTA DE PRESENTACIÓN', ['alumno_id' => $alumno->id]);

            $formato = DB::table('formatos')->first();
            if (!$formato || !$formato->formato_word) {
                throw new \Exception('No hay plantilla de CARTA configurada');
            }

            $tempDir = 'temp/documents';
            if (!Storage::exists($tempDir)) {
                Storage::makeDirectory($tempDir);
            }

            $templateFile = $tempDir . '/template_carta_' . uniqid() . '.docx';
            Storage::put($templateFile, $formato->formato_word); // ✅ Usa formato_word

            $templatePath = Storage::path($templateFile);
            $templateProcessor = new TemplateProcessor($templatePath);
            $this->reemplazarPlaceholdersRailway($templateProcessor, $alumno);

            $outputFile = $tempDir . '/carta_' . $alumno->id . '_' . time() . '.docx';
            $outputPath = Storage::path($outputFile);
            $templateProcessor->saveAs($outputPath);

            $fileContent = file_get_contents($outputPath);
            $filename = 'carta_presentacion_' . $alumno->nombre . '_' . $alumno->apellido_p . '.docx';

            Storage::delete($templateFile);
            Storage::delete($outputFile);

            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));

        } catch (\Exception $e) {
            \Log::error('Error generando CARTA:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function reemplazarPlaceholdersRailway($templateProcessor, $alumno)
    {
        try {
            // Variables básicas del alumno
            $templateProcessor->setValue('nombre', $alumno->nombre ?? '');
            $templateProcessor->setValue('apellido_p', $alumno->apellido_p ?? '');
            $templateProcessor->setValue('apellido_m', $alumno->apellido_m ?? '');
            $templateProcessor->setValue('correo_institucional', $alumno->correo_institucional ?? '');
            $templateProcessor->setValue('telefono', $alumno->telefono ?? '');

            // Información académica
            $templateProcessor->setValue('numero_control', $alumno->numero_control ?? '');
            $templateProcessor->setValue('carrera', $alumno->carrera ?? '');
            $templateProcessor->setValue('semestre', $alumno->semestre ?? '');
            $templateProcessor->setValue('grupo', $alumno->grupo ?? '');
            $templateProcessor->setValue('modalidad', $alumno->modalidad ?? '');

            // Información del programa
            $templateProcessor->setValue('institucion', $alumno->institucion ?? '');
            $templateProcessor->setValue('nombre_programa', $alumno->nombre_programa ?? '');
            $templateProcessor->setValue('encargado_nombre', $alumno->encargado_nombre ?? '');
            $templateProcessor->setValue('puesto_encargado', $alumno->puesto_encargado ?? '');
            $templateProcessor->setValue('telefono_institucion', $alumno->telefono_institucion ?? '');

            // Fechas
            $templateProcessor->setValue('fecha_inicio', $alumno->fecha_inicio ? date('d/m/Y', strtotime($alumno->fecha_inicio)) : '');
            $templateProcessor->setValue('fecha_final', $alumno->fecha_final ? date('d/m/Y', strtotime($alumno->fecha_final)) : '');
            $templateProcessor->setValue('fecha_actual', date('d/m/Y'));

            // Ubicación
            $templateProcessor->setValue('localidad', $alumno->localidad ?? '');
            $templateProcessor->setValue('municipio', $alumno->municipio ?? '');
            $templateProcessor->setValue('estado', $alumno->estado ?? '');
            $templateProcessor->setValue('cp', $alumno->cp ?? '');

            \Log::info('Variables reemplazadas exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error reemplazando placeholders: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generarReporteRailway($alumno)
    {
        try {
            \Log::info('Generando REPORTE MENSUAL', ['alumno_id' => $alumno->id]);

            $formato = DB::table('formatos')->first();
            if (!$formato || !$formato->formato_reporte) {
                throw new \Exception('No hay plantilla de REPORTE MENSUAL configurada');
            }

            $tempDir = 'temp/documents';
            if (!Storage::exists($tempDir)) {
                Storage::makeDirectory($tempDir);
            }

            $templateFile = $tempDir . '/template_reporte_mensual_' . uniqid() . '.docx';
            Storage::put($templateFile, $formato->formato_reporte); // ✅ Usa formato_reporte

            $templatePath = Storage::path($templateFile);
            $templateProcessor = new TemplateProcessor($templatePath);
            $this->reemplazarPlaceholdersRailway($templateProcessor, $alumno);

            $outputFile = $tempDir . '/reporte_mensual_' . $alumno->id . '_' . time() . '.docx';
            $outputPath = Storage::path($outputFile);
            $templateProcessor->saveAs($outputPath);

            $fileContent = file_get_contents($outputPath);
            $filename = 'reporte_mensual_' . $alumno->numero_control . '.docx';

            Storage::delete($templateFile);
            Storage::delete($outputFile);

            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));

        } catch (\Exception $e) {
            \Log::error('Error generando REPORTE MENSUAL:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function generarReporteFinalRailway($alumno)
    {
        try {
            \Log::info('Generando REPORTE FINAL', ['alumno_id' => $alumno->id]);

            $formato = DB::table('formatos')->first();
            if (!$formato || !$formato->formato_reporte_final) {
                throw new \Exception('No hay plantilla de REPORTE FINAL configurada');
            }

            $tempDir = 'temp/documents';
            if (!Storage::exists($tempDir)) {
                Storage::makeDirectory($tempDir);
            }

            $templateFile = $tempDir . '/template_reporte_final_' . uniqid() . '.docx';
            Storage::put($templateFile, $formato->formato_reporte_final); // ✅ Usa formato_reporte_final

            $templatePath = Storage::path($templateFile);
            $templateProcessor = new TemplateProcessor($templatePath);
            $this->reemplazarPlaceholdersRailway($templateProcessor, $alumno);

            $outputFile = $tempDir . '/reporte_final_' . $alumno->id . '_' . time() . '.docx';
            $outputPath = Storage::path($outputFile);
            $templateProcessor->saveAs($outputPath);

            $fileContent = file_get_contents($outputPath);
            $filename = 'reporte_final_' . $alumno->numero_control . '.docx';

            Storage::delete($templateFile);
            Storage::delete($outputFile);

            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));

        } catch (\Exception $e) {
            \Log::error('Error generando REPORTE FINAL:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function obtenerDatosCompletos($id)
    {
        try {
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
                ->where('alumno.id', $id)
                ->select([
                    'alumno.id',
                    'alumno.nombre',
                    'alumno.apellido_p',
                    'alumno.apellido_m',
                    'alumno.correo_institucional',
                    'alumno.telefono',
                    'alumno.fecha_registro',
                    'edad.edades as edad',
                    'sexo.tipo as sexo',
                    'rol.tipo as rol',
                    'status.tipo as status',
                    'ubicaciones.localidad',
                    'ubicaciones.cp',
                    'municipios.nombre as municipio',
                    'estados.nombre as estado',
                    'escolaridad_alumno.numero_control',
                    'escolaridad_alumno.meses_servicio',
                    'carreras.nombre as carrera',
                    'semestres.nombre as semestre',
                    'grupos.letra as grupo',
                    'modalidad.nombre as modalidad',
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
                ])
                ->first();
        } catch (\Exception $e) {
            \Log::error('Error obteniendo datos completos: ' . $e->getMessage());
            return null;
        }
    }

    private function crearReporteBasico($alumno)
    {
        return response()->json([
            'message' => 'Plantilla de reporte no encontrada, generando básico para: ' . $alumno->nombre,
            'tipo' => 'reporte_basico',
            'data' => $alumno
        ]);
    }

    private function crearReporteFinalBasico($alumno)
    {
        return response()->json([
            'message' => 'Plantilla de reporte final no encontrada, generando básico para: ' . $alumno->nombre,
            'tipo' => 'reporte_final_basico',
            'data' => $alumno
        ]);
    }

    // Métodos de exportación pública
    public function exportSolicitud($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId, 'word');
    }

    public function exportEscolaridad($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId, 'reporte');
    }

    public function exportPrograma($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId, 'reporte_final');
    }

    public function exportReporteFinal($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId, 'reporte_final');
    }

    public function store(Request $request)
    {
        try {
            // Validar con límite aumentado (10MB = 10240 KB)
            $rules = [
                'formato_word' => 'nullable|mimes:doc,docx|max:10240',
                'formato_reporte' => 'nullable|mimes:doc,docx|max:10240',
                'formato_reporte_final' => 'nullable|mimes:doc,docx|max:10240',
            ];

            // Si no existe formato, formato_word es obligatorio
            $formatoExistente = DB::table('formatos')->first();
            
            if (!$formatoExistente) {
                $rules['formato_word'] = 'required|mimes:doc,docx|max:10240';
            }

            $request->validate($rules, [
                'formato_word.max' => 'El archivo de carta no puede superar 10MB',
                'formato_reporte.max' => 'El archivo de reporte no puede superar 10MB',
                'formato_reporte_final.max' => 'El archivo de reporte final no puede superar 10MB',
            ]);

            // Log para debugging
            \Log::info('Subiendo formatos:', [
                'files' => [
                    'formato_word' => $request->hasFile('formato_word') ? $request->file('formato_word')->getSize() . ' bytes' : 'no enviado',
                    'formato_reporte' => $request->hasFile('formato_reporte') ? $request->file('formato_reporte')->getSize() . ' bytes' : 'no enviado',
                    'formato_reporte_final' => $request->hasFile('formato_reporte_final') ? $request->file('formato_reporte_final')->getSize() . ' bytes' : 'no enviado',
                ]
            ]);

            // ... resto del código existente

        } catch (\Exception $e) {
            \Log::error('Error subiendo formatos:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir formatos: ' . $e->getMessage());
        }
    }
}