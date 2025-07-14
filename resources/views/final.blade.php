@php
    // Verificar que la sesión tenga el flag de registro exitoso
    if (!Session::get('registro_exitoso')) {
        header('Location: /datosalumno');
        exit();
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Registro Exitoso! - Servicio Social</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B0000',
                        'primary-hover': '#a00000'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen py-4 sm:py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Success Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
            <!-- Header with animation -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 sm:px-8 py-8 text-center relative overflow-hidden">
                <!-- Animated background pattern -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 left-0 w-full h-full bg-white transform rotate-12 scale-150"></div>
                </div>
                
                <div class="relative z-10">
                    <!-- Success Icon with pulse animation -->
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">¡Registro Exitoso!</h1>
                    <p class="text-green-100 text-lg">Tu información ha sido guardada correctamente</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 sm:p-8 space-y-8">
                <!-- Student Info -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Información Registrada</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Nombre:</span>
                                <span class="text-gray-900">{{ session('alumno_nombre') ?? 'No disponible' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Control:</span>
                                <span class="text-gray-900">{{ session('numero_control') ?? 'No disponible' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Carrera:</span>
                                <span class="text-gray-900">{{ session('carrera_nombre') ?? 'No disponible' }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Programa:</span>
                                <span class="text-gray-900">{{ session('programa_nombre') ?? 'No disponible' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Institución:</span>
                                <span class="text-gray-900">{{ session('institucion_nombre') ?? 'No disponible' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">ID Registro:</span>
                                <span class="text-gray-900 font-mono">#{{ session('alumno_id') ?? '000' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download Section -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Descarga de Formatos</h2>
                    </div>

                    <!-- Auto-download status -->
                    <div id="autoDownloadStatus" class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="animate-spin w-5 h-5 border-2 border-yellow-500 border-t-transparent rounded-full"></div>
                            <span class="text-yellow-800 font-medium">Preparando descarga automática...</span>
                        </div>
                        <div class="mt-2 text-sm text-yellow-700">
                            Los archivos se descargarán automáticamente en unos segundos.
                        </div>
                    </div>

                    <!-- Manual download buttons -->
                    <div class="space-y-4">
                        <p class="text-gray-600 text-sm">Si la descarga automática no funciona, usa los botones de abajo:</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Solicitud PDF -->
                            <button onclick="descargarFormato('solicitud')" 
                                    class="download-btn group relative flex flex-col items-center p-4 bg-red-500 hover:bg-red-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <svg class="w-8 h-8 mb-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M9 5h6a2 2 0 012 2v12a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                </svg>
                                <span class="text-sm font-medium">Solicitud</span>
                                <span class="text-xs opacity-80">PDF</span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity"></div>
                            </button>

                            <!-- Escolaridad PDF -->
                            <button onclick="descargarFormato('escolaridad')" 
                                    class="download-btn group relative flex flex-col items-center p-4 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <svg class="w-8 h-8 mb-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium">Escolaridad</span>
                                <span class="text-xs opacity-80">PDF</span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity"></div>
                            </button>

                            <!-- Programa PDF -->
                            <button onclick="descargarFormato('programa')" 
                                    class="download-btn group relative flex flex-col items-center p-4 bg-green-500 hover:bg-green-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <svg class="w-8 h-8 mb-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm font-medium">Programa</span>
                                <span class="text-xs opacity-80">PDF</span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity"></div>
                            </button>

                            <!-- Final PDF -->
                            <button onclick="descargarFormato('final')" 
                                    class="download-btn group relative flex flex-col items-center p-4 bg-purple-500 hover:bg-purple-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <svg class="w-8 h-8 mb-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                <span class="text-sm font-medium">Final</span>
                                <span class="text-xs opacity-80">PDF</span>
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-xl transition-opacity"></div>
                            </button>
                        </div>

                        <!-- Download All Button -->
                        <div class="flex justify-center mt-6">
                            <button onclick="descargarTodos()" 
                                    class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                                <svg class="w-5 h-5 mr-3 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                                Descargar Todos los Formatos
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-2xl transition-opacity"></div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-200">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900">Próximos Pasos</h2>
                    </div>

                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold mt-0.5">1</div>
                            <p><strong>Imprime los formatos</strong> descargados y complétalos con tu información.</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold mt-0.5">2</div>
                            <p><strong>Obtén las firmas</strong> necesarias de tu institución y coordinador.</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs font-bold mt-0.5">3</div>
                            <p><strong>Entrega los documentos</strong> en la oficina de servicio social del plantel.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ route('dashboard') }}" 
                       class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-primary hover:bg-primary-hover text-white font-medium rounded-xl transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        </svg>
                        Ir al Dashboard
                    </a>
                    <a href="{{ url('/datosalumno') }}" 
                       class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-xl transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Registrar Otro Alumno
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal for Downloads -->
    <div id="downloadSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 transform transition-all">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">¡Descarga Exitosa!</h3>
                <p class="text-gray-600 mb-6" id="downloadSuccessMsg">El archivo se ha descargado correctamente.</p>
                <button onclick="cerrarModalDescarga()" 
                        class="w-full inline-flex justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        let confettiLanzado = false;
        let alumnoId = {{ session('alumno_id') ?? 'null' }};

        // Función para lanzar confeti
        function lanzarConfeti() {
            if (confettiLanzado) return;
            confettiLanzado = true;

            // Confeti desde la izquierda
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { x: 0, y: 0.6 },
                colors: ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444']
            });

            // Confeti desde la derecha
            setTimeout(() => {
                confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { x: 1, y: 0.6 },
                    colors: ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444']
                });
            }, 250);

            // Confeti desde el centro
            setTimeout(() => {
                confetti({
                    particleCount: 50,
                    spread: 120,
                    origin: { x: 0.5, y: 0.4 },
                    colors: ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444']
                });
            }, 500);
        }

        // Función para descargar un formato específico
        function descargarFormato(tipo) {
            if (!alumnoId) {
                alert('Error: ID de alumno no disponible');
                return;
            }

            // CAMBIAR esta URL para que coincida con tus rutas existentes
            const url = `/descargar-formato/${alumnoId}/${tipo}`;
            const link = document.createElement('a');
            link.href = url;
            link.download = `${tipo}_${alumnoId}.docx`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Mostrar modal de éxito
            mostrarModalDescarga(`Formato de ${tipo} descargado correctamente`);
            
            // Mini confeti para cada descarga
            confetti({
                particleCount: 30,
                spread: 50,
                origin: { x: 0.5, y: 0.7 },
                colors: ['#10B981', '#3B82F6']
            });
        }

        // Función para descargar todos los formatos
        function descargarTodos() {
            if (!alumnoId) {
                alert('Error: ID de alumno no disponible');
                return;
            }

            const formatos = ['solicitud', 'escolaridad', 'programa', 'final'];
            let descargados = 0;

            formatos.forEach((formato, index) => {
                setTimeout(() => {
                    descargarFormato(formato);
                    descargados++;
                    
                    if (descargados === formatos.length) {
                        // Confeti extra al descargar todo
                        setTimeout(() => {
                            confetti({
                                particleCount: 200,
                                spread: 100,
                                origin: { x: 0.5, y: 0.5 },
                                colors: ['#10B981', '#3B82F6', '#8B5CF6', '#F59E0B']
                            });
                        }, 500);
                        
                        mostrarModalDescarga('¡Todos los formatos han sido descargados correctamente!');
                    }
                }, index * 500); // Espaciar las descargas por 500ms
            });
        }

        // Función para mostrar modal de descarga exitosa
        function mostrarModalDescarga(mensaje) {
            document.getElementById('downloadSuccessMsg').textContent = mensaje;
            document.getElementById('downloadSuccessModal').classList.remove('hidden');
            document.getElementById('downloadSuccessModal').classList.add('flex');
        }

        // Función para cerrar modal de descarga
        function cerrarModalDescarga() {
            document.getElementById('downloadSuccessModal').classList.add('hidden');
            document.getElementById('downloadSuccessModal').classList.remove('flex');
        }

        // Descarga automática al cargar la página
        function iniciarDescargaAutomatica() {
            if (!alumnoId) {
                document.getElementById('autoDownloadStatus').innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <span class="text-red-800 font-medium">Error en descarga automática</span>
                    </div>
                    <div class="mt-2 text-sm text-red-700">
                        Use los botones de descarga manual.
                    </div>
                `;
                return;
            }

            // Simular preparación
            setTimeout(() => {
                document.getElementById('autoDownloadStatus').innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <span class="text-blue-800 font-medium">Iniciando descarga automática...</span>
                    </div>
                    <div class="mt-2 text-sm text-blue-700">
                        Descargando formatos automáticamente...
                    </div>
                `;

                // Iniciar descarga automática después de 2 segundos
                setTimeout(() => {
                    descargarTodos();
                    
                    // Actualizar estado después de la descarga
                    setTimeout(() => {
                        document.getElementById('autoDownloadStatus').innerHTML = `
                            <div class="flex items-center space-x-3">
                                <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-green-800 font-medium">¡Descarga automática completada!</span>
                            </div>
                            <div class="mt-2 text-sm text-green-700">
                                Todos los formatos han sido descargados. Revisa tu carpeta de descargas.
                            </div>
                        `;
                    }, 3000);
                }, 2000);
            }, 1000);
        }

        // Ejecutar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Lanzar confeti después de un pequeño delay
            setTimeout(lanzarConfeti, 500);
            
            // Iniciar descarga automática
            setTimeout(iniciarDescargaAutomatica, 2000);

            // Cerrar modal con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    cerrarModalDescarga();
                }
            });
        });
    </script>
</body>
</html>
