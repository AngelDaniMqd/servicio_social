<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

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
            $request->session()->put('datos_alumno', $request->all());
            return redirect('/escolaridad');
        } catch (\Throwable $e) {
            return redirect('/datos-alumno')->with('error', 'No se pudieron guardar los datos del alumno. Intenta más tarde.');
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
            $request->session()->put('datos_escolaridad', $request->all());
            return redirect('/programa');
        } catch (\Throwable $e) {
            return redirect('/escolaridad')->with('error', 'No se pudieron guardar los datos de escolaridad.');
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
        return DB::transaction(function() use ($request) {
            // Incluye 'localidad' y 'municipio' en los datos del alumno
            $datosAlumno = session('datos_alumno', $request->only([
                'correo_institucional',
                'apellido_paterno',
                'apellido_materno',
                'nombre',
                'telefono',
                'cp',
                'sexo',
                'edad',
                'localidad',
                'municipio'
            ]));

            if (!isset($datosAlumno['correo_institucional'])) {
                return redirect('/datos-alumno')->with('error', 'No se encontraron los datos del alumno. Intenta nuevamente.');
            }

            // ---------- PROCESAR ALUMNO ----------
            $alumnoInsert = [
                'correo_institucional'  => $datosAlumno['correo_institucional'],
                'apellido_p'            => $datosAlumno['apellido_paterno'],
                'apellido_m'            => $datosAlumno['apellido_materno'],
                'nombre'                => $datosAlumno['nombre'],
                'telefono'              => (int)$datosAlumno['telefono'],
                'fecha_registro'        => Carbon::now()->format('Y-m-d H:i:s'),
                'sexo_id'               => $datosAlumno['sexo'],
                'status_id'             => 1,
                'edad_id'               => (int)$datosAlumno['edad'],
                'rol_id'                => 2,
            ];

            // Insertar alumno primero para obtener su ID
            $alumno_id = DB::table('alumno')->insertGetId($alumnoInsert);

            // ---------- PROCESAR UBICACIÓN ----------
            $municipio = DB::table('municipios')
                          ->where('nombre', $datosAlumno['municipio'])
                          ->first();
            $idMunicipio = $municipio ? $municipio->id : 1;

            $ubicacionInsert = [
                'alumno_id'      => $alumno_id,  // Cambio aquí - ahora ubicaciones tiene alumno_id
                'localidad'      => $datosAlumno['localidad'],
                'cp'             => (int)$datosAlumno['cp'],
                'municipios_id'  => $idMunicipio,
            ];

            // Insertar ubicación
            DB::table('ubicaciones')->insert($ubicacionInsert);

            // Insertar la escolaridad del alumno
            $datosEscolaridad = session('datos_escolaridad', $request->only([
                'matricula',
                'meses_servicio',
                'modalidad_id',
                'carreras_id',
                'semestres_id',
                'grupos_id'
            ]));

            if ($datosEscolaridad) {
                $escolaridadInsert = [
                    'numero_control' => $datosEscolaridad['matricula'],
                    'meses_servicio' => (int)$datosEscolaridad['meses_servicio'],
                    'alumno_id'      => $alumno_id,
                    'modalidad_id'   => $datosEscolaridad['modalidad_id'],
                    'carreras_id'    => $datosEscolaridad['carreras_id'],
                    'semestres_id'   => $datosEscolaridad['semestres_id'],
                    'grupos_id'      => $datosEscolaridad['grupos_id'],
                ];
                DB::table('escolaridad_alumno')->insert($escolaridadInsert);
            }

            // Recopilar datos del formulario de programa
            $datosPrograma = $request->only([
                'instituciones_id',
                'otra_institucion',
                'nombre_programa',
                'encargado_nombre',
                'titulos_id',
                'puesto_encargado',
                'metodo_servicio_id',
                'telefono_institucion',
                'fecha_inicio',
                'fecha_final',
                'tipos_programa_id',
                'otro_programa'
            ]);

            // Agregar el id del alumno y el status por defecto
            $datosPrograma['alumno_id'] = $alumno_id;
            $datosPrograma['status_id'] = 1;

            // Insertar en la tabla programa_servicio_social
            DB::table('programa_servicio_social')->insert($datosPrograma);

            // Finalmente, olvidar los datos de sesión
            session()->forget('datos_escolaridad');
            session()->forget('datos_alumno');
            
            return view('final', ['nombre' => $datosAlumno['nombre']]);
        });
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
