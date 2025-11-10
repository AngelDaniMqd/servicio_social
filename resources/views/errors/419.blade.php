
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Expirada - CBTA 256</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="w-20 h-20 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Sesión Expirada</h1>
            
            <p class="text-gray-600 mb-6">
                Tu sesión ha expirado por inactividad. Por favor, vuelve a intentarlo.
            </p>
            
            <div class="space-y-3">
                <a href="/solicitud" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Volver al Formulario
                </a>
                
                <button onclick="window.history.back()" class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Regresar
                </button>
            </div>
            
            <p class="text-xs text-gray-500 mt-6">
                Si el problema persiste, recarga la página completamente (Ctrl+F5)
            </p>
        </div>
    </div>
</body>
</html>