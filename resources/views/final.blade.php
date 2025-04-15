<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Exitoso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #fff;
            max-width: 700px;
            width: 100%;
            padding: 50px 30px;
            border-radius: 15px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #006400;
            font-size: 26px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #333;
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
            display: inline-block;
        }

        .btn:hover {
            background-color: #a00000;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>¡Formulario enviado correctamente!</h1>
        <p>Gracias por completar tu registro de Servicio Social.</p>
        <a href="{{ url('/solicitud') }}" class="btn">Volver al inicio</a>
    </div>
</body>
</html>
