
@extends('layouts.app') {{-- O el layout que uses para páginas públicas --}}

@section('title', 'Actualizar Mi Información')
@section('page-title', 'Actualizar Mi Información de Servicio Social')
@section('page-description', 'Modifica tu información personal, académica y de servicio social')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ url('/') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4l2 2 4-4"/>
                    </svg>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500">Actualizar Mi Información</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Formulario con ACTION PÚBLICO -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ url('/alumno/' . $alumno->id . '/actualizar') }}" method="POST" enctype="multipart/form-data" id="editAlumnoForm">
            @csrf
            @method('PUT')
            
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
                            <h3 class="text-sm font-medium text-red-800">Se encontraron errores en el formulario:</h3>
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

            {{-- Incluir aquí todo el contenido del formulario de alumno-edit.blade.php --}}
            {{-- pero cambiar las rutas y acciones para el contexto público --}}

            <!-- Botones de acción PÚBLICOS -->
            <div class="p-6 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ url('/solicitud') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a2 2 0 002-2v-1m-1-4l-4-4m0 0l-4 4m4-4v12"/>
                    </svg>
                    <span id="submitText">Actualizar Mi Información</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts similares a alumno-edit pero adaptados --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Scripts de validación y funcionalidad...
    // (copiar de alumno-edit.blade.php)
});
</script>
@endsection