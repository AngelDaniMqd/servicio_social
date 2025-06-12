<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedTable = $request->query('table');
        $rows = null;
        $filterOptions = [];

        if ($selectedTable) {
            $query = DB::table($selectedTable);
            
            // Aplicar filtros según la tabla
            if ($selectedTable === 'alumno') {
                $this->applyAlumnoFilters($query, $request);
                $filterOptions = $this->getFilterOptions();
            }
            
            // Aplicar paginación con 50 registros por página
            $rows = $query->paginate(50)->appends($request->query());
        }

        return view('database-overview', compact('selectedTable', 'rows', 'filterOptions'));
    }

    private function applyAlumnoFilters($query, $request)
    {
        // Filtros de información personal
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('apellidos')) {
            $query->where(function($q) use ($request) {
                $q->where('apellido_p', 'like', '%' . $request->apellidos . '%')
                  ->orWhere('apellido_m', 'like', '%' . $request->apellidos . '%');
            });
        }

        if ($request->filled('telefono')) {
            $query->where('telefono', 'like', '%' . $request->telefono . '%');
        }

        if ($request->filled('correo')) {
            $query->where('correo_institucional', 'like', '%' . $request->correo . '%');
        }

        // Filtros demográficos
        if ($request->filled('edad_id')) {
            $query->where('edad_id', $request->edad_id);
        }

        if ($request->filled('sexo_id')) {
            $query->where('sexo_id', $request->sexo_id);
        }

        if ($request->filled('rol_id')) {
            $query->where('rol_id', $request->rol_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // Filtros de ubicación
        if ($request->filled('cp')) {
            $query->where('cp', 'like', '%' . $request->cp . '%');
        }

        // Filtros de fechas
        if ($request->filled('fecha_registro_desde')) {
            $query->whereDate('fecha_registro', '>=', $request->fecha_registro_desde);
        }

        if ($request->filled('fecha_registro_hasta')) {
            $query->whereDate('fecha_registro', '<=', $request->fecha_registro_hasta);
        }

        // Variables para controlar los joins
        $hasEscolaridadJoin = false;
        $hasProgramaJoin = false;

        // Nuevos filtros académicos
        if ($request->filled('carrera_nombre')) {
            if (!$hasEscolaridadJoin) {
                $query->join('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id');
                $hasEscolaridadJoin = true;
            }
            $query->join('carreras', 'escolaridad_alumno.carreras_id', '=', 'carreras.id')
                  ->where('carreras.nombre', $request->carrera_nombre);
        }

        if ($request->filled('semestre_nombre')) {
            if (!$hasEscolaridadJoin) {
                $query->join('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id');
                $hasEscolaridadJoin = true;
            }
            $query->join('semestres', 'escolaridad_alumno.semestres_id', '=', 'semestres.id')
                  ->where('semestres.nombre', $request->semestre_nombre);
        }

        if ($request->filled('grupo_letra')) {
            if (!$hasEscolaridadJoin) {
                $query->join('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id');
                $hasEscolaridadJoin = true;
            }
            $query->join('grupos', 'escolaridad_alumno.grupos_id', '=', 'grupos.id')
                  ->where('grupos.letra', $request->grupo_letra);
        }

        if ($request->filled('modalidad_nombre')) {
            if (!$hasEscolaridadJoin) {
                $query->join('escolaridad_alumno', 'alumno.id', '=', 'escolaridad_alumno.alumno_id');
                $hasEscolaridadJoin = true;
            }
            $query->join('modalidad', 'escolaridad_alumno.modalidad_id', '=', 'modalidad.id')
                  ->where('modalidad.nombre', $request->modalidad_nombre);
        }

        // Nuevos filtros de servicio social
        if ($request->filled('institucion_nombre')) {
            if (!$hasProgramaJoin) {
                $query->join('programa_servicio_social', 'alumno.id', '=', 'programa_servicio_social.alumno_id');
                $hasProgramaJoin = true;
            }
            $query->join('instituciones', 'programa_servicio_social.instituciones_id', '=', 'instituciones.id')
                  ->where('instituciones.nombre', $request->institucion_nombre);
        }

        if ($request->filled('titulo_nombre')) {
            if (!$hasProgramaJoin) {
                $query->join('programa_servicio_social', 'alumno.id', '=', 'programa_servicio_social.alumno_id');
                $hasProgramaJoin = true;
            }
            $query->join('titulos', 'programa_servicio_social.titulos_id', '=', 'titulos.id')
                  ->where('titulos.titulo', $request->titulo_nombre);
        }

        if ($request->filled('metodo_nombre')) {
            if (!$hasProgramaJoin) {
                $query->join('programa_servicio_social', 'alumno.id', '=', 'programa_servicio_social.alumno_id');
                $hasProgramaJoin = true;
            }
            $query->join('metodo_servicio', 'programa_servicio_social.metodo_servicio_id', '=', 'metodo_servicio.id')
                  ->where('metodo_servicio.metodo', $request->metodo_nombre);
        }

        if ($request->filled('tipo_programa_nombre')) {
            if (!$hasProgramaJoin) {
                $query->join('programa_servicio_social', 'alumno.id', '=', 'programa_servicio_social.alumno_id');
                $hasProgramaJoin = true;
            }
            $query->join('tipos_programa', 'programa_servicio_social.tipos_programa_id', '=', 'tipos_programa.id')
                  ->where('tipos_programa.tipo', $request->tipo_programa_nombre);
        }

        // Seleccionar solo campos de alumno y evitar duplicados
        $query->select('alumno.*')->distinct();
    }

    private function getColumnName($table, $preferredColumns)
    {
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        
        foreach ($preferredColumns as $column) {
            if (in_array($column, $columns)) {
                return $column;
            }
        }
        
        return 'id'; // fallback
    }

    private function getFilterOptions()
    {
        try {
            return [
                'edades' => DB::table('edad')->select('id', 'edades as nombre')->orderBy('edades')->get(),
                'sexos' => DB::table('sexo')->select('id', 'tipo as nombre')->orderBy('tipo')->get(),
                'roles' => DB::table('rol')->select('id', 'tipo as nombre')->orderBy('tipo')->get(),
                'status' => DB::table('status')->select('id', 'tipo as nombre')->orderBy('tipo')->get(),
                'carreras' => DB::table('carreras')->select('id', 'nombre')->orderBy('nombre')->get(),
                'semestres' => DB::table('semestres')->select('id', 'nombre')->orderBy('nombre')->get(),
                'grupos' => DB::table('grupos')->select('id', 'letra as nombre')->orderBy('letra')->get(),
                'modalidades' => DB::table('modalidad')->select('id', 'nombre')->orderBy('nombre')->get(),
                'instituciones' => DB::table('instituciones')->select('id', 'nombre')->orderBy('nombre')->get(),
                'titulos' => DB::table('titulos')->select('id', 'titulo', 'titulo as nombre')->orderBy('titulo')->get(),
                'metodos' => DB::table('metodo_servicio')->select('id', 'metodo', 'metodo as nombre')->orderBy('metodo')->get(),
                'tipos_programa' => DB::table('tipos_programa')->select('id', 'tipo', 'tipo as nombre')->orderBy('tipo')->get(),
            ];
        } catch (\Exception $e) {
            return [
                'edades' => collect([]),
                'sexos' => collect([]),
                'roles' => collect([]),
                'status' => collect([]),
                'carreras' => collect([]),
                'semestres' => collect([]),
                'grupos' => collect([]),
                'modalidades' => collect([]),
                'instituciones' => collect([]),
                'titulos' => collect([]),
                'metodos' => collect([]),
                'tipos_programa' => collect([]),
            ];
        }
    }
}