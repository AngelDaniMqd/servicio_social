@php
    $estados = DB::table('estados')->select('id','nombre')->get();
    $municipios = DB::table('municipios')->select('id','nombre','estado_id')->get();
    $edades = DB::table('edad')->select('id','edades')->get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Alumno</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos básicos */
        body { background-color: #f0f0f0; font-family: 'Montserrat', sans-serif; margin: 0; padding: 2rem; display: flex; justify-content: center; align-items: center; }
        .card { background: #fff; width: 100%; max-width: 800px; padding: 40px; border-radius: 15px; box-shadow: 0 12px 30px rgba(0,0,0,0.15); }
        h2 { text-align: center; margin-bottom: 30px; font-size: 22px; }
        form label { font-weight: 600; margin-top: 15px; display: block; }
        input[type="text"], input[type="tel"], input[type="number"], select { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc; }
        .buttons { margin-top: 30px; display: flex; justify-content: space-between; }
        .btn { background-color: #8B0000; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; text-decoration: none; }
        .btn:hover { background-color: #a00000; }
        .required::after { content: " *"; color: red; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Datos del Alumno</h2>
        @if(session('error'))
            <div class="alerta">{{ session('error') }}<br>Intenta nuevamente.</div>
        @endif
        <form id="formAlumno" action="{{ url('/guardar-datos-alumno') }}" method="POST" novalidate>
            @csrf
            <label for="correo" class="required">Correo institucional</label>
            <input type="text" name="correo_institucional" id="correo" required pattern="^[a-zA-Z0-9._%+-]+@cbta256\.edu\.mx$" title="Debe terminar en @cbta256.edu.mx" oninput="validarCorreo(this)">
            
            <label for="apellido_paterno" class="required">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" required maxlength="45" oninput="capitalizar(this)">
            
            <label for="apellido_materno" class="required">Apellido Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" required maxlength="45" oninput="capitalizar(this)">
            
            <label for="nombre" class="required">Nombre(s)</label>
            <input type="text" name="nombre" id="nombre" required maxlength="45" oninput="capitalizar(this)">
            
            <label for="telefono" class="required">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" required maxlength="10" minlength="10" pattern="[0-9]{10}" title="10 dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            
            <label for="cp" class="required">Código Postal</label>
            <input type="text" name="cp" id="cp" required maxlength="5" minlength="5" pattern="[0-9]{5}" title="5 dígitos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            
            <label for="edad" class="required">Edad</label>
            <select name="edad" id="edad" required>
                <option value="" disabled selected>Selecciona tu edad</option>
                @foreach($edades as $edad)
                    <option value="{{ $edad->id }}">{{ $edad->edades }}</option>
                @endforeach
            </select>
            
            <label for="sexo" class="required">Sexo</label>
            <select name="sexo" id="sexo" required>
                <option value="" disabled selected>Selecciona tu sexo</option>
                <option value="1">Femenino</option>
                <option value="2">Masculino</option>
            </select>
            
            <label for="localidad" class="required">Localidad</label>
            <input type="text" name="localidad" id="localidad" required oninput="capitalizar(this)">
            
            <label for="estado" class="required">Estado</label>
            <select name="estado" id="estado" required>
                <option value="" disabled selected>Selecciona un estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                @endforeach
            </select>
            
            <label for="municipio" class="required" id="municipio-label" style="display:none;">Municipio</label>
            <select name="municipio" id="municipio" required style="display:none;">
                <option value="" disabled selected>Selecciona un municipio</option>
            </select>
            
            <div class="buttons">
                <a href="{{ url('/solicitud') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Siguiente</button>
            </div>
        </form>
    </div>
    <script>
        function capitalizar(input) {
            input.value = input.value.toLowerCase().replace(/(^|\s)\S/g, letra=> letra.toUpperCase());
        }
        function validarCorreo(input) {
            const regex = /^[a-zA-Z0-9._%+-]+@cbta256\.edu\.mx$/;
            input.setCustomValidity(regex.test(input.value) ? "" : "El correo debe terminar en @cbta256.edu.mx");
        }
        document.getElementById('formAlumno').addEventListener('submit', function(e){
            if(!this.checkValidity()){
                e.preventDefault();
                this.reportValidity();
            }
        });
        // Cargar municipios dinámicamente según el estado
        const municipiosData = @json($municipios);
        const estadoSelect = document.getElementById('estado');
        const municipioSelect = document.getElementById('municipio');
        const municipioLabel = document.getElementById('municipio-label');
        estadoSelect.addEventListener('change', function(){
            const selectedEstadoId = this.value;
            municipioSelect.innerHTML = '<option value="" disabled selected>Selecciona un municipio</option>';
            const filtrados = municipiosData.filter(m => m.estado_id == selectedEstadoId);
            if(filtrados.length > 0){
                municipioSelect.style.display = 'block';
                municipioLabel.style.display = 'block';
                filtrados.forEach(m=>{
                    const opt = document.createElement('option');
                    opt.value = m.nombre;
                    opt.textContent = m.nombre;
                    municipioSelect.append(opt);
                });
            } else {
                municipioSelect.style.display = 'none';
                municipioLabel.style.display = 'none';
            }
        });
    </script>
</body>
</html>
