<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                // Se elimina 'codigo_postal'
                'fecha_registro'        => Carbon::now()->format('Y-m-d H:i:s'),
                'sexo_id'               => $datosAlumno['sexo'],
                'status_id'             => 1,
                'edad_id'               => (int)$datosAlumno['edad'],
                'rol_id'                => 2,
            ];

            // ---------- PROCESAR UBICACIÓN ----------
            $municipio = DB::table('municipios')
                          ->where('nombre', $datosAlumno['municipio'])
                          ->first();
            $idMunicipio = $municipio ? $municipio->id : 1;

            $ubicacionInsert = [
                'localidad'      => $datosAlumno['localidad'],
                'cp'             => (int)$datosAlumno['cp'],  // aquí se guarda el código postal
                'municipios_id'  => $idMunicipio,
            ];
            $ubicaciones_id = DB::table('ubicaciones')->insertGetId($ubicacionInsert);
            $alumnoInsert['ubicaciones_id'] = $ubicaciones_id;

            // Insertar en la tabla alumno y continuar con el proceso...
            $alumno_id = DB::table('alumno')->insertGetId($alumnoInsert);

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
}
