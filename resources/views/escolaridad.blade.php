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

        .radio-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .radio-option input[type="text"] {
            width: 150px;
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

            .radio-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Escolaridad del Estudiante</h2>
        <form action="#" method="POST">

            <label class="required">Modalidad de estudios</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="modalidad" value="Escolarizado" required> Escolarizado</label>
                <label class="radio-option"><input type="radio" name="modalidad" value="Auto-planeado"> Auto-planeado</label>
                <label class="radio-option"><input type="radio" name="modalidad" value="Formación dual (no habilitado)"> Formación dual (no habilitado)</label>
            </div>

            <label class="required">Carrera</label>
            <div class="radio-group">
                <label class="radio-option"><input type="radio" name="carrera" value="Técnico Agropecuario" required> Técnico Agropecuario</label>
                <label class="radio-option"><input type="radio" name="carrera" value="Técnico en Ofimática"> Técnico en Ofimática</label>
                <div class="radio-option">
                    <input type="radio" name="carrera" value="Otro"> Otro:
                    <input type="text" name="carrera_otro" placeholder="Especificar">
                </div>
            </div>

            <label for="semestre" class="required">Semestre que estás cursando</label>
            <select name="semestre" id="semestre" required>
                <option value="" disabled selected>Selecciona tu semestre</option>
                @for ($i = 3; $i <= 6; $i++)
                    <option value="{{ $i }}">{{ $i }}° semestre</option>
                @endfor
            </select>

            <label class="required">Grupo</label>
            <div class="radio-group">
                @foreach(['A','B','C','D','E','F','G','H','I'] as $grupo)
                    <label class="radio-option"><input type="radio" name="grupo" value="{{ $grupo }}" required> {{ $grupo }}</label>
                @endforeach
            </div>

            <label for="control" class="required">Número de control (14 dígitos)</label>
            <input type="text" name="control" id="control" required pattern="[0-9]{14}" maxlength="14" title="Debe contener exactamente 14 dígitos numéricos" oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <div class="buttons">
                <a href="{{ url('/datos-alumno') }}" class="btn">Atrás</a>
                <a href="{{ url('/programa') }}" class="btn">Siguiente</a>
            </div>
        </form>
    </div>
</body>
</html>
