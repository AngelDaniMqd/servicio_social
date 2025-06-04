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
        body { background-color: #f0f0f0; font-family: 'Montserrat', sans-serif; margin: 0; padding: 2rem; display: flex; justify-content: center; align-items: center; }
        .card { background: #fff; width: 100%; max-width: 800px; padding: 40px; border-radius: 15px; box-shadow: 0 12px 30px rgba(0,0,0,0.15); }
        h2 { text-align: center; margin-bottom: 30px; font-size: 22px; }
        form label { font-weight: 600; margin-top: 15px; display: block; }
        /* Unificar tamaño de inputs y selects */
        .form-control {
            width: 100%;
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
            font-size: 1rem;
            background: #fff;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: border-color 0.2s;
        }
        select.form-control {
            padding-right: 35px; /* espacio para la flecha */
        }
        .buttons { margin-top: 30px; display: flex; justify-content: space-between; }
        .btn { background-color: #8B0000; color: white; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; text-decoration: none; }
        .btn:hover { background-color: #a00000; }
        .required::after { content: " *"; color: red; }
        .error-message {
            color: #d9534f;
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
        }
        input:invalid, select:invalid {
            border-color: #d9534f;
        }
        input:valid, select:valid {
            border-color: #5cb85c;
        }
        .alerta {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        /* Modal styles */
        .modal-error {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.35);
            justify-content: center;
            align-items: center;
        }
        .modal-error.active {
            display: flex;
        }
        .modal-content {
            background: #fff;
            padding: 35px 30px 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            text-align: center;
            max-width: 350px;
            width: 90%;
            position: relative;
            font-family: 'Montserrat', sans-serif;
        }
        .modal-content h3 {
            margin-top: 0;
            color: #8B0000;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .modal-content p {
            color: #333;
            font-size: 1rem;
            margin-bottom: 0;
        }
        .close-modal {
            position: absolute;
            right: 18px;
            top: 12px;
            font-size: 1.5rem;
            color: #8B0000;
            cursor: pointer;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            .modal-content { padding: 20px 10px; }
        }
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
            <input type="text" name="correo_institucional" id="correo" required 
                   pattern="^[a-zA-Z0-9._%+-]+@cbta256\.edu\.mx$" 
                   title="Debe terminar en @cbta256.edu.mx" class="form-control">
            <div class="error-message" id="correo-error"></div>
            
            <label for="apellido_paterno" class="required">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" required 
                   maxlength="45" title="Formato correcto: De La Torre" class="form-control">
            <div class="error-message" id="apellido_paterno-error"></div>
            
            <label for="apellido_materno" class="required">Apellido Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" required 
                   maxlength="45" title="Formato correcto: González Pérez" class="form-control">
            <div class="error-message" id="apellido_materno-error"></div>
            
            <label for="nombre" class="required">Nombre(s)</label>
            <input type="text" name="nombre" id="nombre" required 
                   maxlength="45" title="Formato correcto: José Armando" class="form-control">
            <div class="error-message" id="nombre-error"></div>
            
            <label for="telefono" class="required">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" required 
                   maxlength="10" minlength="10" pattern="[0-9]{10}" 
                   title="10 dígitos numéricos" class="form-control">
            <div class="error-message" id="telefono-error"></div>
            
            <label for="cp" class="required">Código Postal</label>
            <input type="text" name="cp" id="cp" required 
                   maxlength="5" minlength="5" pattern="[0-9]{5}" 
                   title="5 dígitos numéricos" class="form-control">
            <div class="error-message" id="cp-error"></div>
            
            <label for="edad" class="required">Edad</label>
            <select name="edad" id="edad" required class="form-control">
                <option value="" disabled selected>Selecciona tu edad</option>
                @foreach($edades as $edad)
                    <option value="{{ $edad->id }}">{{ $edad->edades }}</option>
                @endforeach
            </select>
            <div class="error-message" id="edad-error"></div>
            
            <label for="sexo" class="required">Sexo</label>
            <select name="sexo" id="sexo" required class="form-control">
                <option value="" disabled selected>Selecciona tu sexo</option>
                <option value="1">Femenino</option>
                <option value="2">Masculino</option>
            </select>
            <div class="error-message" id="sexo-error"></div>
            
            <label for="localidad" class="required">Localidad</label>
            <input type="text" name="localidad" id="localidad" required 
                   title="Formato correcto: San Pedro Tlaquepaque" class="form-control">
            <div class="error-message" id="localidad-error"></div>
            
            <label for="estado" class="required">Estado</label>
            <select name="estado" id="estado" required class="form-control">
                <option value="" disabled selected>Selecciona un estado</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                @endforeach
            </select>
            <div class="error-message" id="estado-error"></div>
            
            <label for="municipio" class="required" id="municipio-label" style="display:none;">Municipio</label>
            <select name="municipio" id="municipio" required style="display:none;" class="form-control">
                <option value="" disabled selected>Selecciona un municipio</option>
            </select>
            <div class="error-message" id="municipio-error"></div>
            
            <div class="buttons">
                <a href="{{ url('/solicitud') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Siguiente</button>
            </div>
        </form>
    </div>

    <!-- MODAL DE ERROR -->
    <div id="modalError" class="modal-error">
        <div class="modal-content">
            <span id="cerrarModal" class="close-modal">&times;</span>
            <h3>Error de validación</h3>
            <p>Por favor complete todos los campos requeridos correctamente.</p>
            <button id="btnCerrarModal" class="btn" style="width:100%;margin-top:20px;">Cerrar</button>
        </div>
    </div>

    <script>
        // Datos de municipios
        const municipiosData = @json($municipios);
        
        // Función mejorada para capitalizar nombres y apellidos con espacios
        function capitalizarNombres(input) {
            // Guardar posición del cursor
            const start = input.selectionStart;
            const end = input.selectionEnd;
            
            // Capitalizar cada palabra
            let value = input.value.toLowerCase()
                .replace(/(^|\s)\S/g, function(letra) {
                    return letra.toUpperCase();
                });
            
            // Restaurar posición del cursor
            input.value = value;
            input.setSelectionRange(start, end);
            
            return value;
        }
        
        // Validación de nombres y apellidos
        function validarNombreApellido(input) {
            const value = capitalizarNombres(input);
            const regex = /^[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+( [A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+)*$/;
            
            if (value.trim() === "") {
                mostrarError(input, "Este campo es obligatorio");
                return false;
            } else if (!regex.test(value)) {
                mostrarError(input, "Formato incorrecto. Ejemplo: José Armando");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Función para mostrar errores
        function mostrarError(campo, mensaje) {
            const errorElement = document.getElementById(`${campo.id}-error`);
            if (mensaje) {
                errorElement.textContent = mensaje;
                errorElement.style.display = 'block';
                campo.classList.add('invalid');
                campo.classList.remove('valid');
            } else {
                errorElement.style.display = 'none';
                campo.classList.remove('invalid');
                campo.classList.add('valid');
            }
        }
        
        // Validación de correo institucional
        function validarCorreo(input) {
            const regex = /^[a-zA-Z0-9._%+-]+@cbta256\.edu\.mx$/;
            if (!regex.test(input.value)) {
                mostrarError(input, "El correo debe terminar en @cbta256.edu.mx");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Validación de teléfono
        function validarTelefono(input) {
            const regex = /^[0-9]{10}$/;
            if (!regex.test(input.value)) {
                mostrarError(input, "Debe ingresar exactamente 10 dígitos");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Validación de código postal
        function validarCP(input) {
            const regex = /^[0-9]{5}$/;
            if (!regex.test(input.value)) {
                mostrarError(input, "El código postal debe tener 5 dígitos");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Validación para selects
        function validarSelect(select) {
            if (select.required && select.value === "") {
                mostrarError(select, "Este campo es requerido");
                return false;
            } else {
                mostrarError(select, '');
                return true;
            }
        }
        
        // Cargar municipios según el estado seleccionado
        function cargarMunicipios() {
            const estadoSelect = document.getElementById('estado');
            const municipioSelect = document.getElementById('municipio');
            const municipioLabel = document.getElementById('municipio-label');
            const selectedEstadoId = estadoSelect.value;
            
            municipioSelect.innerHTML = '<option value="" disabled selected>Selecciona un municipio</option>';
            
            const filtrados = municipiosData.filter(m => m.estado_id == selectedEstadoId);
            
            if (filtrados.length > 0) {
                municipioSelect.style.display = 'block';
                municipioLabel.style.display = 'block';
                
                filtrados.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = m.nombre;
                    municipioSelect.appendChild(opt);
                });
            } else {
                municipioSelect.style.display = 'none';
                municipioLabel.style.display = 'none';
            }
            
            validarSelect(municipioSelect);
        }

        // MODAL DE ERROR
        function mostrarModalError() {
            document.getElementById('modalError').classList.add('active');
        }
        function ocultarModalError() {
            document.getElementById('modalError').classList.remove('active');
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('cerrarModal').onclick = ocultarModalError;
            document.getElementById('btnCerrarModal').onclick = ocultarModalError;
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") ocultarModalError();
            });

            // Asignar eventos a los campos de nombres y apellidos
            const camposNombres = ['apellido_paterno', 'apellido_materno', 'nombre', 'localidad'];
            camposNombres.forEach(id => {
                const campo = document.getElementById(id);
                
                // Capitalizar al escribir
                campo.addEventListener('input', function() {
                    capitalizarNombres(this);
                });
                
                // Validar al salir del campo
                campo.addEventListener('blur', function() {
                    validarNombreApellido(this);
                });
                
                // Validar también al cambiar (para selects)
                campo.addEventListener('change', function() {
                    validarNombreApellido(this);
                });
            });
            
            // Validación de correo
            const correo = document.getElementById('correo');
            correo.addEventListener('input', function() {
                validarCorreo(this);
            });
            correo.addEventListener('blur', function() {
                validarCorreo(this);
            });
            
            // Validación de teléfono
            const telefono = document.getElementById('telefono');
            telefono.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                validarTelefono(this);
            });
            telefono.addEventListener('blur', function() {
                validarTelefono(this);
            });
            
            // Validación de código postal
            const cp = document.getElementById('cp');
            cp.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                validarCP(this);
            });
            cp.addEventListener('blur', function() {
                validarCP(this);
            });
            
            // Validación de selects
            const selects = ['edad', 'sexo', 'estado', 'municipio'];
            selects.forEach(id => {
                const select = document.getElementById(id);
                if (select) {
                    select.addEventListener('change', function() {
                        validarSelect(this);
                    });
                }
            });
            
            // Cargar municipios cuando cambie el estado
            const estadoSelect = document.getElementById('estado');
            estadoSelect.addEventListener('change', cargarMunicipios);
            
            // Validar formulario antes de enviar
            document.getElementById('formAlumno').addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validar todos los campos requeridos
                const requiredFields = this.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (field.id === 'correo' && !validarCorreo(field)) isValid = false;
                    else if ((field.id === 'telefono') && !validarTelefono(field)) isValid = false;
                    else if ((field.id === 'cp') && !validarCP(field)) isValid = false;
                    else if (camposNombres.includes(field.id) && !validarNombreApellido(field)) isValid = false;
                    else if (selects.includes(field.id) && !validarSelect(field)) isValid = false;
                });
                
                if (!isValid) {
                    e.preventDefault();
                    mostrarModalError();
                }
            });
            
            // Inicializar validación de municipios si ya hay un estado seleccionado
            if (estadoSelect.value) {
                cargarMunicipios();
            }
        });
    </script>
</body>
</html>