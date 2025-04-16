<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos del Programa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            background-color: #f0f0f0;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .alerta {
            background-color: #ffe0e0;
            border: 1px solid #cc0000;
            color: #a00000;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 20px;
        }

        input[type="text"],
        input[type="date"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
            font-family: 'Montserrat', sans-serif;
        }

        .btn {
            background-color: #8B0000;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            min-width: 100px;
        }

        .btn:hover {
            background-color: #a00000;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .required::after {
            content: " *";
            color: red;
        }

        @media (max-width: 768px) {
            .buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        function toggleOtraInstitucion() {
            const select = document.getElementById('dependencia');
            const otra = document.getElementById('otraInstitucionContainer');
            const input = document.getElementById('otra_institucion');
            if (select.value === 'Otro') {
                otra.style.display = 'block';
                input.setAttribute('required', 'required');
            } else {
                otra.style.display = 'none';
                input.removeAttribute('required');
                input.value = '';
            }
        }

        function toggleOtroTitulo(select) {
            const otro = document.getElementById('otroTituloInput');
            const input = document.getElementById('otro_titulo');
            if (select.value === 'Otro') {
                otro.style.display = 'block';
                input.setAttribute('required', 'required');
            } else {
                otro.style.display = 'none';
                input.removeAttribute('required');
                input.value = '';
            }
        }

        function toggleOtroTipo(select) {
            const otro = document.getElementById('otroTipoInput');
            const input = document.getElementById('tipo_otro');
            if (select.value === 'Otro') {
                otro.style.display = 'block';
                input.setAttribute('required', 'required');
            } else {
                otro.style.display = 'none';
                input.removeAttribute('required');
                input.value = '';
            }
        }

        function capitalizarNombre(input) {
            input.value = input.value.replace(/\b\w/g, l => l.toUpperCase());
        }

        function validarFecha(input) {
            const valor = input.value;
            const year = parseInt(valor.split("-")[0]);
            if (isNaN(year) || year.toString().length !== 4) {
                input.setCustomValidity("El año debe tener exactamente 4 dígitos.");
                return false;
            } else if (year < 2025) {
                input.setCustomValidity("Solo se permiten fechas desde el año 2025 en adelante.");
                return false;
            } else {
                input.setCustomValidity("");
                return true;
            }
        }

        function compararFechas(inicio, fin) {
            const fechaInicio = new Date(inicio.value);
            const fechaFin = new Date(fin.value);
            if (fechaFin < fechaInicio) {
                fin.setCustomValidity("La fecha de término no puede ser anterior a la de inicio.");
                return false;
            } else {
                fin.setCustomValidity("");
                return true;
            }
        }

        function validarProgramaForm() {
            const dependencia = document.getElementById("dependencia").value;
            const otraInstitucion = document.getElementById("otra_institucion").value.trim();

            const tituloEncargado = document.getElementById("titulo_encargado").value;
            const otroTitulo = document.getElementById("otro_titulo").value.trim();

            const tipoPrograma = document.getElementById("tipo_programa").value;
            const tipoOtro = document.getElementById("tipo_otro").value.trim();

            if (dependencia === "Otro" && otraInstitucion === "") {
                alert("Por favor especifica la otra institución.");
                return false;
            }

            if (tituloEncargado === "Otro" && otroTitulo === "") {
                alert("Por favor especifica el otro título del encargado.");
                return false;
            }

            if (tipoPrograma === "Otro" && tipoOtro === "") {
                alert("Por favor especifica el otro tipo de programa.");
                return false;
            }

            return true;
        }

        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector("form");
            const inicio = document.getElementById("inicio");
            const fin = document.getElementById("fin");

            inicio.addEventListener("blur", () => validarFecha(inicio));
            fin.addEventListener("blur", () => {
                validarFecha(fin);
                compararFechas(inicio, fin);
            });

            form.addEventListener("submit", function (e) {
                const validInicio = validarFecha(inicio);
                const validFin = validarFecha(fin);
                const fechasOk = compararFechas(inicio, fin);
                const validForm = validarProgramaForm();

                if (!validInicio || !validFin || !fechasOk || !this.checkValidity() || !validForm) {
                    e.preventDefault();
                    this.reportValidity();
                }
            });
        });
    </script>
</head>
<body>
    <div class="card">
        <h2>Datos del Programa</h2>

        @if(session('error'))
            <div class="alerta">
                {{ session('error') }}<br>
                Por favor, vuelve a intentarlo.
            </div>
        @endif

        <form action="{{ url('/finalizar-formulario') }}" method="POST">
            @csrf

            <label for="dependencia" class="required">Nombre de la dependencia u organización</label>
            <select name="dependencia" id="dependencia" onchange="toggleOtraInstitucion()" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="CBTA 256">CBTA 256</option>
                <option value="Otro">Otro</option>
            </select>

            <div id="otraInstitucionContainer" style="display:none;">
                <label for="otra_institucion">Si lo realizas en otra institución, indica cuál</label>
                <input type="text" name="otra_institucion" id="otra_institucion" maxlength="100">
            </div>

            <label for="programa" class="required">Nombre del programa</label>
            <input type="text" name="programa" id="programa" required>

            <label for="encargado" class="required">Nombre del encargado(a)</label>
            <input type="text" name="encargado" id="encargado" required oninput="capitalizarNombre(this)">

            <label for="titulo_encargado" class="required">Título del encargado</label>
            <select name="titulo_encargado" id="titulo_encargado" onchange="toggleOtroTitulo(this)" required>
                <option value="" disabled selected>Selecciona un título</option>
                @foreach(['Lic.', 'Arq.', 'Ing.', 'Profr.', 'Profa.', 'Dr.', 'Dra.', 'C.P.T.', 'Dir.', 'Pte.', 'Abg.', 'Mtro.', 'Mtra.', 'Delegado', 'Otro'] as $titulo)
                    <option value="{{ $titulo }}">{{ $titulo }}</option>
                @endforeach
            </select>
            <div id="otroTituloInput" style="display:none; margin-top:10px;">
                <input type="text" name="otro_titulo" id="otro_titulo" placeholder="Especificar si seleccionaste 'Otro'">
            </div>

            <label for="puesto_encargado" class="required">Puesto del encargado</label>
            <input type="text" name="puesto_encargado" id="puesto_encargado" required>

            <label for="telefono_institucion" class="required">Número de contacto de la institución (10 dígitos)</label>
            <input type="tel" name="telefono_institucion" id="telefono_institucion"
                   required pattern="[0-9]{10}" maxlength="10" minlength="10"
                   title="Debe contener exactamente 10 dígitos numéricos"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <label for="metodo" class="required">Método de realización del servicio social</label>
            <select name="metodo" id="metodo" required>
                <option value="" disabled selected>Selecciona un método</option>
                <option value="Individual">Individual</option>
                <option value="Grupal">Grupal</option>
                <option value="Brigada">Brigada</option>
            </select>

            <label for="inicio" class="required">Fecha de inicio del servicio social</label>
            <input type="date" name="inicio" id="inicio" required>

            <label for="fin" class="required">Fecha de término del servicio social</label>
            <input type="date" name="fin" id="fin" required>

            <label for="tipo_programa" class="required">Tipo de programa</label>
            <select name="tipo_programa" id="tipo_programa" onchange="toggleOtroTipo(this)" required>
                <option value="" disabled selected>Selecciona un tipo</option>
                @foreach(['Educativo', 'Social', 'Comunitario', 'Alimentario', 'Ecológico', 'Salud', 'Otro'] as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
            </select>
            <div id="otroTipoInput" style="display:none; margin-top:10px;">
                <input type="text" name="tipo_otro" id="tipo_otro" placeholder="Especificar si seleccionaste 'Otro'">
            </div>

            <div class="buttons">
                <a href="{{ url('/escolaridad') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Finalizar</button>
            </div>
        </form>
    </div>
</body>
</html>
