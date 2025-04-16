<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Escolaridad;
use App\Models\Programa;

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
        try {
            $datosAlumno = session('datos_alumno');
            $datosEscolaridad = session('datos_escolaridad');
            $datosPrograma = $request->all();

            if (!$datosAlumno || !$datosEscolaridad) {
                return redirect('/datos-alumno')->with('error', 'La sesión ha expirado o los datos están incompletos.');
            }

            $alumno = Alumno::create([
                'email_institucional' => $datosAlumno['email_institucional'],
                'apellido_paterno'    => $datosAlumno['apellido_paterno'],
                'apellido_materno'    => $datosAlumno['apellido_materno'],
                'nombre'              => $datosAlumno['nombre'],
                'edad'                => $datosAlumno['edad'],
                'sexo'                => $datosAlumno['sexo'],
                'telefono'            => $datosAlumno['telefono'],
                'calle'               => $datosAlumno['calle'],
                'cp'                  => $datosAlumno['cp'],
                'colonia'             => $datosAlumno['colonia'],
                'localidad'           => $datosAlumno['localidad'],
                'municipio'           => $datosAlumno['municipio'],
                'ciudad'              => $datosAlumno['ciudad'],
                'estado'              => $datosAlumno['estado'],
            ]);

            Escolaridad::create([
                'alumno_id'       => $alumno->id,
                'modalidad'       => $datosEscolaridad['modalidad'],
                'carrera'         => $datosEscolaridad['carrera'],
                'carrera_otro'    => $datosEscolaridad['carrera_otro'] ?? null,
                'semestre_actual' => $datosEscolaridad['semestre_actual'],
                'grupo'           => $datosEscolaridad['grupo'],
                'matricula'       => $datosEscolaridad['matricula'],
            ]);

            $datosPrograma['alumno_id'] = $alumno->id;
            Programa::create($datosPrograma);

            session()->forget(['datos_alumno', 'datos_escolaridad']);

            try {
                return view('final', ['nombre' => $alumno->nombre]);
            } catch (\Throwable $e) {
                return redirect('/')->with('error', 'Tu formulario fue enviado, pero ocurrió un error al mostrar la confirmación.');
            }
        } catch (\Throwable $e) {
            return redirect('/programa')->with('error', 'Ocurrió un error al guardar el formulario. Inténtalo de nuevo.');
        }
    }
}
