<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte - {{ $tableName }}</title>
    <style>
        @page { margin: 1cm; size: A4 landscape; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 0; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563EB; padding-bottom: 10px; }
        .header h1 { color: #2563EB; margin: 0 0 5px 0; font-size: 18px; }
        .info-section { margin-bottom: 15px; font-size: 9px; }
        .info-box { background: #f8f9fa; padding: 8px; margin: 5px 0; border-left: 3px solid #2563EB; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px; /* Reducir más el tamaño para que quepa todo */
        }
        
        th {
            background: #2563EB;
            color: white;
            padding: 4px 2px; /* Reducir padding */
            text-align: center;
            font-weight: bold;
            border: 1px solid #1d4ed8;
            font-size: 7px;
            word-wrap: break-word;
            max-width: 60px; /* Limitar ancho máximo */
        }
        
        td {
            padding: 3px 2px; /* Reducir padding */
            border: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
            font-size: 6px;
            word-wrap: break-word;
            max-width: 60px; /* Limitar ancho máximo */
        }
        
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #666; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de {{ $tableName }}</h1>
        <p>Sistema de Servicio Social - Exportación de Datos</p>
    </div>

    <div class="info-section">
        <div class="info-box"><strong>Total de registros:</strong> {{ $totalRecords }}</div>
        <div class="info-box"><strong>Fecha de exportación:</strong> {{ $exportDate }}</div>
        <div class="info-box"><strong>Tabla:</strong> {{ $tableName }}</div>
    </div>

    @if($data->isNotEmpty())
        <table>
            <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($columns as $column)
                            <td>
                                @php
                                    $value = $row->$column ?? '';
                                    
                                    // Convertir IDs a nombres legibles
                                    switch ($column) {
                                        case 'edad_id':
                                            $edadNames = [1 => '15', 2 => '15', 3 => '16', 4 => '17', 5 => '18', 6 => '19', 7 => '20+'];
                                            $value = $edadNames[$value] ?? $value;
                                            break;
                                            
                                        case 'sexo_id':
                                            $sexoNames = [1 => 'Masculino', 2 => 'Femenino'];
                                            $value = $sexoNames[$value] ?? $value;
                                            break;
                                            
                                        case 'rol_id':
                                            $rolNames = [1 => 'Alumno', 2 => 'Administrador'];
                                            $value = $rolNames[$value] ?? $value;
                                            break;
                                            
                                        case 'status_id':
                                            $statusNames = [1 => 'Activo', 2 => 'Inactivo', 3 => 'Suspendido'];
                                            $value = $statusNames[$value] ?? $value;
                                            break;
                                    }
                                    
                                    // Formatear fechas
                                    if (in_array($column, ['fecha_registro', 'fecha_inicio', 'fecha_final'])) {
                                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                                            $value = date('d/m/Y', strtotime($value));
                                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                                            $value = date('d/m/Y H:i', strtotime($value));
                                        }
                                    }
                                    
                                    // Truncar textos largos para PDF
                                    if (strlen($value) > 20) {
                                        $value = substr($value, 0, 17) . '...';
                                    }
                                @endphp
                                
                                {{ $value ?: 'Sin información' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; padding: 40px;">No se encontraron registros para exportar.</p>
    @endif

    <div class="footer">
        <p>Sistema de Servicio Social - Generado el {{ $exportDate }}</p>
    </div>
</body>
</html>