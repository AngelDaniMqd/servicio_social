<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;

class AlumnosController extends Controller
{
    public function recientes()
    {
        $alumnos = Alumno::with(['sexo', 'status', 'edad', 'rol'])
                         ->orderBy('fecha_registro', 'desc')
                         ->limit(50)
                         ->get();
                         
        return view('alumnos_descargar', compact('alumnos'));
    }
}