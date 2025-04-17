@php
    $instituciones = DB::table('instituciones')->select('id','nombre')->get();
    $titulos = DB::table('titulos')->select('id','titulo')->get();
    $metodos = DB::table('metodo_servicio')->select('id','metodo')->get();
    $tipos = DB::table('tipos_programa')->select('id','tipo')->get();
    $status = DB::table('status')->select('id','tipo')->get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa de Servicio Social</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f0f0f0; font-family: 'Montserrat', sans-serif; margin:0; padding:2rem; display:flex; justify-content:center; }
        .card { background: #fff; width:100%; max-width:800px; padding:40px; border-radius:15px; box-shadow:0 12px 30px rgba(0,0,0,0.15); }
        h2 { text-align:center; margin-bottom:20px; }
        label { display:block; font-weight:600; margin-top:15px; }
        input[type="text"], input[type="date"], input[type="number"], select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
        .buttons { margin-top:30px; display:flex; justify-content:space-between; }
        .btn { background-color:#8B0000; color:white; padding:12px 25px; border:none; border-radius:5px; font-weight:bold; text-decoration:none; text-align:center; }
        .btn:hover { background-color:#a00000; }
        .required::after { content:" *"; color:red; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Programa de Servicio Social</h2>
        <form action="{{ url('/finalizar-formulario') }}" method="POST" novalidate>
            @csrf
            <label for="instituciones_id" class="required">Institución</label>
            <select name="instituciones_id" id="instituciones_id" required>
                <option value="" disabled selected>Selecciona una institución</option>
                @foreach($instituciones as $inst)
                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                @endforeach
            </select>
            
            <label for="otra_institucion">Otra Institución (si no se encuentra en la lista)</label>
            <input type="text" name="otra_institucion" id="otra_institucion">
            
            <label for="nombre_programa" class="required">Nombre del Programa</label>
            <input type="text" name="nombre_programa" id="nombre_programa" required>
            
            <label for="encargado_nombre" class="required">Nombre del Encargado</label>
            <input type="text" name="encargado_nombre" id="encargado_nombre" required>
            
            <label for="titulos_id" class="required">Título</label>
            <select name="titulos_id" id="titulos_id" required>
                <option value="" disabled selected>Selecciona un título</option>
                @foreach($titulos as $titulo)
                    <option value="{{ $titulo->id }}">{{ $titulo->titulo }}</option>
                @endforeach
            </select>
            
            <label for="puesto_encargado" class="required">Puesto del Encargado</label>
            <input type="text" name="puesto_encargado" id="puesto_encargado" required>
            
            <label for="metodo_servicio_id" class="required">Método de Servicio</label>
            <select name="metodo_servicio_id" id="metodo_servicio_id" required>
                <option value="" disabled selected>Selecciona un método</option>
                @foreach($metodos as $met)
                    <option value="{{ $met->id }}">{{ $met->metodo }}</option>
                @endforeach
            </select>
            
            <label for="telefono_institucion" class="required">Teléfono de Institución</label>
            <input type="number" name="telefono_institucion" id="telefono_institucion" required>
            
            <label for="fecha_inicio" class="required">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" required>
            
            <label for="fecha_final" class="required">Fecha Final</label>
            <input type="date" name="fecha_final" id="fecha_final" required>
            
            <label for="tipos_programa_id" class="required">Tipo de Programa</label>
            <select name="tipos_programa_id" id="tipos_programa_id" required>
                <option value="" disabled selected>Selecciona un tipo</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                @endforeach
            </select>
            
            <label for="otro_programa">Otro Programa (si aplica)</label>
            <input type="text" name="otro_programa" id="otro_programa">
            
           
            
            <div class="buttons">
                <a href="{{ url('/escolaridad') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Finalizar</button>
            </div>
        </form>
    </div>
</body>
</html>
