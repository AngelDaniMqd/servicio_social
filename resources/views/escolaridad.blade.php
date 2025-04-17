@php
    $modalidades = DB::table('modalidad')->select('id','nombre')->get();
    $carreras = DB::table('carreras')->select('id','nombre')->get();
    $semestres = DB::table('semestres')->select('id','nombre')->get();
    $grupos = DB::table('grupos')->select('id','letra')->get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolaridad del Alumno</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f0f0f0; font-family: 'Montserrat', sans-serif; margin:0; padding:2rem; display:flex; justify-content:center; }
        .card { background:#fff; max-width:800px; width:100%; padding:40px; border-radius:15px; box-shadow:0 12px 30px rgba(0,0,0,0.15); }
        h2 { text-align:center; margin-bottom:20px; }
        label { display:block; font-weight:600; margin-top:15px; }
        input[type="number"], select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
        .buttons { margin-top:30px; display:flex; justify-content:space-between; }
        .btn { background-color:#8B0000; color:white; padding:12px 25px; border:none; border-radius:5px; font-weight:bold; text-decoration:none; }
        .btn:hover { background-color:#a00000; }
        .required::after { content:" *"; color:red; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Escolaridad del Alumno</h2>
        @if(session('error'))
            <div class="alerta">{{ session('error') }}<br>Intenta nuevamente.</div>
        @endif
        <form action="{{ url('/guardar-escolaridad') }}" method="POST" novalidate>
            @csrf
            <label for="matricula" class="required">Número de Control</label>
            <input type="number" name="matricula" id="matricula" required pattern="[0-9]{14}" maxlength="14" title="14 dígitos">
            
            <label for="meses_servicio" class="required">Meses de Servicio</label>
            <input type="number" name="meses_servicio" id="meses_servicio" required min="1">
            
            <label for="modalidad_id" class="required">Modalidad</label>
            <select name="modalidad_id" id="modalidad_id" required>
                <option value="" disabled selected>Selecciona una modalidad</option>
                @foreach($modalidades as $modalidad)
                    <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
                @endforeach
            </select>
            
            <label for="carreras_id" class="required">Carrera</label>
            <select name="carreras_id" id="carreras_id" required>
                <option value="" disabled selected>Selecciona una carrera</option>
                @foreach($carreras as $carrera)
                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                @endforeach
            </select>
            
            <label for="semestres_id" class="required">Semestre</label>
            <select name="semestres_id" id="semestres_id" required>
                <option value="" disabled selected>Selecciona un semestre</option>
                @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                @endforeach
            </select>
            
            <label for="grupos_id" class="required">Grupo</label>
            <select name="grupos_id" id="grupos_id" required>
                <option value="" disabled selected>Selecciona un grupo</option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->letra }}</option>
                @endforeach
            </select>
            
            <div class="buttons">
                <a href="{{ url('/datos-alumno') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Siguiente</button>
            </div>
        </form>
    </div>
</body>
</html>
