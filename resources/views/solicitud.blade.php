<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Servicio Social</title>
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
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 800px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card img {
            display: block;
            margin: 0 auto 25px;
            max-width: 150px;
            height: auto;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #111;
        }

        p {
            font-size: 16px;
            line-height: 1.7;
            color: #333;
            margin-bottom: 1rem;
        }

        .resaltado {
            font-weight: bold;
            color: #8B0000;
        }

        .btn {
            background-color: #8B0000;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            text-align: center;
            text-decoration: none;
            width: fit-content;
        }

        .btn:hover {
            background-color: #a00000;
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

        @media (max-width: 768px) {
            .card {
                padding: 25px 20px;
                margin: 0 15px;
            }

            h1 {
                font-size: 20px;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ asset('img/servicio-social-banner.jpg') }}" alt="Logo CBTa">
        <h1>SOLICITUD DE SERVICIO SOCIAL (SSS)</h1>

        {{-- ALERTA DE ERROR SI VIENE DESDE EL CONTROLADOR --}}
        @if(session('error'))
            <div class="alerta">
                {{ session('error') }}<br>
                Por favor, vuelva a intentarlo o contacte al personal de apoyo.
            </div>
        @endif

        <p><span class="resaltado">DIRECCIÓN GENERAL DE EDUCACIÓN TECNOLÓGICA AGROPECUARIA Y CIENCIAS DEL MAR</span><br>
        <span class="resaltado">CENTRO DE BACHILLERATO TECNOLÓGICO AGROPECUARIO N°256</span><br>
        DEPARTAMENTO DE VINCULACIÓN Y DESARROLLO INSTITUCIONAL</p>

        <p>El presente formulario debe ser llenado de manera correcta y con la información que se solicita dentro del periodo que esté disponible el alta para la realización del servicio social, esto con la finalidad de agilizar el proceso.</p>

        <p>Una vez revisado se enviará la confirmación para el proceso de servicio social, <span class="resaltado">en dado caso que la información sea errónea se notificará</span> y se habilitará de nueva cuenta el formulario para que sea contestado de manera idónea.</p>

        <p><span class="resaltado">Debe ser llenado con el correo institucional que se encuentre en tu credencial, de no ser así no se tomará en cuenta tu registro.</span></p>

        <a href="{{ url('/datos-alumno') }}" class="btn">Siguiente</a>
    </div>
</body>
</html>
