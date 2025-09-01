
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
    <div class="mb-6">
      <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-gray-800 mb-2">¡Registro Exitoso!</h1>
      <p class="text-gray-600 mb-2">Gracias <strong class="text-gray-800">{{ $alumnoNombre ?? 'Alumno' }}</strong>.</p>
      <p class="text-gray-600 mb-6">Tu ID de registro es <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $alumnoId ?? 'N/A' }}</span>.</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
      <p class="text-sm text-blue-800 mb-2">
        <strong>Importante:</strong> Descarga tus documentos ahora. Solo podrás acceder a ellos durante esta sesión.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
      <button onclick="descargar('/export/solicitud/{{ $alumnoId }}', 'Carta de Presentación')" 
              class="py-3 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Carta Presentación
      </button>
      
      <button onclick="descargar('/export/escolaridad/{{ $alumnoId }}', 'Información Escolar')" 
              class="py-3 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Info. Escolar
      </button>
      
      <button onclick="descargar('/export/programa/{{ $alumnoId }}', 'Información del Programa')" 
              class="py-3 px-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Programa S.S.
      </button>
      
      <button onclick="descargar('/export/final/{{ $alumnoId }}', 'Reporte Final')" 
              class="py-3 px-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors flex items-center justify-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Reporte Final
      </button>
    </div>

    <button onclick="descargarTodos()" 
            class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg mb-6 hover:from-indigo-700 hover:to-purple-700 transition-all flex items-center justify-center">
      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      Descargar Todos los Documentos
    </button>
    
    <div class="mt-6 pt-6 border-t border-gray-200">
      <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 flex items-center justify-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Volver al inicio
      </a>
    </div>
  </div>

  <!-- Toast notification -->
  <div id="toast" class="fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 max-w-sm">
    <div class="flex items-center">
      <div id="toast-icon" class="w-8 h-8 rounded-full flex items-center justify-center mr-3"></div>
      <div>
        <div id="toast-title" class="font-medium text-gray-900"></div>
        <div id="toast-message" class="text-sm text-gray-600"></div>
      </div>
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

    function showToast(title, message, type = 'success') {
      const toast = document.getElementById('toast');
      const icon = document.getElementById('toast-icon');
      const titleEl = document.getElementById('toast-title');
      const messageEl = document.getElementById('toast-message');
      
      titleEl.textContent = title;
      messageEl.textContent = message;
      
      if (type === 'success') {
        icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-green-100';
        icon.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
      } else if (type === 'error') {
        icon.className = 'w-8 h-8 rounded-full flex items-center justify-center mr-3 bg-red-100';
        icon.innerHTML = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
      }
      
      toast.classList.remove('translate-x-full');
      
      setTimeout(() => {
        toast.classList.add('translate-x-full');
      }, 3000);
    }

    function descargar(url, nombre = 'documento') {
      try {
        showToast('Descargando...', `Preparando ${nombre}`, 'success');
        window.open(url, '_blank');
      } catch (error) {
        showToast('Error', `No se pudo descargar ${nombre}`, 'error');
      }
    }

    function descargarTodos() {
      const documentos = [
        { url: '/export/solicitud/{{ $alumnoId }}', nombre: 'Carta de Presentación' },
        { url: '/export/escolaridad/{{ $alumnoId }}', nombre: 'Información Escolar' },
        { url: '/export/programa/{{ $alumnoId }}', nombre: 'Información del Programa' },
        { url: '/export/final/{{ $alumnoId }}', nombre: 'Reporte Final' }
      ];
      
      showToast('Iniciando descargas', 'Se descargarán todos los documentos automáticamente', 'success');
      
      documentos.forEach((doc, i) => {
        setTimeout(() => {
          descargar(doc.url, doc.nombre);
        }, i * 1500); // 1.5 segundos entre cada descarga
      });
    }
  </script>
</body>
</html>
