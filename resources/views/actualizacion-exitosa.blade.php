<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización Exitosa - CBTA 256</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-8 px-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Icono de éxito -->
            <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <!-- Título -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">¡Actualización Exitosa!</h1>

            <!-- Mensaje -->
            <p class="text-gray-600 mb-6">
                @if(session('alumno_nombre'))
                    <strong>{{ session('alumno_nombre') }}</strong>, tu información ha sido actualizada correctamente.
                @else
                    Tu información ha sido actualizada correctamente.
                @endif
            </p>

            <!-- Información adicional -->
            @if(session('alumno_nombre'))
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <strong>Nombre:</strong> {{ session('alumno_nombre') }}
                    </p>
                    <p class="text-xs text-blue-600 mt-1">
                        Tu información ha sido actualizada exitosamente
                    </p>
                </div>
            @endif

            <!-- Botones de acción -->
            <div class="space-y-3">
                <a href="{{ url('/solicitud') }}" 
                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    🔍 Buscar Otro Registro
                </a>
                
                <a href="{{ url('/') }}" 
                   class="w-full inline-flex items-center justify-center px-6 py-3 bg-gray-500 border border-transparent rounded-lg font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    🏠 Ir al Inicio
                </a>
            </div>

            <!-- Mensaje adicional -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Si necesitas realizar más cambios, puedes buscar tu registro nuevamente con tu folio y correo institucional.
                </p>
            </div>
        </div>
    </div>
</body>
</html>