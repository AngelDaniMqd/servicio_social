<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolaridad del Estudiante</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

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
            margin-bottom: 20px;
            font-size: 22px;
        }

        form label {
            font-weight: 600;
            display: block;
            margin: 15px 0 5px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
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
            margin-top: 25px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #a00000;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .required::after {
            content: " *";
            color: red;
        }

        @media (max-width: 768px) {
            .card {
                padding: 25px 20px;
            }

            .buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Escolaridad del Estudiante</h2>
        <form action="{{ url('/guardar-escolaridad') }}" method="POST">
            @csrf

            <label for="modalidad" class="required">Modalidad de estudios</label>
            <select name="modalidad" id="modalidad" required>
                <option value="" disabled selected>Selecciona una modalidad</option>
                <option value="Escolarizado">Escolarizado</option>
                <option value="Auto-planeado">Auto-planeado</option>
                <option value="Formación dual (no habilitado)">Formación dual (no habilitado)</option>
            </select>

            <label for="carrera" class="required">Carrera</label>
            <select name="carrera" id="carrera" required onchange="mostrarOtraCarrera(this)">
                <option value="" disabled selected>Selecciona una carrera</option>
                <option value="Técnico Agropecuario">Técnico Agropecuario</option>
                <option value="Técnico en Ofimática">Técnico en Ofimática</option>
                <option value="Otro">Otro</option>
            </select>

            <div id="otraCarreraInput" style="display:none; margin-top:10px;">
                <input type="text" name="carrera_otro" placeholder="Especificar otra carrera">
            </div>

            <label for="semestre_actual" class="required">Semestre que estás cursando</label>
            <select name="semestre_actual" id="semestre_actual" required>
                <option value="" disabled selected>Selecciona tu semestre</option>
                @for ($i = 3; $i <= 6; $i++)
                    <option value="{{ $i }}">{{ $i }}° semestre</option>
                @endfor
            </select>

            <label for="grupo" class="required">Grupo</label>
            <select name="grupo" id="grupo" required>
                <option value="" disabled selected>Selecciona tu grupo</option>
                @foreach(['A','B','C','D','E','F','G','H','I'] as $grupo)
                    <option value="{{ $grupo }}">{{ $grupo }}</option>
                @endforeach
            </select>

            <label for="matricula" class="required">Número de control (14 dígitos)</label>
            <input type="text" name="matricula" id="matricula" required pattern="[0-9]{14}" maxlength="14" title="Debe contener exactamente 14 dígitos numéricos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <div class="buttons">
                <a href="{{ url('/datos-alumno') }}" class="btn">Atrás</a>
                <button type="submit" class="btn">Siguiente</button>
            </div>
        </form>
    </div>

    <script>
        function mostrarOtraCarrera(select) {
            const otra = document.getElementById('otraCarreraInput');
            otra.style.display = (select.value === 'Otro') ? 'block' : 'none';
        }
    </script>
</body>
</html>
