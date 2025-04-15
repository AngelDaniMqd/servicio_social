<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Escolaridad;
use App\Models\Programa;

class FormularioController extends Controller
{
    public function vistaDatosAlumno() {
        return view('datosalumno');
    }

    public function guardarDatosAlumno(Request $request) {
        $request->session()->put('datos_alumno', $request->all());
        return redirect('/escolaridad');
    }

    public function vistaEscolaridad() {
        return view('escolaridad');
    }

    public function guardarEscolaridad(Request $request) {
        $request->session()->put('datos_escolaridad', $request->all());
        return redirect('/programa');
    }

    public function vistaPrograma() {
        return view('programa');
    }

    public function guardarTodo(Request $request) {
        $datosAlumno = session('datos_alumno');
        $datosEscolaridad = session('datos_escolaridad');
        $datosPrograma = $request->all();
    
        // Guardar Alumno
        $alumno = Alumno::create([
            'email_institucional' => $datosAlumno['email_institucional'],
            'apellido_paterno' => $datosAlumno['apellido_paterno'],
            'apellido_materno' => $datosAlumno['apellido_materno'],
            'nombre' => $datosAlumno['nombre'],
            'edad' => $datosAlumno['edad'],
            'sexo' => $datosAlumno['sexo'],
            'telefono' => $datosAlumno['telefono'],
            'calle' => $datosAlumno['calle'],
            'cp' => $datosAlumno['cp'],
            'colonia' => $datosAlumno['colonia'],
            'localidad' => $datosAlumno['localidad'],
            'municipio' => $datosAlumno['municipio'],
            'ciudad' => $datosAlumno['ciudad'],
            'estado' => $datosAlumno['estado'],
        ]);
    
        // Guardar Escolaridad (filtrando campos explícitos)
        Escolaridad::create([
            'alumno_id' => $alumno->id,
            'modalidad' => $datosEscolaridad['modalidad'],
            'carrera' => $datosEscolaridad['carrera'],
            'carrera_otro' => $datosEscolaridad['carrera_otro'] ?? null,
            'semestre_actual' => $datosEscolaridad['semestre_actual'],
            'grupo' => $datosEscolaridad['grupo'],
            'matricula' => $datosEscolaridad['matricula'],
        ]);
    
        // Guardar Programa
        $datosPrograma['alumno_id'] = $alumno->id;
        Programa::create($datosPrograma);
    
        // Limpiar sesión
        session()->forget(['datos_alumno', 'datos_escolaridad']);
    
        return view('final');
    }
    
    
}
