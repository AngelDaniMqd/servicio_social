<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Verificar si Laravel Excel está disponible
$excelAvailable = class_exists('\Maatwebsite\Excel\Concerns\FromCollection');

if ($excelAvailable) {
    // Solo definir las interfaces si Laravel Excel está disponible
    interface_exists('\Maatwebsite\Excel\Concerns\FromCollection') or eval('
        interface FromCollection {}
        interface WithHeadings {}
        interface WithMapping {}
        interface WithStyles {}
        interface WithColumnWidths {}
    ');
}

class AlumnosExport
{
    protected $data;
    protected $table;
    protected $filters;
    
    public function __construct($data, $table, $filters = [])
    {
        $this->data = $data;
        $this->table = $table;
        $this->filters = $filters;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        if (empty($this->data) || !is_countable($this->data) || count($this->data) === 0) {
            return ['Sin datos'];
        }

        $firstRow = is_array($this->data) ? $this->data[0] : $this->data->first();
        if (!$firstRow) {
            return ['Sin datos'];
        }
        
        $columns = array_keys((array)$firstRow);
        
        return array_map(function($column) {
            return $this->getColumnDisplayName($column);
        }, $columns);
    }

    public function map($row): array
    {
        $columns = array_keys((array)$row);
        $mappedRow = [];
        
        foreach ($columns as $column) {
            $mappedRow[] = $this->formatCellValue($column, $row->$column ?? '', $row);
        }
        
        return $mappedRow;
    }

    public function styles($sheet)
    {
        // Solo aplicar estilos si PhpSpreadsheet está disponible
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Style\Fill')) {
            return [];
        }
        
        $sheet->getStyle('1:1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        if (!empty($this->data) && is_countable($this->data) && count($this->data) > 0) {
            $lastRow = count($this->data) + 1;
            $firstRow = is_array($this->data) ? $this->data[0] : $this->data->first();
            $lastColumn = count(array_keys((array)$firstRow));
            
            if (class_exists('\PhpOffice\PhpSpreadsheet\Cell\Coordinate')) {
                $range = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumn) . $lastRow;
                
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            }
        }

        return [];
    }

    public function columnWidths(): array
    {
        if (empty($this->data) || !is_countable($this->data) || count($this->data) === 0) {
            return ['A' => 15];
        }

        $firstRow = is_array($this->data) ? $this->data[0] : $this->data->first();
        $columns = array_keys((array)$firstRow);
        $widths = [];
        
        foreach ($columns as $index => $column) {
            if (class_exists('\PhpOffice\PhpSpreadsheet\Cell\Coordinate')) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
            } else {
                // Fallback simple para generar letras de columna
                $columnLetter = chr(65 + ($index % 26));
            }
            $widths[$columnLetter] = $this->getColumnWidth($column);
        }
        
        return $widths;
    }

    private function getColumnWidth($column)
    {
        $widths = [
            'id' => 8,
            'nombre' => 15,
            'apellido_p' => 15,
            'apellido_m' => 15,
            'correo_institucional' => 25,
            'telefono' => 12,
            'fecha_registro' => 12,
            'edad_id' => 8,
            'sexo_id' => 10,
            'rol_id' => 10,
            'status_id' => 10,
            'localidad' => 15,
            'cp' => 8,
            'municipio' => 15,
            'estado' => 15,
            'numero_control' => 15,
            'meses_servicio' => 8,
            'carrera' => 20,
            'semestre' => 12,
            'grupo' => 8,
            'modalidad' => 12,
            'nombre_programa' => 25,
            'encargado_nombre' => 20,
            'puesto_encargado' => 15,
            'telefono_institucion' => 12,
            'fecha_inicio' => 12,
            'fecha_final' => 12,
            'institucion' => 25,
            'titulo' => 8,
            'metodo' => 12,
            'tipo_programa' => 15,
            'status_programa' => 15,
        ];
        
        return $widths[$column] ?? 12;
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

    private function formatCellValue($column, $value, $row)
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
                    return Carbon::parse($value)->format('d/m/Y');
                }
                if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                    return Carbon::parse($value)->format('d/m/Y H:i');
                }
            } catch (\Exception $e) {
                return $value;
            }
        }
        
        // Truncar textos muy largos para Excel
        if (in_array($column, ['nombre_programa', 'encargado_nombre', 'puesto_encargado', 'institucion']) && strlen($value) > 50) {
            return substr($value, 0, 47) . '...';
        }
        
        return $value;
    }
}