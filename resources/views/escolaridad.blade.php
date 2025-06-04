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
        .form-control {
            width: 100%;
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
            background: #fff;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: border-color 0.2s;
            margin-top: 5px;
        }
        select.form-control {
            padding-right: 35px;
        }
        .buttons { margin-top:30px; display:flex; justify-content:space-between; }
        .btn { background-color:#8B0000; color:white; padding:12px 25px; border:none; border-radius:5px; font-weight:bold; text-decoration:none; }
        .btn:hover { background-color:#a00000; }
        .required::after { content:" *"; color:red; }
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
        <h2>Escolaridad del Alumno</h2>
        @if(session('error'))
            <div class="alerta">{{ session('error') }}<br>Intenta nuevamente.</div>
        @endif
        <form id="formEscolaridad" action="{{ url('/guardar-escolaridad') }}" method="POST" novalidate>
            @csrf
            <label for="matricula" class="required">Número de Control</label>
            <input type="text" name="matricula" id="matricula" required pattern="[0-9]{14}" maxlength="14" minlength="14" title="14 dígitos" class="form-control">
            <span id="matriculaError" class="error-message">El número de control debe tener exactamente 14 dígitos.</span>
            
            <label for="meses_servicio" class="required">Meses de Servicio</label>
            <input type="number" name="meses_servicio" id="meses_servicio" required min="1" class="form-control">
            
            <label for="modalidad_id" class="required">Modalidad</label>
            <select name="modalidad_id" id="modalidad_id" required class="form-control">
                <option value="" disabled selected>Selecciona una modalidad</option>
                @foreach($modalidades as $modalidad)
                    <option value="{{ $modalidad->id }}">{{ $modalidad->nombre }}</option>
                @endforeach
            </select>
            
            <label for="carreras_id" class="required">Carrera</label>
            <select name="carreras_id" id="carreras_id" required class="form-control">
                <option value="" disabled selected>Selecciona una carrera</option>
                @foreach($carreras as $carrera)
                    <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                @endforeach
            </select>
            
            <label for="semestres_id" class="required">Semestre</label>
            <select name="semestres_id" id="semestres_id" required class="form-control">
                <option value="" disabled selected>Selecciona un semestre</option>
                @foreach($semestres as $semestre)
                    <option value="{{ $semestre->id }}">{{ $semestre->nombre }}</option>
                @endforeach
            </select>
            
            <label for="grupos_id" class="required">Grupo</label>
            <select name="grupos_id" id="grupos_id" required class="form-control">
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

            // Solo permitir números en matrícula y limitar a 14 dígitos
            const matriculaInput = document.getElementById('matricula');
            const matriculaError = document.getElementById('matriculaError');
            matriculaInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0,14);
                if (this.value.length !== 14) {
                    matriculaError.style.display = 'block';
                } else {
                    matriculaError.style.display = 'none';
                }
            });

            // Validación de formulario
            document.getElementById('formEscolaridad').addEventListener('submit', function(e) {
                let isValid = true;
                let onlyMatriculaError = false;

                // Validar campos requeridos
                const requiredFields = this.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    // Para selects
                    if (field.tagName === "SELECT" && (field.value === "" || field.value === null)) {
                        isValid = false;
                    }
                    // Para inputs
                    if (field.tagName === "INPUT" && (field.value.trim() === "")) {
                        isValid = false;
                    }
                    // Validación especial para matrícula (14 dígitos)
                    if (field.id === "matricula" && !/^[0-9]{14}$/.test(field.value.trim())) {
                        isValid = false;
                        onlyMatriculaError = true;
                    }
                    // Validación especial para meses de servicio (mayor a 0)
                    if (field.id === "meses_servicio" && (parseInt(field.value) < 1 || isNaN(parseInt(field.value)))) {
                        isValid = false;
                    }
                });

                // Mostrar solo el error de matrícula si es el único problema
                if (onlyMatriculaError) {
                    e.preventDefault();
                    matriculaError.style.display = 'block';
                    matriculaInput.focus();
                    return;
                } else {
                    matriculaError.style.display = 'none';
                }

                if (!isValid) {
                    e.preventDefault();
                    mostrarModalError();
                }
            });
        });
    </script>
</body>
</html>