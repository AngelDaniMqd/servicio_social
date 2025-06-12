@extends('layouts.blaze')

@section('title', 'Gestión de Base de Datos')
@section('page-title', 'Administración del Sistema')
@section('page-description', 'Gestiona todas las tablas de la base de datos del sistema de servicio social')

@section('content')
<div class="max-w-7xl mx-auto">
    @if($selectedTable && $rows)
        <!-- Header con filtros en popover -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            @if($selectedTable === 'formatos')
                <!-- Header específico para formatos -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Gestión de Formatos</h2>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($rows->count() > 0)
                                    Formato configurado - Use "Editar" para actualizar las plantillas
                                @else
                                    No hay formatos configurados - Use "Subir Formatos" para comenzar
                                @endif
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            @if($rows->count() > 0)
                                <a href="{{ route('formatos.upload') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar Formatos
                                </a>
                            @else
                                <a href="{{ route('formatos.upload') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    Subir Formatos
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Header normal para otras tablas -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Mostrando {{ $rows->firstItem() ?? 0 }} - {{ $rows->lastItem() ?? 0 }} de {{ $rows->total() ?? 0 }} registros
                                @if(request()->hasAny(['nombre', 'apellidos', 'telefono', 'correo', 'edad_id', 'sexo_id', 'rol_id', 'status_id', 'cp', 'fecha_registro_desde', 'fecha_registro_hasta', 'carrera_nombre', 'semestre_nombre', 'grupo_letra', 'modalidad_nombre', 'institucion_nombre', 'titulo_nombre', 'metodo_nombre', 'tipo_programa_nombre']))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                        Filtrado
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            @if($selectedTable === 'alumno')
                                <!-- Botón de filtros en popover -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" 
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                        </svg>
                                        Filtros
                                        @if(request()->hasAny(['nombre', 'apellidos', 'telefono', 'correo', 'edad_id', 'sexo_id', 'rol_id', 'status_id', 'cp', 'fecha_registro_desde', 'fecha_registro_hasta', 'carrera_nombre', 'semestre_nombre', 'grupo_letra', 'modalidad_nombre', 'institucion_nombre', 'titulo_nombre', 'metodo_nombre', 'tipo_programa_nombre']))
                                            <span class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-blue-500 rounded-full">
                                                {{ collect(request()->only(['nombre', 'apellidos', 'telefono', 'correo', 'edad_id', 'sexo_id', 'rol_id', 'status_id', 'cp', 'fecha_registro_desde', 'fecha_registro_hasta', 'carrera_nombre', 'semestre_nombre', 'grupo_letra', 'modalidad_nombre', 'institucion_nombre', 'titulo_nombre', 'metodo_nombre', 'tipo_programa_nombre']))->filter()->count() }}
                                            </span>
                                        @endif
                                    </button>

                                    <!-- Popover de filtros -->
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-[800px] max-w-[95vw] bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-[85vh] overflow-y-auto">
                                        
                                        <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                            <h3 class="text-lg font-medium text-gray-900">Filtros de Búsqueda Avanzada</h3>
                                            <p class="text-sm text-gray-500 mt-1">Filtra los registros de alumnos por múltiples criterios</p>
                                        </div>

                                        <form method="GET" action="{{ route('dashboard') }}" class="p-6 space-y-6">
                                            <input type="hidden" name="table" value="alumno">
                                            
                                            <!-- Información Personal -->
                                            <div class="space-y-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    Información Personal
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <div>
                                                        <label for="nombre" class="block text-xs font-medium text-gray-700 mb-2">Nombre</label>
                                                        <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                               placeholder="Buscar por nombre...">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="apellidos" class="block text-xs font-medium text-gray-700 mb-2">Apellidos</label>
                                                        <input type="text" name="apellidos" id="apellidos" value="{{ request('apellidos') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                               placeholder="Buscar por apellidos...">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="correo" class="block text-xs font-medium text-gray-700 mb-2">Correo</label>
                                                        <input type="email" name="correo" id="correo" value="{{ request('correo') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                               placeholder="email@ejemplo.com">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="telefono" class="block text-xs font-medium text-gray-700 mb-2">Teléfono</label>
                                                        <input type="text" name="telefono" id="telefono" value="{{ request('telefono') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                               placeholder="Número de teléfono">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información Académica -->
                                            <div class="space-y-4 border-t border-gray-200 pt-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    Información Académica
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <div>
                                                        <label for="carrera_nombre" class="block text-xs font-medium text-gray-700 mb-2">Carrera</label>
                                                        <select name="carrera_nombre" id="carrera_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todas las carreras</option>
                                                            @foreach($filterOptions['carreras'] ?? [] as $carrera)
                                                                <option value="{{ $carrera->nombre }}" {{ request('carrera_nombre') == $carrera->nombre ? 'selected' : '' }}>
                                                                    {{ $carrera->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="semestre_nombre" class="block text-xs font-medium text-gray-700 mb-2">Semestre</label>
                                                        <select name="semestre_nombre" id="semestre_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los semestres</option>
                                                            @foreach($filterOptions['semestres'] ?? [] as $semestre)
                                                                <option value="{{ $semestre->nombre }}" {{ request('semestre_nombre') == $semestre->nombre ? 'selected' : '' }}>
                                                                    {{ $semestre->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="grupo_letra" class="block text-xs font-medium text-gray-700 mb-2">Grupo</label>
                                                        <select name="grupo_letra" id="grupo_letra" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los grupos</option>
                                                            @foreach($filterOptions['grupos'] ?? [] as $grupo)
                                                                @php
                                                                    // Determinar qué campo usar para mostrar el grupo
                                                                    $displayField = 'letra';
                                                                    $valueField = 'letra';
                                                                    
                                                                    if (!isset($grupo->letra)) {
                                                                        if (isset($grupo->nombre)) {
                                                                            $displayField = 'nombre';
                                                                            $valueField = 'nombre';
                                                                        } else {
                                                                            $displayField = 'id';
                                                                            $valueField = 'id';
                                                                        }
                                                                    }
                                                                    
                                                                    $displayValue = $grupo->$displayField ?? 'Grupo ' . $grupo->id;
                                                                    $optionValue = $grupo->$valueField ?? $grupo->id;
                                                                @endphp
                                                                <option value="{{ $optionValue }}" {{ request('grupo_letra') == $optionValue ? 'selected' : '' }}>
                                                                    @if($displayField === 'letra')
                                                                        Grupo {{ $displayValue }}
                                                                    @else
                                                                        {{ $displayValue }}
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="modalidad_nombre" class="block text-xs font-medium text-gray-700 mb-2">Modalidad</label>
                                                        <select name="modalidad_nombre" id="modalidad_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todas las modalidades</option>
                                                            @foreach($filterOptions['modalidades'] ?? [] as $modalidad)
                                                                <option value="{{ $modalidad->nombre }}" {{ request('modalidad_nombre') == $modalidad->nombre ? 'selected' : '' }}>
                                                                    {{ $modalidad->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información de Servicio Social -->
                                            <div class="space-y-4 border-t border-gray-200 pt-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    Información de Servicio Social
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <div>
                                                        <label for="institucion_nombre" class="block text-xs font-medium text-gray-700 mb-2">Institución</label>
                                                        <select name="institucion_nombre" id="institucion_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todas las instituciones</option>
                                                            @foreach($filterOptions['instituciones'] ?? [] as $institucion)
                                                                <option value="{{ $institucion->nombre }}" {{ request('institucion_nombre') == $institucion->nombre ? 'selected' : '' }}>
                                                                    {{ $institucion->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="titulo_nombre" class="block text-xs font-medium text-gray-700 mb-2">Título</label>
                                                        <select name="titulo_nombre" id="titulo_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los títulos</option>
                                                            @foreach($filterOptions['titulos'] ?? [] as $titulo)
                                                                <option value="{{ $titulo->titulo ?? $titulo->nombre }}" {{ request('titulo_nombre') == ($titulo->titulo ?? $titulo->nombre) ? 'selected' : '' }}>
                                                                    {{ $titulo->titulo ?? $titulo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="metodo_nombre" class="block text-xs font-medium text-gray-700 mb-2">Método</label>
                                                        <select name="metodo_nombre" id="metodo_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los métodos</option>
                                                            @foreach($filterOptions['metodos'] ?? [] as $metodo)
                                                                <option value="{{ $metodo->metodo ?? $metodo->nombre }}" {{ request('metodo_nombre') == ($metodo->metodo ?? $metodo->nombre) ? 'selected' : '' }}>
                                                                    {{ $metodo->metodo ?? $metodo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="tipo_programa_nombre" class="block text-xs font-medium text-gray-700 mb-2">Tipo de Programa</label>
                                                        <select name="tipo_programa_nombre" id="tipo_programa_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los tipos</option>
                                                            @foreach($filterOptions['tipos_programa'] ?? [] as $tipo)
                                                                <option value="{{ $tipo->tipo ?? $tipo->nombre }}" {{ request('tipo_programa_nombre') == ($tipo->tipo ?? $tipo->nombre) ? 'selected' : '' }}>
                                                                    {{ $tipo->tipo ?? $tipo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información Demográfica -->
                                            <div class="space-y-4 border-t border-gray-200 pt-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                    </svg>
                                                    Información Demográfica
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <div>
                                                        <label for="edad_id" class="block text-xs font-medium text-gray-700 mb-2">Edad</label>
                                                        <select name="edad_id" id="edad_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todas las edades</option>
                                                            @foreach($filterOptions['edades'] ?? [] as $edad)
                                                                <option value="{{ $edad->id }}" {{ request('edad_id') == $edad->id ? 'selected' : '' }}>
                                                                    {{ $edad->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="sexo_id" class="block text-xs font-medium text-gray-700 mb-2">Sexo</label>
                                                        <select name="sexo_id" id="sexo_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos</option>
                                                            @foreach($filterOptions['sexos'] ?? [] as $sexo)
                                                                <option value="{{ $sexo->id }}" {{ request('sexo_id') == $sexo->id ? 'selected' : '' }}>
                                                                    {{ $sexo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="rol_id" class="block text-xs font-medium text-gray-700 mb-2">Rol</label>
                                                        <select name="rol_id" id="rol_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los roles</option>
                                                            @foreach($filterOptions['roles'] ?? [] as $rol)
                                                                <option value="{{ $rol->id }}" {{ request('rol_id') == $rol->id ? 'selected' : '' }}>
                                                                    {{ $rol->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="status_id" class="block text-xs font-medium text-gray-700 mb-2">Estado</label>
                                                        <select name="status_id" id="status_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                            <option value="">Todos los estados</option>
                                                            @foreach($filterOptions['status'] ?? [] as $status)
                                                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                                                    {{ $status->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Fechas y Ubicación -->
                                            <div class="space-y-4 border-t border-gray-200 pt-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Fechas y Ubicación
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    <div>
                                                        <label for="cp" class="block text-xs font-medium text-gray-700 mb-2">Código Postal</label>
                                                        <input type="text" name="cp" id="cp" value="{{ request('cp') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                                               placeholder="Ej: 12345">
                                                    </div>

                                                    <div>
                                                        <label for="fecha_registro_desde" class="block text-xs font-medium text-gray-700 mb-2">Fecha desde</label>
                                                        <input type="date" name="fecha_registro_desde" id="fecha_registro_desde" value="{{ request('fecha_registro_desde') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="fecha_registro_hasta" class="block text-xs font-medium text-gray-700 mb-2">Fecha hasta</label>
                                                        <input type="date" name="fecha_registro_hasta" id="fecha_registro_hasta" value="{{ request('fecha_registro_hasta') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones -->
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-gray-200 space-y-3 sm:space-y-0">
                                                <button type="button" onclick="clearFilters()" 
                                                        class="flex items-center justify-center px-4 py-2 text-sm text-gray-600 hover:text-gray-900 font-medium border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Limpiar filtros
                                                </button>
                                                
                                                <div class="flex space-x-3">
                                                    <button type="button" @click="open = false" 
                                                            class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit" 
                                                            class="flex-1 sm:flex-none px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                        Aplicar Filtros
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Botones de exportación -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('export.excel', ['table' => 'alumno'] + request()->query()) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Excel
                                    </a>
                                    <a href="{{ route('export.pdf', ['table' => 'alumno'] + request()->query()) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-lg font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            @endif
                            
                            @if($selectedTable !== 'formatos')
                                <a href="{{ route('record.create', ['table' => $selectedTable]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Agregar Registro
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
                
            <!-- Tabla de datos -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if($rows->isNotEmpty())
                                @foreach(array_keys((array)$rows->first()) as $column)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ ucfirst(str_replace('_', ' ', $column)) }}
                                    </th>
                                @endforeach
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rows as $row)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @foreach((array)$row as $column => $value)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(is_null($value))
                                            <span class="text-gray-400 italic">Sin información</span>
                                        @elseif($value === '')
                                            <span class="text-gray-400 italic">Vacío</span>
                                        @elseif(strlen($value) > 50)
                                            <span title="{{ $value }}" class="cursor-help">{{ substr($value, 0, 50) }}...</span>
                                        @elseif(preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
                                            {{ date('d/m/Y', strtotime($value)) }}
                                        @elseif(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value))
                                            {{ date('d/m/Y H:i', strtotime($value)) }}
                                        @elseif($column === 'correo_institucional')
                                            <a href="mailto:{{ $value }}" class="text-blue-600 hover:text-blue-800">{{ $value }}</a>
                                        @elseif($column === 'telefono')
                                            <a href="tel:{{ $value }}" class="text-blue-600 hover:text-blue-800">{{ $value }}</a>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($selectedTable === 'formatos')
                                        <!-- Acciones especiales para formatos -->
                                        <a href="{{ route('formatos.upload') }}" 
                                           class="text-amber-600 hover:text-amber-900 transition-colors mr-3" title="Editar formatos">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <!-- Botón de edición estándar -->
                                        <a href="{{ route('record.edit', ['table' => $selectedTable, 'id' => $row->id]) }}" 
                                           class="text-amber-600 hover:text-amber-900 transition-colors mr-3" title="Editar registro">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    <!-- Botón de eliminar -->
                                    <button onclick="confirmDelete('{{ $row->id }}', '{{ $selectedTable }}')" 
                                            class="text-red-600 hover:text-red-900 transition-colors" title="Eliminar registro">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-lg font-medium">No se encontraron registros</p>
                                    <p class="text-sm">
                                        @if(request()->hasAny(['nombre', 'apellidos', 'telefono', 'correo']))
                                            Intenta ajustar o limpiar los filtros para mostrar más resultados.
                                        @else
                                            No hay registros en esta tabla.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if($rows->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                        <div class="text-sm text-gray-700">
                            <span class="font-medium">{{ $rows->total() }}</span> registros total
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            {{-- Botón Anterior --}}
                            @if ($rows->onFirstPage())
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $rows->previousPageUrl() }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </a>
                            @endif

                            {{-- Número de página actual --}}
                            <span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md">
                                {{ $rows->currentPage() }}
                            </span>

                            {{-- Botón Siguiente --}}
                            @if ($rows->hasMorePages())
                                <a href="{{ $rows->nextPageUrl() }}" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-700">
                            Página {{ $rows->currentPage() }} de {{ $rows->lastPage() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @elseif($selectedTable)
        <!-- Mensaje cuando se selecciona una tabla pero no hay datos -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tabla vacía</h3>
            <p class="text-gray-600 mb-4">No hay registros en la tabla "{{ $selectedTable }}".</p>
            @if($selectedTable === 'formatos')
                <a href="{{ route('formatos.upload') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Subir Formatos
                </a>
            @else
                <a href="{{ route('record.create', ['table' => $selectedTable]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Agregar Primer Registro
                </a>
            @endif
        </div>
    @else
        <!-- Mensaje inicial cuando no se ha seleccionado ninguna tabla -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Administración de Base de Datos</h3>
            <p class="text-gray-600 mb-4">Selecciona una tabla del menú lateral para gestionar sus registros.</p>
            <div class="text-sm text-gray-500">
                <p>• Gestiona alumnos, carreras, programas y más</p>
                <p>• Aplica filtros avanzados para encontrar información específica</p>
                <p>• Exporta datos a Excel o PDF para auditorías</p>
            </div>
        </div>
    @endif
</div>

<script>
function clearFilters() {
    window.location.href = '{{ route("dashboard") }}?table=alumno';
}
</script>

<!-- Alpine.js para el popover -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection