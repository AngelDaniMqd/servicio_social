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
        .btn { background-color:#8B0000; color:white; padding:12px 25px; border:none; border-radius:5px; font-weight:bold; text-decoration:none; text-align:center; }
        .btn:hover { background-color:#a00000; }
        .required::after { content:" *"; color:red; }
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
        <h2>Programa de Servicio Social</h2>
        <form id="formPrograma" action="{{ url('/finalizar-formulario') }}" method="POST" novalidate>
            @csrf
            <label for="instituciones_id" class="required">Institución</label>
            <select name="instituciones_id" id="instituciones_id" required class="form-control">
                <option value="" disabled selected>Selecciona una institución</option>
                @foreach($instituciones as $inst)
                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                @endforeach
                <option value="otra">Otra</option>
            </select>
            
            <div id="otra_institucion_div" style="display:none;">
                <label for="otra_institucion" class="required">Otra Institución</label>
                <input type="text" name="otra_institucion" id="otra_institucion" class="form-control">
            </div>
            
            <label for="nombre_programa" class="required">Nombre del Programa</label>
            <input type="text" name="nombre_programa" id="nombre_programa" required class="form-control">
            
            <label for="encargado_nombre" class="required">Nombre del Encargado</label>
            <input type="text" name="encargado_nombre" id="encargado_nombre" required class="form-control">
            
            <label for="titulos_id" class="required">Título del encargado</label>
            <select name="titulos_id" id="titulos_id" required class="form-control">
                <option value="" disabled selected>Selecciona un título</option>
                @foreach($titulos as $titulo)
                    <option value="{{ $titulo->id }}">{{ $titulo->titulo }}</option>
                @endforeach
            </select>
            
            <label for="puesto_encargado" class="required">Puesto del Encargado</label>
            <input type="text" name="puesto_encargado" id="puesto_encargado" required class="form-control">
            
            <label for="metodo_servicio_id" class="required">Método de Servicio</label>
            <select name="metodo_servicio_id" id="metodo_servicio_id" required class="form-control">
                <option value="" disabled selected>Selecciona un método</option>
                @foreach($metodos as $met)
                    <option value="{{ $met->id }}">{{ $met->metodo }}</option>
                @endforeach
            </select>
            
            <label for="telefono_institucion" class="required">Teléfono de Institución</label>
            <input type="text" name="telefono_institucion" id="telefono_institucion" required class="form-control" maxlength="10" minlength="10" pattern="[0-9]{10}" title="10 dígitos numéricos">
            
            <label for="fecha_inicio" class="required">Fecha de Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" required class="form-control" max="9999-12-31">
            
            <label for="fecha_final" class="required">Fecha Final</label>
            <input type="date" name="fecha_final" id="fecha_final" required class="form-control" max="9999-12-31">
            
            <label for="tipos_programa_id" class="required">Tipo de Programa</label>
            <select name="tipos_programa_id" id="tipos_programa_id" required class="form-control">
                <option value="" disabled selected>Selecciona un tipo</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                @endforeach
                <option value="0">Otro</option>
            </select>
            
            <div id="otro_programa_div" style="display:none;">
                <label for="otro_programa" class="required">Otro Programa</label>
                <input type="text" name="otro_programa" id="otro_programa" class="form-control">
            </div>
            
            <div class="buttons">
                <a href="{{ url('/escolaridad') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Finalizar</button>
            </div>
        </form>
    </div>

    <!-- MODAL DE ERROR -->
    <div id="modalError" class="modal-error">
        <div class="modal-content">
            <span id="cerrarModal" class="close-modal">&times;</span>
            <h3>Error de validación</h3>
            <p id="modalErrorMsg">Por favor complete todos los campos requeridos correctamente.</p>
            <button id="btnCerrarModal" class="btn" style="width:100%;margin-top:20px;">Cerrar</button>
        </div>
    </div>

    <script>
        // Modal de error
        function mostrarModalError(msg) {
            document.getElementById('modalErrorMsg').textContent = msg || "Por favor complete todos los campos requeridos correctamente.";
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

            // Mostrar/ocultar campo "Otra Institución"
            const instSelect = document.getElementById('instituciones_id');
            const otraInstDiv = document.getElementById('otra_institucion_div');
            instSelect.addEventListener('change', function() {
                if (this.value === 'otra') {
                    otraInstDiv.style.display = 'block';
                    document.getElementById('otra_institucion').setAttribute('required', 'required');
                } else {
                    otraInstDiv.style.display = 'none';
                    document.getElementById('otra_institucion').removeAttribute('required');
                }
            });

            // Mostrar/ocultar campo "Otro Programa"
            const tipoSelect = document.getElementById('tipos_programa_id');
            const otroProgDiv = document.getElementById('otro_programa_div');
            tipoSelect.addEventListener('change', function() {
                if (this.value === '0') {
                    otroProgDiv.style.display = 'block';
                    document.getElementById('otro_programa').setAttribute('required', 'required');
                } else {
                    otroProgDiv.style.display = 'none';
                    document.getElementById('otro_programa').removeAttribute('required');
                }
            });

            // Validación de teléfono de institución
            const telInput = document.getElementById('telefono_institucion');
            telInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);
            });

            // Capitalizar automáticamente el nombre del encargado
            const encargadoInput = document.getElementById('encargado_nombre');
            encargadoInput.addEventListener('input', function() {
                this.value = this.value
                    .toLowerCase()
                    .replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
            });

            // Validación del formulario
            document.getElementById('formPrograma').addEventListener('submit', function(e) {
                let isValid = true;
                let msg = "Por favor complete todos los campos requeridos correctamente.";

                // Validar selects requeridos
                const selects = this.querySelectorAll('select[required]');
                selects.forEach(sel => {
                    if (!sel.value || sel.value === "") isValid = false;
                });

                // Validar inputs requeridos
                const inputs = this.querySelectorAll('input[required]');
                inputs.forEach(inp => {
                    if (inp.offsetParent !== null && inp.value.trim() === "") isValid = false;
                });

                // Validar teléfono exactamente 10 dígitos
                if (isValid && !/^[0-9]{10}$/.test(telInput.value.trim())) {
                    isValid = false;
                    msg = "El teléfono de institución debe tener exactamente 10 dígitos.";
                }

                // Validar "Otra Institución" si está visible
                if (isValid && instSelect.value === 'otra') {
                    const otraInst = document.getElementById('otra_institucion');
                    if (!otraInst.value.trim()) {
                        isValid = false;
                        msg = "Por favor ingresa el nombre de la otra institución.";
                    }
                }

                // Validar "Otro Programa" si está visible
                if (isValid && tipoSelect.value === '0') {
                    const otroProg = document.getElementById('otro_programa');
                    if (!otroProg.value.trim()) {
                        isValid = false;
                        msg = "Por favor ingresa el nombre del otro programa.";
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    mostrarModalError(msg);
                }
            });
        });
    </script>
</body>
</html>