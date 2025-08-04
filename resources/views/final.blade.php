
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>¡Registro Completado!</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-xl shadow-lg text-center max-w-lg">
    <h1 class="text-3xl font-bold mb-4">¡Registro Exitoso!</h1>
    <p class="mb-2">Gracias <strong>{{ $alumnoNombre ?? 'Alumno' }}</strong>.</p>
    <p class="mb-6">Tu ID de registro es <span class="font-mono">{{ $alumnoId ?? 'N/A' }}</span>.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
      <button onclick="descargar('/export/solicitud/{{ $alumnoId }}')" 
              class="py-3 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
        Carta Presentación
      </button>
      <button onclick="descargar('/export/escolaridad/{{ $alumnoId }}')" 
              class="py-3 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600">
        Escolaridad
      </button>
      <button onclick="descargar('/export/programa/{{ $alumnoId }}')" 
              class="py-3 px-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
        Programa
      </button>
      <button onclick="descargar('/export/final/{{ $alumnoId }}')" 
              class="py-3 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
        Reporte Final
      </button>
    </div>

    <button onclick="descargarTodos()" 
            class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg mb-6">
      📥 Descargar Todos
    </button>
    
    <div class="mt-6">
      <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800">🏠 Volver al inicio</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
      });
    });

    function descargar(url) {
      window.open(url, '_blank');
    }

    function descargarTodos() {
      const rutas = [
        '/export/solicitud/{{ $alumnoId }}',
        '/export/escolaridad/{{ $alumnoId }}',
        '/export/programa/{{ $alumnoId }}',
        '/export/final/{{ $alumnoId }}'
      ];
      rutas.forEach((url, i) => {
        setTimeout(() => descargar(url), i * 1000);
      });
    }
  </script>
</body>
</html>
