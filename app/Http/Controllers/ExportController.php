<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportController extends Controller
{
    public function exportExcel(Request $request)
    {
        try {
            $table = $request->get('table', 'alumno');
            $filters = $request->except(['table', '_token']);
            
            $data = $this->getFilteredData($table, $filters);
            
            // Verificar si Laravel Excel está disponible
            if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {
                try {
                    return \Maatwebsite\Excel\Facades\Excel::download(
                        new \App\Exports\AlumnosExport($data, $table, $filters),
                        "alumnos_export_" . date('Y-m-d_H-i-s') . ".xlsx"
                    );
                } catch (\Exception $e) {
                    // Si falla Laravel Excel, usar fallback
                    return $this->exportExcelHtml($data, $table, $filters);
                }
            } else {
                // Fallback a exportación HTML que se puede abrir en Excel
                return $this->exportExcelHtml($data, $table, $filters);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al exportar: ' . $e->getMessage());
        }
    }
    
    private function exportExcelHtml($data, $table, $filters)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Exportación de Alumnos</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #2563EB; color: white; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Exportación de ' . ucfirst($table) . '</h2>
    <p>Total de registros: ' . (is_countable($data) ? count($data) : 0) . '</p>
    <p>Fecha de exportación: ' . date('d/m/Y H:i:s') . '</p>
    
    <table>
        <thead>
            <tr>';
        
        if (!empty($data) && is_countable($data) && count($data) > 0) {
            $firstRow = is_array($data) ? $data[0] : $data->first();
            if ($firstRow) {
                $columns = array_keys((array)$firstRow);
                
                foreach ($columns as $column) {
                    $html .= '<th>' . $this->getColumnDisplayName($column) . '</th>';
                }
            }
        }
        
        $html .= '</tr></thead><tbody>';
        
        if (!empty($data)) {
            foreach ($data as $row) {
                $html .= '<tr>';
                foreach ((array)$row as $column => $value) {
                    $html .= '<td>' . $this->formatCellValueForHtml($column, $value) . '</td>';
                }
                $html .= '</tr>';
            }
        }
        
        $html .= '</tbody></table></body></html>';
        
        $filename = "alumnos_export_" . date('Y-m-d_H-i-s') . ".xls";
        
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    private function getColumnDisplayName($column)
    {
        $displayNames = [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido_p' => 'Apellido Paterno',
            'apellido_m' => 'Apellido Materno',
            'correo_institucional' => 'Correo Institucional',
            'telefono' => 'Teléfono',
            'fecha_registro' => 'Fecha Registro',
            'edad_id' => 'Edad',
            'sexo_id' => 'Sexo',
            'rol_id' => 'Rol',
            'status_id' => 'Estado',
            'localidad' => 'Localidad',
            'cp' => 'Código Postal',
            'municipio' => 'Municipio',
            'estado' => 'Estado',
            'numero_control' => 'Número de Control',
            'meses_servicio' => 'Meses de Servicio',
            'carrera' => 'Carrera',
            'semestre' => 'Semestre',
            'grupo' => 'Grupo',
            'modalidad' => 'Modalidad',
            'nombre_programa' => 'Nombre del Programa',
            'encargado_nombre' => 'Encargado',
            'puesto_encargado' => 'Puesto del Encargado',
            'telefono_institucion' => 'Teléfono Institución',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'institucion' => 'Institución',
            'titulo' => 'Título',
            'metodo' => 'Método',
            'tipo_programa' => 'Tipo de Programa',
            'status_programa' => 'Estado del Programa'
        ];
        
        return $displayNames[$column] ?? ucfirst(str_replace('_', ' ', $column));
    }
    
    private function formatCellValueForHtml($column, $value)
    {
        if (is_null($value) || $value === '') {
            return 'Sin información';
        }
        
        // Convertir IDs a nombres legibles
        switch ($column) {
            case 'edad_id':
                $edadNames = [1 => '15', 2 => '15', 3 => '16', 4 => '17', 5 => '18', 6 => '19', 7 => '20+'];
                return $edadNames[$value] ?? $value;
                
            case 'sexo_id':
                $sexoNames = [1 => 'Masculino', 2 => 'Femenino'];
                return $sexoNames[$value] ?? $value;
                
            case 'rol_id':
                $rolNames = [1 => 'Alumno', 2 => 'Administrador'];
                return $rolNames[$value] ?? $value;
                
            case 'status_id':
                $statusNames = [1 => 'Activo', 2 => 'Inactivo', 3 => 'Suspendido'];
                return $statusNames[$value] ?? $value;
        }
        
        // Formatear fechas
        if (in_array($column, ['fecha_registro', 'fecha_inicio', 'fecha_final'])) {
            try {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    return date('d/m/Y', strtotime($value));
                }
                if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                    return date('d/m/Y H:i', strtotime($value));
                }
            } catch (\Exception $e) {
                return $value;
            }
        }
        
        return htmlspecialchars($value);
    }
    
    public function exportPdf(Request $request)
    {
        $table = $request->get('table', 'alumno');
        $filters = $request->except(['table', '_token']);
        
        // Obtener datos filtrados
        $data = $this->getFilteredData($table, $filters);
        
        // Preparar datos para la vista
        $viewData = [
            'data' => $data,
            'table' => $table,
            'tableName' => ucfirst(str_replace('_', ' ', $table)),
            'filters' => $filters,
            'totalRecords' => is_countable($data) ? count($data) : 0,
            'exportDate' => Carbon::now()->format('d/m/Y H:i:s'),
            'columns' => (!empty($data) && is_countable($data) && count($data) > 0) ? array_keys((array)$data[0]) : []
        ];
        
        $pdf = Pdf::loadView('exports.pdf-table', $viewData)
                  ->setPaper('a4', 'landscape')
                  ->setOptions([
                      'defaultFont' => 'sans-serif',
                      'isRemoteEnabled' => true,
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true
                  ]);
        
        $filename = $table . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    private function getFilteredData($table, $filters)
    {
        if ($table === 'alumno') {
            // Usar solo las tablas que existen en tu base de datos
            $query = DB::table('alumno')
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
                ->leftJoin('status', 'programa_servicio_social.status_id', '=', 'status.id')
                ->select(
                    'alumno.id',
                    'alumno.nombre',
                    'alumno.apellido_p',
                    'alumno.apellido_m',
                    'alumno.correo_institucional',
                    'alumno.telefono',
                    'alumno.fecha_registro',
                    // Usar directamente los IDs ya que no existen las tablas de referencia
                    'alumno.edad_id',
                    'alumno.sexo_id',
                    'alumno.rol_id',
                    'alumno.status_id',
                    'ubicaciones.localidad', // Usar localidad directamente de ubicaciones
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
                    'tipos_programa.tipo as tipo_programa',
                    'status.tipo as status_programa'
                );
            
            // Aplicar filtros
            $this->applyAlumnoFilters($query, $filters);
            
            return $query->get();
        } else {
            // Para otras tablas usar consulta simple
            $query = DB::table($table);
            $this->applyGenericFilters($query, $filters, $table);
            return $query->get();
        }
    }
    
    private function applyAlumnoFilters($query, $filters)
    {
        // Filtros de información personal
        if (!empty($filters['nombre'])) {
            $query->where('alumno.nombre', 'like', '%' . $filters['nombre'] . '%');
        }

        if (!empty($filters['apellidos'])) {
            $query->where(function($q) use ($filters) {
                $q->where('alumno.apellido_p', 'like', '%' . $filters['apellidos'] . '%')
                  ->orWhere('alumno.apellido_m', 'like', '%' . $filters['apellidos'] . '%');
            });
        }

        if (!empty($filters['telefono'])) {
            $query->where('alumno.telefono', 'like', '%' . $filters['telefono'] . '%');
        }

        if (!empty($filters['correo'])) {
            $query->where('alumno.correo_institucional', 'like', '%' . $filters['correo'] . '%');
        }

        if (!empty($filters['numero_control'])) {
            $query->where('escolaridad_alumno.numero_control', 'like', '%' . $filters['numero_control'] . '%');
        }

        if (!empty($filters['meses_servicio'])) {
            $query->where('escolaridad_alumno.meses_servicio', $filters['meses_servicio']);
        }

        if (!empty($filters['nombre_programa'])) {
            $query->where('programa_servicio_social.nombre_programa', 'like', '%' . $filters['nombre_programa'] . '%');
        }

        if (!empty($filters['encargado_nombre'])) {
            $query->where('programa_servicio_social.encargado_nombre', 'like', '%' . $filters['encargado_nombre'] . '%');
        }

        // Filtros usando IDs directamente
        if (!empty($filters['edad_id'])) {
            $query->where('alumno.edad_id', $filters['edad_id']);
        }

        if (!empty($filters['sexo_id'])) {
            $query->where('alumno.sexo_id', $filters['sexo_id']);
        }

        if (!empty($filters['rol_id'])) {
            $query->where('alumno.rol_id', $filters['rol_id']);
        }

        if (!empty($filters['status_id'])) {
            $query->where('alumno.status_id', $filters['status_id']);
        }

        // Filtro de código postal
        if (!empty($filters['cp'])) {
            $query->where('ubicaciones.cp', 'like', '%' . $filters['cp'] . '%');
        }

        // Filtros de fechas
        if (!empty($filters['fecha_registro_desde'])) {
            $query->whereDate('alumno.fecha_registro', '>=', $filters['fecha_registro_desde']);
        }

        if (!empty($filters['fecha_registro_hasta'])) {
            $query->whereDate('alumno.fecha_registro', '<=', $filters['fecha_registro_hasta']);
        }

        // Filtros académicos
        if (!empty($filters['carrera_nombre'])) {
            $query->where('carreras.nombre', $filters['carrera_nombre']);
        }

        if (!empty($filters['semestre_nombre'])) {
            $query->where('semestres.nombre', $filters['semestre_nombre']);
        }

        if (!empty($filters['grupo_letra'])) {
            $query->where('grupos.letra', $filters['grupo_letra']);
        }

        if (!empty($filters['modalidad_nombre'])) {
            $query->where('modalidad.nombre', $filters['modalidad_nombre']);
        }

        // Filtros de servicio social
        if (!empty($filters['institucion_nombre'])) {
            $query->where('instituciones.nombre', 'like', '%' . $filters['institucion_nombre'] . '%');
        }

        if (!empty($filters['titulo_nombre'])) {
            $query->where('titulos.titulo', 'like', '%' . $filters['titulo_nombre'] . '%');
        }

        if (!empty($filters['metodo_nombre'])) {
            $query->where('metodo_servicio.metodo', 'like', '%' . $filters['metodo_nombre'] . '%');
        }

        if (!empty($filters['tipo_programa_nombre'])) {
            $query->where('tipos_programa.tipo', 'like', '%' . $filters['tipo_programa_nombre'] . '%');
        }

        if (!empty($filters['status_programa_nombre'])) {
            $query->where('status.tipo', 'like', '%' . $filters['status_programa_nombre'] . '%');
        }
    }
    
    private function applyGenericFilters($query, $filters, $table)
    {
        // Aplicar filtros genéricos para otras tablas si es necesario
        foreach ($filters as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $query->where($key, 'like', '%' . $value . '%');
            }
        }
    }
}