
@extends('layouts.blaze')

@section('title', 'Gestión de Formatos')
@section('page-title', 'Subir Formatos de Documentos')
@section('page-description', 'Sube las plantillas de Word para carta de presentación, reporte mensual y reporte final')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $formato ? 'Actualizar Formatos' : 'Subir Formatos Iniciales' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    {{ $formato ? 'Actualiza las plantillas de documentos existentes' : 'Sube las plantillas de Word que se usarán para generar documentos' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Formulario de subida -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ route('formatos.upload.store') }}" method="POST" enctype="multipart/form-data" id="formatosForm">
            @csrf

            <!-- Mostrar errores -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 m-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Se encontraron errores:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 m-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="p-6 space-y-8">
                <!-- Formato Word (Carta de Presentación) -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Carta de Presentación</h3>
                            <p class="text-sm text-gray-500">Plantilla de Word para generar cartas de presentación</p>
                        </div>
                    </div>
                    
                    @if($formato && !empty($formato->formato_word))
                        <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-green-700">Formato actual subido - Subir archivo nuevo para reemplazar</span>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" name="formato_word" id="formato_word" accept=".doc,.docx" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           {{ !$formato ? 'required' : '' }}>
                    <p class="mt-2 text-xs text-gray-500">
                        @if($formato)
                            Opcional: Sube un nuevo archivo .doc o .docx para reemplazar la plantilla actual
                        @else
                            <span class="text-red-600 font-medium">Obligatorio:</span> Sube un archivo .doc o .docx con placeholders como ${nombre}, ${carrera}, etc.
                        @endif
                    </p>
                </div>

                <!-- Formato Reporte Mensual -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Reporte Mensual</h3>
                            <p class="text-sm text-gray-500">Plantilla para reportes mensuales de servicio social</p>
                        </div>
                    </div>
                    
                    @if($formato && !empty($formato->formato_reporte))
                        <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-green-700">Formato actual subido</span>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" name="formato_reporte" id="formato_reporte" accept=".doc,.docx"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    <p class="mt-2 text-xs text-gray-500">Opcional: Plantilla para reportes mensuales</p>
                </div>

                <!-- Formato Reporte Final -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Reporte Final</h3>
                            <p class="text-sm text-gray-500">Plantilla para el reporte final de servicio social</p>
                        </div>
                    </div>
                    
                    @if($formato && !empty($formato->formato_reporte_final))
                        <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-green-700">Formato actual subido</span>
                            </div>
                        </div>
                    @endif
                    
                    <input type="file" name="formato_reporte_final" id="formato_reporte_final" accept=".doc,.docx"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <p class="mt-2 text-xs text-gray-500">Opcional: Plantilla para el reporte final</p>
                </div>

                <!-- Instrucciones -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Instrucciones para las plantillas:</h4>
                    <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                        <li>Usa placeholders como <code class="bg-gray-200 px-1 rounded">${nombre}</code>, <code class="bg-gray-200 px-1 rounded">${apellido_p}</code>, <code class="bg-gray-200 px-1 rounded">${carrera}</code>, etc.</li>
                        <li>Los archivos deben estar en formato .doc o .docx</li>
                        <li>{{ $formato ? 'Solo sube archivos para los formatos que quieras actualizar' : 'Solo la carta de presentación es obligatoria' }}</li>
                        <li>Tamaño máximo por archivo: 5MB</li>
                        <li>El usuario que sube/actualiza queda registrado en el sistema</li>
                    </ul>
                </div>

                @if($formato)
                    <!-- Información del formato actual -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-blue-900 mb-3">Estado actual del formato:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ !empty($formato->formato_word) ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                <span class="text-gray-700">Carta de Presentación</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ !empty($formato->formato_reporte) ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                <span class="text-gray-700">Reporte Mensual</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ !empty($formato->formato_reporte_final) ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></div>
                                <span class="text-gray-700">Reporte Final</span>
                            </div>
                        </div>
                        <p class="text-xs text-blue-700 mt-3">
                            Usuario ID: {{ $formato->usuario_id ?? 'No especificado' }} • 
                            Última actualización: {{ 
                                isset($formato->updated_at) && $formato->updated_at 
                                    ? date('d/m/Y H:i', strtotime($formato->updated_at)) 
                                    : 'No disponible' 
                            }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Botones de acción -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('dashboard', ['table' => 'formatos']) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Cancelar
                </a>

                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm"
                        id="submitBtn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span id="submitText">
                        {{ $formato ? 'Actualizar Formatos' : 'Subir Formatos' }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('formatosForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const originalText = submitText.textContent;

    // Cambiar estado del botón
    submitBtn.disabled = true;
    submitText.textContent = 'Procesando...';
    
    // Resetear después de 30 segundos si no se redirige
    setTimeout(function() {
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    }, 30000);
});
</script>
@endsection