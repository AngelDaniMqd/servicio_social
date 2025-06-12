
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Alumnos - Servicio Social CBTa 256</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            color: #1e40af;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 8px;
        }
        th {
            background-color: #1e40af;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .status-activo { color: #059669; font-weight: bold; }
        .status-inactivo { color: #dc2626; font-weight: bold; }
        .status-en-proceso { color: #d97706; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CBTa 256 - Sistema de Servicio Social</h1>
        <p>Reporte de Alumnos Registrados</p>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
        <p>Total de registros: {{ count($alumnos) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Control</th>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th>Carrera</th>
                <th>Sem.</th>
                <th>Grupo</th>
                <th>Institución</th>
                <th>Programa</th>
                <th>Status</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Ubicación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $alumno)
            <tr>
                <td>{{ $alumno->escolaridad_numero_control }}</td>
                <td>{{ $alumno->nombre }} {{ $alumno->apellido_p }} {{ $alumno->apellido_m }}</td>
                <td>{{ $alumno->telefono }}</td>
                <td>{{ $alumno->carrera_nombre }}</td>
                <td>{{ $alumno->semestre_nombre }}</td>
                <td>{{ $alumno->grupo_letra }}</td>
                <td>{{ $alumno->institucion_nombre }}</td>
                <td>{{ $alumno->programa_nombre_programa }}</td>
                <td class="status-{{ strtolower(str_replace(' ', '-', $alumno->status_tipo)) }}">
                    {{ $alumno->status_tipo }}
                </td>
                <td>{{ $alumno->programa_fecha_inicio ? date('d/m/Y', strtotime($alumno->programa_fecha_inicio)) : '-' }}</td>
                <td>{{ $alumno->programa_fecha_final ? date('d/m/Y', strtotime($alumno->programa_fecha_final)) : '-' }}</td>
                <td>{{ $alumno->municipio_nombre }}, {{ $alumno->estado_nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>CBTa 256 - Centro de Bachillerato Tecnológico agropecuario No. 256 | Página {PAGE_NUM} de {PAGE_COUNT}</p>
    </div>
</body>
</html>