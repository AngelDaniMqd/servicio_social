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
            \Log::error('Error al generar documento: ' . $e->getMessage(), [
                'alumno_id' => $id,
                'tipo' => $tipo,
                'trace' => $e->getTraceAsString()
            ]);
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
                    'programa_servicio_social.instituciones_id',
                    'programa_servicio_social.nombre_programa',
                    'programa_servicio_social.encargado_nombre',
                    'titulos.titulo',
                    'programa_servicio_social.puesto_encargado',
                    'metodo_servicio.metodo',
                    'programa_servicio_social.telefono_institucion',
                    'programa_servicio_social.fecha_inicio',
                    'programa_servicio_social.fecha_final',
                    'tipos_programa.tipo as tipo_programa',
                    'programa_servicio_social.tipos_programa_id',
                    'programa_servicio_social.otro_programa'
                ])
                ->where('alumno.id', $id)
                ->first();
        } catch (\Exception $e) {
            \Log::error('Error en obtenerDatosCompletos: ' . $e->getMessage());
            return null;
        }
    }

    private function ensureStorageDirectories()
    {
        $dirs = ['temp', 'documents'];
        foreach ($dirs as $dir) {
            if (!Storage::exists($dir)) {
                Storage::makeDirectory($dir);
            }
        }
    }

    private function generarFormatoWord($alumno)
    {
        try {
            // Asegurar que los directorios existan
            $this->ensureStorageDirectories();

            // Obtener la plantilla desde la base de datos
            $formato = DB::table('formatos')->first();
            
            if (!$formato || !$formato->formato_word) {
                \Log::warning('No se encontró plantilla Word, generando documento básico');
                return $this->crearDocumentoBasico($alumno);
            }

            // Crear nombres únicos para archivos temporales
            $templateFileName = 'temp/template_' . uniqid() . '.docx';
            $outputFileName = 'temp/output_' . uniqid() . '.docx';

            // Guardar la plantilla temporalmente usando Storage
            Storage::put($templateFileName, $formato->formato_word);
            $tempTemplatePath = Storage::path($templateFileName);

            $templateProcessor = new TemplateProcessor($tempTemplatePath);

            // Reemplazar los placeholders
            $this->reemplazarPlaceholders($templateProcessor, $alumno);

            // Generar archivo de salida
            $outputPath = Storage::path($outputFileName);
            $templateProcessor->saveAs($outputPath);

            // Crear nombre del archivo final
            $filename = "carta_presentacion_{$alumno->numero_control}.docx";
            
            // Leer el contenido del archivo y eliminarlo después
            $fileContent = Storage::get($outputFileName);
            
            // Limpiar archivos temporales
            Storage::delete($templateFileName);
            Storage::delete($outputFileName);
            
            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));

        } catch (\Exception $e) {
            \Log::error('Error generando formato Word: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback a documento básico
            return $this->crearDocumentoBasico($alumno);
        }
    }

    private function generarReporte($alumno)
    {
        try {
            $this->ensureStorageDirectories();

            $formato = DB::table('formatos')->first();
            
            if (!$formato || !$formato->formato_reporte) {
                \Log::warning('No se encontró plantilla de reporte, generando básico');
                return $this->crearReporteBasico($alumno);
            }

            $templateFileName = 'temp/template_reporte_' . uniqid() . '.docx';
            $outputFileName = 'temp/output_reporte_' . uniqid() . '.docx';

            Storage::put($templateFileName, $formato->formato_reporte);
            $tempTemplatePath = Storage::path($templateFileName);

            $templateProcessor = new TemplateProcessor($tempTemplatePath);
            $this->reemplazarPlaceholders($templateProcessor, $alumno);

            $outputPath = Storage::path($outputFileName);
            $templateProcessor->saveAs($outputPath);

            $filename = "reporte_mensual_{$alumno->numero_control}.docx";
            
            $fileContent = Storage::get($outputFileName);
            
            Storage::delete($templateFileName);
            Storage::delete($outputFileName);
            
            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));

        } catch (\Exception $e) {
            \Log::error('Error generando reporte: ' . $e->getMessage());
            return $this->crearReporteBasico($alumno);
        }
    }

    private function generarReporteFinal($alumno)
    {
        try {
            $this->ensureStorageDirectories();

            $formato = DB::table('formatos')->first();
            
            if (!$formato || !$formato->formato_reporte_final) {
                \Log::warning('No se encontró plantilla de reporte final, generando básico');
                return $this->crearReporteFinalBasico($alumno);
            }

            $templateFileName = 'temp/template_final_' . uniqid() . '.docx';
            $outputFileName = 'temp/output_final_' . uniqid() . '.docx';

            Storage::put($templateFileName, $formato->formato_reporte_final);
            $tempTemplatePath = Storage::path($templateFileName);

            $templateProcessor = new TemplateProcessor($tempTemplatePath);
            $this->reemplazarPlaceholders($templateProcessor, $alumno);

            $outputPath = Storage::path($outputFileName);
            $templateProcessor->saveAs($outputPath);

            $filename = "reporte_final_{$alumno->numero_control}.docx";
            
            $fileContent = Storage::get($outputFileName);
            
            Storage::delete($templateFileName);
            Storage::delete($outputFileName);
            
            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));

        } catch (\Exception $e) {
            \Log::error('Error generando reporte final: ' . $e->getMessage());
            return $this->crearReporteFinalBasico($alumno);
        }
    }

    private function reemplazarPlaceholders($templateProcessor, $alumno)
    {
        // Datos del alumno
        $templateProcessor->setValue('nombre', $alumno->nombre ?? '');
        $templateProcessor->setValue('apellido_p', $alumno->apellido_p ?? '');
        $templateProcessor->setValue('apellido_m', $alumno->apellido_m ?? '');
        $templateProcessor->setValue('numero_control', $alumno->numero_control ?? '');
        
        // Institución: usar otra_institucion si instituciones_id es 12, sino usar institucion_nombre
        if ($alumno->instituciones_id == 12 && !empty($alumno->otra_institucion)) {
            $templateProcessor->setValue('institucion', $alumno->otra_institucion);
        } else {
            $templateProcessor->setValue('institucion', $alumno->institucion_nombre ?? '');
        }
        
        // Programa: usar otro_programa si tipos_programa_id es 0, sino usar tipo_programa
        if ($alumno->tipos_programa_id == 0 && !empty($alumno->otro_programa)) {
            $templateProcessor->setValue('tipo_programa', $alumno->otro_programa);
        } else {
            $templateProcessor->setValue('tipo_programa', $alumno->tipo_programa ?? '');
        }
        
        $templateProcessor->setValue('nombre_programa', $alumno->nombre_programa ?? '');
        $templateProcessor->setValue('encargado_nombre', $alumno->encargado_nombre ?? '');
        $templateProcessor->setValue('puesto_encargado', $alumno->puesto_encargado ?? '');
        $templateProcessor->setValue('titulo', $alumno->titulo ?? '');
        $templateProcessor->setValue('telefono_institucion', $alumno->telefono_institucion ?? '');
        $templateProcessor->setValue('metodo', $alumno->metodo ?? '');
        $templateProcessor->setValue('fecha_inicio', $alumno->fecha_inicio ?? '');
        $templateProcessor->setValue('fecha_final', $alumno->fecha_final ?? '');
        $templateProcessor->setValue('carrera', $alumno->carrera_nombre ?? '');
        $templateProcessor->setValue('semestre', $alumno->semestre_nombre ?? '');
        $templateProcessor->setValue('grupo', $alumno->letra ?? '');
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
            
            // Usar la lógica correcta para mostrar la institución
            $institucion = ($alumno->instituciones_id == 12 && !empty($alumno->otra_institucion)) 
                ? $alumno->otra_institucion 
                : $alumno->institucion_nombre;
            $section->addText("Institución: {$institucion}");
            
            $section->addText("Método: {$alumno->metodo}");
            
            // Usar la lógica correcta para mostrar el tipo de programa
            $tipoPrograma = ($alumno->tipos_programa_id == 0 && !empty($alumno->otro_programa)) 
                ? $alumno->otro_programa 
                : $alumno->tipo_programa;
            $section->addText("Tipo: {$tipoPrograma}");
            
            $section->addText("Periodo: Del {$alumno->fecha_inicio} al {$alumno->fecha_final}");
            $section->addText("Duración: {$alumno->meses_servicio} meses");
            
            $section->addTextBreak();
            
            $section->addText("Encargado: {$alumno->titulo} {$alumno->encargado_nombre}");
            $section->addText("Puesto: {$alumno->puesto_encargado}");
            $section->addText("Teléfono de la institución: {$alumno->telefono_institucion}");
            
            // Guardar usando Storage
            $this->ensureStorageDirectories();
            $fileName = 'temp/formato_basico_' . uniqid() . '.docx';
            $filePath = Storage::path($fileName);
            
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($filePath);
            
            $fileContent = Storage::get($fileName);
            Storage::delete($fileName);
            
            $downloadName = 'carta_presentacion_' . $alumno->numero_control . '.docx';
            
            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $downloadName . '"')
                ->header('Content-Length', strlen($fileContent));
            
        } catch (\Exception $e) {
            \Log::error('Error creando documento básico: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al crear documento básico: ' . $e->getMessage(),
                'alumno' => $alumno
            ], 500);
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

    // Métodos públicos para export (con validación de sesión)
    public function exportSolicitud($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId, 'word');
    }

    public function exportEscolaridad($alumnoId)
    {
        // Para escolaridad, puedes crear un documento específico o usar el mismo método
        return $this->crearDocumentoEscolaridad($alumnoId);
    }

    public function exportPrograma($alumnoId)
    {
        // Para programa, puedes crear un documento específico o usar el mismo método
        return $this->crearDocumentoPrograma($alumnoId);
    }

    public function exportReporteFinal($alumnoId)
    {
        return $this->downloadEditedWord($alumnoId, 'reporte_final');
    }

    private function crearDocumentoEscolaridad($alumnoId)
    {
        try {
            $alumno = $this->obtenerDatosCompletos($alumnoId);
            
            if (!$alumno) {
                abort(404, 'Alumno no encontrado');
            }

            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            
            // Título
            $section->addText('INFORMACIÓN ESCOLAR', 
                ['bold' => true, 'size' => 16], ['alignment' => 'center']);
            $section->addTextBreak(2);
            
            // Información del alumno
            $section->addText("Alumno: {$alumno->nombre} {$alumno->apellido_p} {$alumno->apellido_m}");
            $section->addText("Número de Control: {$alumno->numero_control}");
            $section->addText("Correo Institucional: {$alumno->correo_institucional}");
            $section->addTextBreak();
            
            // Información académica
            $section->addText("INFORMACIÓN ACADÉMICA", ['bold' => true]);
            $section->addText("Carrera: {$alumno->carrera_nombre}");
            $section->addText("Modalidad: {$alumno->modalidad_nombre}");
            $section->addText("Semestre: {$alumno->semestre_nombre}");
            $section->addText("Grupo: {$alumno->letra}");
            $section->addText("Meses de Servicio: {$alumno->meses_servicio}");
            
            return $this->guardarYDescargarDocumento($phpWord, "escolaridad_{$alumno->numero_control}.docx");
            
        } catch (\Exception $e) {
            \Log::error('Error creando documento escolaridad: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar documento de escolaridad'], 500);
        }
    }

    private function crearDocumentoPrograma($alumnoId)
    {
        try {
            $alumno = $this->obtenerDatosCompletos($alumnoId);
            
            if (!$alumno) {
                abort(404, 'Alumno no encontrado');
            }

            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            
            // Título
            $section->addText('INFORMACIÓN DEL PROGRAMA DE SERVICIO SOCIAL', 
                ['bold' => true, 'size' => 16], ['alignment' => 'center']);
            $section->addTextBreak(2);
            
            // Información del alumno
            $section->addText("Alumno: {$alumno->nombre} {$alumno->apellido_p} {$alumno->apellido_m}");
            $section->addText("Número de Control: {$alumno->numero_control}");
            $section->addTextBreak();
            
            // Información del programa
            $section->addText("PROGRAMA DE SERVICIO SOCIAL", ['bold' => true]);
            $section->addText("Nombre del Programa: {$alumno->nombre_programa}");
            
            // Usar la lógica correcta para mostrar la institución
            $institucion = ($alumno->instituciones_id == 12 && !empty($alumno->otra_institucion)) 
                ? $alumno->otra_institucion 
                : $alumno->institucion_nombre;
            $section->addText("Institución: {$institucion}");
            
            $section->addText("Método: {$alumno->metodo}");
            
            // Usar la lógica correcta para mostrar el tipo de programa
            $tipoPrograma = ($alumno->tipos_programa_id == 0 && !empty($alumno->otro_programa)) 
                ? $alumno->otro_programa 
                : $alumno->tipo_programa;
            $section->addText("Tipo de Programa: {$tipoPrograma}");
            
            $section->addText("Fecha de Inicio: {$alumno->fecha_inicio}");
            $section->addText("Fecha de Término: {$alumno->fecha_final}");
            $section->addTextBreak();
            
            $section->addText("RESPONSABLE DEL PROGRAMA", ['bold' => true]);
            $section->addText("Nombre: {$alumno->titulo} {$alumno->encargado_nombre}");
            $section->addText("Puesto: {$alumno->puesto_encargado}");
            $section->addText("Teléfono: {$alumno->telefono_institucion}");
            
            return $this->guardarYDescargarDocumento($phpWord, "programa_{$alumno->numero_control}.docx");
            
        } catch (\Exception $e) {
            \Log::error('Error creando documento programa: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar documento de programa'], 500);
        }
    }

    private function guardarYDescargarDocumento($phpWord, $filename)
    {
        try {
            $this->ensureStorageDirectories();
            $tempFileName = 'temp/documento_' . uniqid() . '.docx';
            $filePath = Storage::path($tempFileName);
            
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($filePath);
            
            $fileContent = Storage::get($tempFileName);
            Storage::delete($tempFileName);
            
            return response($fileContent)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Content-Length', strlen($fileContent));
                
        } catch (\Exception $e) {
            \Log::error('Error guardando documento: ' . $e->getMessage());
            throw $e;
        }
    }
}