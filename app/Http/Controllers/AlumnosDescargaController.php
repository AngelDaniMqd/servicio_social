<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnosDescargaController extends Controller
{
    public function index()
    {
        try {
            // Obtener alumnos recientes (últimos 30 días) con datos básicos - SOLO ACTIVOS
            $alumnos = DB::table('alumno')
                ->select([
                    'alumno.id',
                    'alumno.nombre',
                    'alumno.apellido_p',
                    'alumno.apellido_m',
                    'alumno.correo_institucional',
                    'alumno.fecha_registro'
                ])
                ->where('alumno.fecha_registro', '>=', now()->subDays(30))
                ->where('alumno.status_id', 1) // AGREGADO: Solo alumnos activos
                ->orderBy('alumno.fecha_registro', 'desc')
                ->get();

            return view('alumnos_descargar', compact('alumnos'));
        } catch (\Exception $e) {
            \Log::error('Error en AlumnosDescargaController: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar los alumnos: ' . $e->getMessage());
        }
    }
}