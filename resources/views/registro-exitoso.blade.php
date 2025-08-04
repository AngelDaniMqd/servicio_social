<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>¡Registro Completado!</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-2 sm:p-4">
  <div class="bg-white p-4 sm:p-8 rounded-xl shadow-lg text-center max-w-lg w-full mx-2">
    
    <!-- Ícono de éxito -->
    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
      <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
      </svg>
    </div>
    
    <h1 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 text-green-600">¡REGISTRO EXITOSO!</h1>
    <p class="mb-4 sm:mb-6 text-base sm:text-lg px-2">Gracias <strong>{{ $alumnoNombre ?? 'Alumno' }}</strong>.</p>

    <!-- ADVERTENCIA IMPORTANTE SOBRE EL FOLIO -->
    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6 animate-pulse">
      <div class="flex items-center justify-center mb-2">
        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <h3 class="text-base sm:text-lg font-bold text-red-800">⚠️ ¡MUY IMPORTANTE! ⚠️</h3>
      </div>
      <p class="text-red-700 font-semibold mb-2 text-sm sm:text-base">GUARDA ESTE FOLIO DE REGISTRO:</p>
      <div class="bg-white border-2 border-red-300 rounded-lg p-2 sm:p-3 mb-2">
        <p class="text-xl sm:text-2xl font-mono font-bold text-red-800">{{ $alumnoId ?? 'N/A' }}</p>
      </div>
      <p class="text-xs sm:text-sm text-red-600 font-medium px-1">
        📝 <strong>Anota este número</strong> - Lo necesitarás para cualquier actualización futura de tu información de servicio social.
      </p>
    </div>

    @if(isset($numeroControl))
    <div class="bg-blue-50 p-2 sm:p-3 rounded-lg mb-3 sm:mb-4">
      <p class="text-xs sm:text-sm text-gray-600">Número de Control: <strong>{{ $numeroControl }}</strong></p>
    </div>
    @endif

    @if(isset($programaNombre))
    <div class="bg-purple-50 p-2 sm:p-3 rounded-lg mb-4 sm:mb-6">
      <p class="text-xs sm:text-sm text-gray-600">Programa: <strong class="break-words">{{ $programaNombre }}</strong></p>
    </div>
    @endif

    <!-- Botones de descarga - Grid responsivo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
      <button onclick="descargar('{{ route('formatos.download', ['id' => $alumnoId, 'tipo' => 'word']) }}')" 
              class="py-2.5 sm:py-3 px-3 sm:px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-xs sm:text-sm transition-colors">
        📄 Carta Presentación
      </button>
      <button onclick="descargar('{{ route('formatos.download', ['id' => $alumnoId, 'tipo' => 'reporte']) }}')" 
              class="py-2.5 sm:py-3 px-3 sm:px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 text-xs sm:text-sm transition-colors">
        🎓 Reporte
      </button>
      <button onclick="descargar('{{ route('formatos.download', ['id' => $alumnoId, 'tipo' => 'reporte_final']) }}')" 
              class="py-2.5 sm:py-3 px-3 sm:px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 text-xs sm:text-sm transition-colors sm:col-span-2">
        📊 Reporte Final
      </button>
    </div>

    <button onclick="descargarTodos()" 
            class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg mb-4 sm:mb-6 text-sm sm:text-lg font-bold hover:from-indigo-700 hover:to-purple-700 transition-colors">
      📦 DESCARGAR TODOS
    </button>

    <!-- Botón para copiar el folio -->
    <button onclick="copiarFolio(this)" 
            class="w-full px-3 sm:px-4 py-2 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg hover:bg-yellow-200 transition-colors mb-3 sm:mb-4 font-medium text-xs sm:text-sm">
      📋 Copiar Folio al Portapapeles
    </button>
    
    <!-- Enlaces de navegación -->
    <div class="mt-4 sm:mt-6 space-y-2">
      <a href="{{ url('/') }}" class="block text-blue-600 hover:text-blue-800 font-medium text-sm sm:text-base">🏠 Volver al inicio</a>
      <a href="{{ url('/datos-alumno') }}" class="block text-gray-600 hover:text-gray-800 text-sm sm:text-base">📝 Nuevo registro</a>
    </div>
  </div>

  <script>
    // Confetti al cargar
    document.addEventListener('DOMContentLoaded', function() {
      confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
      });
    });

    function descargar(url) {
      console.log('Descargando:', url);
      window.open(url, '_blank');
    }

    function descargarTodos() {
      const rutas = [
        '{{ route('formatos.download', ['id' => $alumnoId, 'tipo' => 'word']) }}',
        '{{ route('formatos.download', ['id' => $alumnoId, 'tipo' => 'reporte']) }}',
        '{{ route('formatos.download', ['id' => $alumnoId, 'tipo' => 'reporte_final']) }}'
      ];
      
      rutas.forEach((url, index) => {
        setTimeout(() => descargar(url), index * 1000);
      });
    }

    function copiarFolio(btn) {
      const folio = '{{ $alumnoId ?? 'N/A' }}';
      navigator.clipboard.writeText(folio).then(() => {
        // Feedback visual mejorado para móviles
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = '✅ ¡Copiado!';
        btn.className = 'w-full px-3 sm:px-4 py-2 bg-green-100 text-green-800 border border-green-300 rounded-lg font-medium text-xs sm:text-sm';
        
        setTimeout(() => {
          btn.innerHTML = textoOriginal;
          btn.className = 'w-full px-3 sm:px-4 py-2 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg hover:bg-yellow-200 transition-colors mb-3 sm:mb-4 font-medium text-xs sm:text-sm';
        }, 2000);
      }).catch((err) => {
        console.error('Error al copiar el folio: ', err);
        alert('Folio: ' + folio + '\n\nCopia este número manualmente');
      });
    }
  </script>
</body>
</html>