@extends('layouts.blaze')
@php
use Carbon\Carbon;
@endphp
@section('title', 'Gestión de Base de Datos')
@section('page-title', 'Administración del Sistema')
@section('page-description', 'Gestiona todas las tablas de la base de datos del sistema de servicio social')
@section('content')
<div id="cancelModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">¿Eliminar alumno?</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Esta acción cambiará el status del alumno a "Eliminar". 
                    <strong>El alumno no se mostrará más en la lista principal.</strong>
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelCancelBtn" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Volver
                </button>
                <button id="confirmCancelBtn" 
                        class="px-4 py-2 bg-orange-600 text-white text-base font-medium rounded-md w-24 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300 disabled:opacity-50"
                        disabled>
                    <span id="cancelText">Eliminar</span>
                    <span id="cancelCountdown" class="ml-1 font-bold"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[60]">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.988-.833-2.732 0L4.982 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">¿Eliminar registro permanentemente?</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Esta acción eliminará permanentemente el registro de la base de datos. 
                    <strong>No se puede deshacer.</strong>
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="cancelDelete" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Volver
                </button>
                <button id="confirmDelete" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50"
                        disabled>
                    <span id="deleteText">Eliminar</span>
                    <span id="countdown" class="ml-1 font-bold"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="max-w-full  pt-10">
    @if($selectedTable && $rows)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            @if($selectedTable === 'formatos')
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
                               @if($selectedTable !== 'formatos')
                                <a href="{{ route('record.create', ['table' => $selectedTable]) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Agregar Nuevo
                                </a>
                            @endif
                            @if($selectedTable === 'alumno')
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
                                    <div x-show="open" 
                                    style="display: none;"
                                         @click.away="open = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-[900px] max-w-[95vw] bg-gray-50 rounded-lg shadow-xl border border-gray-200 z-100 max-h-[85vh] overflow-y-auto">
                                        <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                            <h3 class="text-lg font-medium text-gray-900">Filtros de Búsqueda Avanzada</h3>
                                            <p class="text-sm text-gray-500 mt-1">Filtra los registros de alumnos por múltiples criterios</p>
                                        </div>
                                        <form method="GET" action="{{ route('dashboard') }}" class="p-6 space-y-6">
                                            <input type="hidden" name="table" value="alumno">
                                            <div class="space-y-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    Información Personal
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    <div> 
                                                        <label for="nombre" class="block text-xs font-medium text-gray-700 mb-2">Nombre</label>
                                                        <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="Buscar por nombre">
                                                    </div>
                                                    <div>
                                                        <label for="apellidos" class="block text-xs font-medium text-gray-700 mb-2">Apellidos</label>
                                                        <input type="text" name="apellidos" id="apellidos" value="{{ request('apellidos') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="Apellido paterno o materno">
                                                    </div>
                                                    <div>
                                                        <label for="telefono" class="block text-xs font-medium text-gray-700 mb-2">Teléfono</label>
                                                        <input type="text" name="telefono" id="telefono" value="{{ request('telefono') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="Número de teléfono">
                                                    </div>
                                                    <div>
                                                        <label for="correo" class="block text-xs font-medium text-gray-700 mb-2">Correo Institucional</label>
                                                        <input type="email" name="correo" id="correo" value="{{ request('correo') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="@cbta256.edu.mx">
                                                    </div>
                                                    <div>
                                                        <label for="edad_id" class="block text-xs font-medium text-gray-700 mb-2">Edad</label>
                                                        <select name="edad_id" id="edad_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
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
                                                        <select name="sexo_id" id="sexo_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
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
                                                        <select name="rol_id" id="rol_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos los roles</option>
                                                            @foreach($filterOptions['roles'] ?? [] as $rol)
                                                                <option value="{{ $rol->id }}" {{ request('rol_id') == $rol->id ? 'selected' : '' }}>
                                                                    {{ $rol->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="status_id" class="block text-xs font-medium text-gray-700 mb-2">Estado del Alumno</label>
                                                        <select name="status_id" id="status_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos los estados</option>
                                                            @foreach($filterOptions['status'] ?? [] as $status)
                                                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                                                    {{ $status->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="numero_control" class="block text-xs font-medium text-gray-700 mb-2">Número de Control</label>
                                                        <input type="text" name="numero_control" id="numero_control" value="{{ request('numero_control') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="Número de control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    Información Académica
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                    <div>
                                                        <label for="carrera_nombre" class="block text-xs font-medium text-gray-700 mb-2">Carrera</label>
                                                        <select name="carrera_nombre" id="carrera_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
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
                                                        <select name="semestre_nombre" id="semestre_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
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
                                                        <select name="grupo_letra" id="grupo_letra" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos los grupos</option>
                                                            @foreach($filterOptions['grupos'] ?? [] as $grupo)
                                                                @php
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
                                                                    {{ $displayValue }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="modalidad_nombre" class="block text-xs font-medium text-gray-700 mb-2">Modalidad</label>
                                                        <select name="modalidad_nombre" id="modalidad_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todas las modalidades</option>
                                                            @foreach($filterOptions['modalidades'] ?? [] as $modalidad)
                                                                <option value="{{ $modalidad->nombre }}" {{ request('modalidad_nombre') == $modalidad->nombre ? 'selected' : '' }}>
                                                                    {{ $modalidad->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="meses_servicio" class="block text-xs font-medium text-gray-700 mb-2">Meses de Servicio</label>
                                                        <select name="meses_servicio" id="meses_servicio" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos</option>
                                                            @for($i = 1; $i <= 24; $i++)
                                                                <option value="{{ $i }}" {{ request('meses_servicio') == $i ? 'selected' : '' }}>
                                                                    {{ $i }} {{ $i == 1 ? 'mes' : 'meses' }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

<!-- Información de Servicio Social -->
                                            <div class="space-y-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    Servicio Social
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                    <div>
                                                        <label for="institucion_nombre" class="block text-xs font-medium text-gray-700 mb-2">Institución</label>
                                                        <select name="institucion_nombre" id="institucion_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todas las instituciones</option>
                                                            @foreach($filterOptions['instituciones'] ?? [] as $institucion)
                                                                <option value="{{ $institucion->nombre }}" {{ request('institucion_nombre') == $institucion->nombre ? 'selected' : '' }}>
                                                                    {{ $institucion->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="titulo_nombre" class="block text-xs font-medium text-gray-700 mb-2">Título del Encargado</label>
                                                        <select name="titulo_nombre" id="titulo_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos los títulos</option>
                                                            @foreach($filterOptions['titulos'] ?? [] as $titulo)
                                                                <option value="{{ $titulo->titulo }}" {{ request('titulo_nombre') == $titulo->titulo ? 'selected' : '' }}>
                                                                    {{ $titulo->titulo }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="metodo_nombre" class="block text-xs font-medium text-gray-700 mb-2">Método de Servicio</label>
                                                        <select name="metodo_nombre" id="metodo_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos los métodos</option>
                                                            @foreach($filterOptions['metodos'] ?? [] as $metodo)
                                                                <option value="{{ $metodo->metodo }}" {{ request('metodo_nombre') == $metodo->metodo ? 'selected' : '' }}>
                                                                    {{ $metodo->metodo }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="tipo_programa_nombre" class="block text-xs font-medium text-gray-700 mb-2">Tipo de Programa</label>
                                                        <select name="tipo_programa_nombre" id="tipo_programa_nombre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                            <option value="">Todos los tipos</option>
                                                            @foreach($filterOptions['tipos_programa'] ?? [] as $tipo)
                                                                <option value="{{ $tipo->tipo }}" {{ request('tipo_programa_nombre') == $tipo->tipo ? 'selected' : '' }}>
                                                                    {{ $tipo->tipo }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="nombre_programa" class="block text-xs font-medium text-gray-700 mb-2">Nombre del Programa</label>
                                                        <input type="text" name="nombre_programa" id="nombre_programa" value="{{ request('nombre_programa') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="Buscar programa">
                                                    </div>

                                                    <div>
                                                        <label for="encargado_nombre" class="block text-xs font-medium text-gray-700 mb-2">Nombre del Encargado</label>
                                                        <input type="text" name="encargado_nombre" id="encargado_nombre" value="{{ request('encargado_nombre') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                                               placeholder="Buscar encargado">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtros de Fecha -->
                                            <div class="space-y-4">
                                                <h4 class="text-sm font-semibold text-gray-800 flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    Fechas
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="fecha_registro_desde" class="block text-xs font-medium text-gray-700 mb-2">Registrado desde</label>
                                                        <input type="date" name="fecha_registro_desde" id="fecha_registro_desde" value="{{ request('fecha_registro_desde') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="fecha_registro_hasta" class="block text-xs font-medium text-gray-700 mb-2">Registrado hasta</label>
                                                        <input type="date" name="fecha_registro_hasta" id="fecha_registro_hasta" value="{{ request('fecha_registro_hasta') }}" 
                                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botones de acción -->
                                            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                                                <button type="submit" class="flex-1 sm:flex-none px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                                    Aplicar Filtros
                                                </button>
                                                <a href="{{ route('dashboard', ['table' => 'alumno']) }}" 
                                                   class="flex-1 sm:flex-none px-6 py-2 text-center bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                                    Limpiar Filtros
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Botones de exportación con mejor estilo -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('export.excel', ['table' => 'alumno'] + request()->query()) }}" 
                                       class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-lg font-semibold text-white text-sm shadow-md hover:from-green-600 hover:to-green-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Exportar</span>
                                        Excel
                                    </a>
                                    
                                    <a href="{{ route('export.pdf', ['table' => 'alumno'] + request()->query()) }}" 
                                       class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-500 to-red-600 border border-transparent rounded-lg font-semibold text-white text-sm shadow-md hover:from-red-600 hover:to-red-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:-translate-y-0.5 transition-all duration-200 ease-in-out">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Exportar</span>
                                        PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Barra de herramientas universal (EN TODAS LAS TABLAS EXCEPTO ALUMNO) -->
            @if($selectedTable && $rows && $rows->count() > 0 && $selectedTable !== 'alumno')
                <div class="bg-gray-50 border-b border-gray-200 p-4">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <!-- Búsqueda rápida -->
                        <div class="flex-1 max-w-md">
                            <div class="relative flex">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="searchInput" 
                                       value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                       placeholder="Buscar en {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}...">
                                <button onclick="performSearch()" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 border border-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <span class="sr-only">Buscar</span>
                                </button>
                            </div>
                        </div>

                        <!-- Controles de paginación y registros por página -->
                        <div class="flex items-center space-x-4">
                            <!-- Selector de registros por página -->
                            <div class="flex items-center space-x-2">
                                <label for="perPage" class="text-sm font-medium text-gray-700">Mostrar:</label>
                                <select id="perPage" class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    <option value="250" {{ request('per_page') == 250 ? 'selected' : '' }}>250</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label for="goToPage" class="text-sm font-medium text-gray-700">Ir a página:</label>
                                <input type="number" 
                                       id="goToPage" 
                                       min="1" 
                                       max="{{ $rows->lastPage() }}" 
                                       value="{{ $rows->currentPage() }}"
                                       class="w-16 border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button onclick="goToSpecificPage()" 
                                        class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                    Ir
                                </button>
                            </div>
                            <button onclick="clearSearch()" 
                                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Tabla de datos -->
            @php
                if ($selectedTable && $rows && method_exists($rows, 'getCollection')) {
                    $rows->setCollection(
                        $rows->getCollection()
                            ->sortByDesc(function ($item) {
                                if (is_array($item)) {
                                    return $item['id'] ?? ($item['created_at'] ?? 0);
                                }
                                return $item->id ?? ($item->created_at ?? 0);
                            })
                            ->values()
                    );
                }
            @endphp
            
            <!-- Scroll superior sincronizado -->
            <div id="top-scroll" class="overflow-x-auto">
                <div id="top-scroll-content" class="h-4"></div>
            </div>
            <div id="bottom-scroll" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200">
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
                                        @elseif($selectedTable === 'programa_servicio_social')
                                            {{-- AÑADIR ESTA SECCIÓN ESPECÍFICA PARA PROGRAMA_SERVICIO_SOCIAL --}}
                                            @if($column === 'alumno_id')
                                                @php
                                                    $alumno = DB::table('alumno')->where('id', $value)->first();
                                                @endphp
                                                {{ $alumno ? $alumno->nombre . ' ' . $alumno->apellido_p . ' ' . $alumno->apellido_m : $value }}
                                            @elseif($column === 'instituciones_id')
                                                @php
                                                    $institucion = DB::table('instituciones')->where('id', $value)->first();
                                                @endphp
                                                {{ $institucion ? $institucion->nombre : $value }}
                                            @elseif($column === 'titulos_id')
                                                @php
                                                    $titulo = DB::table('titulos')->where('id', $value)->first();
                                                @endphp
                                                {{ $titulo ? $titulo->titulo : $value }}
                                            @elseif($column === 'metodo_servicio_id')
                                                @php
                                                    $metodo = DB::table('metodo_servicio')->where('id', $value)->first();
                                                @endphp
                                                {{ $metodo ? $metodo->metodo : $value }}
                                            @elseif($column === 'tipos_programa_id')
                                                @php
                                                    $tipo = DB::table('tipos_programa')->where('id', $value)->first();
                                                @endphp
                                                {{ $tipo ? $tipo->tipo : $value }}
                                            @elseif($column === 'status_id')
                                                @php
                                                    $status = DB::table('status')->where('id', $value)->first();
                                                @endphp
                                                {{ $status ? $status->tipo : $value }}
                                            @elseif(preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
                                                {{ date('d/m/Y', strtotime($value)) }}
                                            @elseif(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value))
                                                {{ date('d/m/Y H:i', strtotime($value)) }}
                                            @elseif($column === 'telefono_institucion')
                                                <a href="tel:{{ $value }}" class="text-blue-600 hover:text-blue-800">{{ $value }}</a>
                                            @elseif(strlen($value) > 50)
                                                <span title="{{ $value }}" class="cursor-help">{{ substr($value, 0, 50) }}...</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        @elseif($column === 'usuario_id' && $selectedTable === 'formatos')
                                            @php
                                                $usuario = DB::table('users')->where('id', $value)->first();
                                            @endphp
                                            {{ $usuario && isset($usuario->name) ? $usuario->name : $value }}
                                        @elseif($column === 'alumno_id')
                                            @php
                                                $alumno = DB::table('alumno')->where('id', $value)->first();
                                            @endphp
                                            {{ $alumno && isset($alumno->nombre) && isset($alumno->apellido_p) ? $alumno->nombre . ' ' . $alumno->apellido_p : $value }}
                                        @elseif($column === 'instituciones_id')
                                            @php
                                                $institucion = DB::table('instituciones')->where('id', $value)->first();
                                            @endphp
                                            {{ $institucion && isset($institucion->nombre) ? $institucion->nombre : $value }}
                                        @elseif($column === 'titulos_id')
                                            @php
                                                try {
                                                    $titulo = DB::table('titulos')->where('id', $value)->first();
                                                    echo $titulo && property_exists($titulo, 'nombre') ? $titulo->nombre : ($titulo && property_exists($titulo, 'titulo') ? $titulo->titulo : $value);
                                                } catch(Exception $e) {
                                                    echo $value;
                                                }
                                            @endphp
                                        @elseif($column === 'metodo_servicio_id')
                                            @php
                                                try {
                                                    $metodo = DB::table('metodo_servicio')->where('id', $value)->first();
                                                    echo $metodo && property_exists($metodo, 'nombre') ? $metodo->nombre : ($metodo && property_exists($metodo, 'metodo') ? $metodo->metodo : $value);
                                                } catch(Exception $e) {
                                                    echo $value;
                                                }
                                            @endphp
                                        @elseif($column === 'tipos_programa_id')
                                            @php
                                                try {
                                                    $tipo = DB::table('tipos_programa')->where('id', $value)->first();
                                                    echo $tipo && property_exists($tipo, 'nombre') ? $tipo->nombre : ($tipo && property_exists($tipo, 'tipo') ? $tipo->tipo : $value);
                                                } catch(Exception $e) {
                                                    echo $value;
                                                }
                                            @endphp
                                        @elseif($column === 'status_id')
                                            @php
                                                try {
                                                    // Obtener el registro completo para verificar fechas
                                                    $currentRow = null;
                                                    if ($selectedTable === 'programa_servicio_social') {
                                                        $currentRow = DB::table('programa_servicio_social')->where('id', $row->id)->first();
                                                    }
                                                    
                                                    // Determinar status automático basado en fechas
                                                    $autoStatus = null;
                                                    if ($currentRow && isset($currentRow->fecha_final)) {
                                                        $fechaFinal = \Carbon\Carbon::parse($currentRow->fecha_final);
                                                        $fechaInicio = \Carbon\Carbon::parse($currentRow->fecha_inicio);
                                                        $hoy = \Carbon\Carbon::now();
                                                        
                                                        if ($hoy->gt($fechaFinal)) {
                                                            $autoStatus = 4; // Finalizado
                                                        } elseif ($hoy->gte($fechaInicio) && $hoy->lte($fechaFinal)) {
                                                            $autoStatus = 3; // En proceso
                                                        } else {
                                                            $autoStatus = 1; // Activo (aún no inicia)
                                                        }
                                                        
                                                        // Actualizar automáticamente si es diferente
                                                        if ($autoStatus != $value) {
                                                            DB::table('programa_servicio_social')
                                                                ->where('id', $row->id)
                                                                ->update(['status_id' => $autoStatus]);
                                                            $value = $autoStatus;
                                                        }
                                                    }
                                                    
                                                    $status = DB::table('status')->where('id', $value)->first();
                                                    echo $status && property_exists($status, 'tipo') ? $status->tipo : $value;
                                                } catch(Exception $e) {
                                                    echo $value;
                                                }
                                            @endphp
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

                                    <!-- Lógica existente para formatos -->
                                        <a href="{{ route('formatos.upload') }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors mr-2">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>
                                    @elseif($selectedTable === 'alumno')
                                        <!-- Botones de descarga de documentos -->
                                        <div class="flex flex-col sm:flex-row items-end justify-end gap-1 mb-2">
                                            <a href="{{ route('formatos.download', ['id' => $row->id, 'tipo' => 'word']) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded hover:bg-amber-200 transition-colors"
                                               title="Descargar Carta de Presentación">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Carta
                                            </a>
                                            <a href="{{ route('formatos.download', ['id' => $row->id, 'tipo' => 'reporte']) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded hover:bg-green-200 transition-colors"
                                               title="Descargar Reporte Mensual">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Reporte
                                            </a>
                                            <a href="{{ route('formatos.download', ['id' => $row->id, 'tipo' => 'reporte_final']) }}" 
                                               class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded hover:bg-purple-200 transition-colors"
                                               title="Descargar Reporte Final">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                </svg>
                                                Final
                                            </a>
                                        </div>
                                        
                                        <!-- Botón Editar -->
                                        <a href="{{ route('record.edit', ['table' => 'alumno', 'id' => $row->id]) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors mr-2">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>

                                        <!-- Botón Cancelar - Solo mostrar si el alumno está activo (status = Activo) -->
                                       @if($row->status === 'Activo' || (isset($row->status_id) && $row->status_id == 1))
                                         <button onclick="openCancelModal('{{ $row->id }}', '{{ $selectedTable }}')" 
                                          class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                             Eliminar
                                        </button>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 text-xs font-medium rounded-lg">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                                </svg>
                                                Cancelado
                                            </span>
                                        @endif
                                    @else
                                        <!-- Botón editar normal para otras tablas -->
                                        <a href="{{ route('record.edit', ['table' => $selectedTable, 'id' => $row->id]) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg hover:bg-amber-200 transition-colors mr-2">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar
                                        </a>

                                        <!-- Botón eliminar normal para otras tablas -->
                                        <button onclick="openDeleteModal('{{ $row->id }}', '{{ $selectedTable }}')" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-200 text-red-800 text-xs font-medium rounded-lg hover:bg-red-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-lg font-medium">No se encontraron registros</p>
                                    <p class="text-sm">No hay registros en esta tabla.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            @if($rows->hasPages())
                <div class="px-6 py-4 border-t border-gray-300 bg-gray-100">
                    {{ $rows->links() }}
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

<style>
/* Estilos adicionales para los botones de descarga */
.download-buttons {
    min-width: 200px;
}

.download-buttons a {
    white-space: nowrap;
}

/* Indicador visual para alumnos cancelados */
tr:has(.bg-gray-100) {
    background-color: #f9fafb;
    opacity: 0.8;
}

/* Mejorar responsive de botones */
@media (max-width: 640px) {
    .download-buttons {
        min-width: 150px;
    }
    
    .download-buttons a {
        font-size: 10px;
        padding: 4px 8px;
    }
}

/* Fuerza absoluta para todos los elementos de paginación */
* [aria-label="Pagination Navigation"] *,
* nav[role="navigation"] *,
* .pagination *,
* [class*="page"] * {
    background-color: #F3F4F6 !important;
    border-color: #D1D5DB !important;
    color: #374151 !important;
}

/* Página activa con máxima prioridad */
*[aria-current="page"] {
    background-color: #DBEAFE !important;
    border-color: #93C5FD !important;
    color: #1E40AF !important;
}

/* Scrollbars personalizados (horizontal) */
#bottom-scroll, #top-scroll {
  /* Firefox */
  scrollbar-color: #60a5fa #e5e7eb; /* thumb | track */
  scrollbar-width: thin;
}
/* WebKit (Chrome/Edge/Safari) */
#bottom-scroll::-webkit-scrollbar,
#top-scroll::-webkit-scrollbar {
  height: 10px;              /* barra horizontal */
  background-color: #e5e7eb; /* track */
}
#bottom-scroll::-webkit-scrollbar-thumb,
#top-scroll::-webkit-scrollbar-thumb {
  background-color: #60a5fa; /* thumb */
  border-radius: 8px;
  border: 2px solid #e5e7eb;
}
#bottom-scroll::-webkit-scrollbar-thumb:hover,
#top-scroll::-webkit-scrollbar-thumb:hover {
  background-color: #3b82f6;
}
</style>
<script>
// Variables globales
let deleteRecordId = null;
let deleteTableName = null;
let deleteCountdownTimer = null;
let cancelRecordId = null;
let cancelTableName = null;
let cancelCountdownTimer = null;

// URLs generadas por Laravel
const baseDeleteUrl = '{{ route("record.delete", ["table" => "PLACEHOLDER_TABLE", "id" => "PLACEHOLDER_ID"]) }}'
    .replace('PLACEHOLDER_TABLE', '')
    .replace('PLACEHOLDER_ID', '');

// Función para abrir modal de eliminación
function openDeleteModal(id, table) {
    deleteRecordId = id;
    deleteTableName = table;
    
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
    
    const confirmBtn = document.getElementById('confirmDelete');
    const countdownText = document.getElementById('countdown');
    
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        confirmBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
    }
    
    let countdown = 3;
    
    if (countdownText) {
        deleteCountdownTimer = setInterval(() => {
            countdownText.textContent = `(${countdown}s)`;
            countdown--;
            
            if (countdown < 0) {
                clearInterval(deleteCountdownTimer);
                countdownText.textContent = '';
                
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    confirmBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                }
            }
        }, 1000);
        
        countdownText.textContent = `(${countdown}s)`;
    }
}

// Función para cerrar modal de eliminación
function closeDeleteModal() {
    if (deleteCountdownTimer) {
        clearInterval(deleteCountdownTimer);
        deleteCountdownTimer = null;
    }
    
    deleteRecordId = null;
    deleteTableName = null;
    
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    
    const countdownText = document.getElementById('countdown');
    if (countdownText) {
        countdownText.textContent = '';
    }
    
    const confirmBtn = document.getElementById('confirmDelete');
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        confirmBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
    }
}

// Función para confirmar eliminación - VERSIÓN MEJORADA
function confirmDelete() {
    if (!deleteRecordId || !deleteTableName) {
        console.error('Faltan datos para eliminar:', { deleteRecordId, deleteTableName });
        return;
    }
    
    console.log('Eliminando registro:', { id: deleteRecordId, table: deleteTableName });
    
    const form = document.createElement('form');
    form.method = 'POST';
    
    // ⚠️ CORREGIR URL - AGREGAR /admin:
    form.action = `/admin/record/delete/${deleteTableName}/${deleteRecordId}`;
    
    console.log('URL de eliminación:', form.action);
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    
    console.log('Enviando formulario de eliminación...', form);
    form.submit();
}

// Función para abrir modal de cancelación
function openCancelModal(id, table) {
    cancelRecordId = id;
    cancelTableName = table;
    
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
    
    const confirmBtn = document.getElementById('confirmCancelBtn');
    const countdownText = document.getElementById('cancelCountdown');
    
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        confirmBtn.classList.remove('bg-orange-600', 'hover:bg-orange-700');
    }
    
    let countdown = 3;
    
    if (countdownText) {
        cancelCountdownTimer = setInterval(() => {
            countdownText.textContent = `(${countdown}s)`;
            countdown--;
            
            if (countdown < 0) {
                clearInterval(cancelCountdownTimer);
                countdownText.textContent = '';
                
                if (confirmBtn) {
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    confirmBtn.classList.add('bg-orange-600', 'hover:bg-orange-700');
                }
            }
        }, 1000);
        
        countdownText.textContent = `(${countdown}s)`;
    }
}

// Función para cerrar modal de cancelación
function closeCancelModal() {
    if (cancelCountdownTimer) {
        clearInterval(cancelCountdownTimer);
               cancelCountdownTimer = null;
    }
    
    cancelRecordId = null;
    cancelTableName = null;
    
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    
    const countdownText = document.getElementById('cancelCountdown');
    if (countdownText) {
        countdownText.textContent = '';
    }
    
    const confirmBtn = document.getElementById('confirmCancelBtn');
    if (confirmBtn) {
        confirmBtn.disabled = true;
        confirmBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        confirmBtn.classList.remove('bg-orange-600', 'hover:bg-orange-700');
    }
}

// Función para confirmar cancelación
function confirmCancel() {
    if (!cancelRecordId || !cancelTableName) {
        console.error('Faltan datos para cancelar:', { cancelRecordId, cancelTableName });
        return;
    }
    
    console.log('Cancelando alumno:', { id: cancelRecordId, table: cancelTableName });
    
    const form = document.createElement('form');
    form.method = 'POST';
    
    // ⚠️ CORREGIR URL - AGREGAR /admin:
    form.action = `/admin/alumno/cancelar/${cancelRecordId}`;
    
    console.log('URL de cancelación:', form.action);
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        csrfToken.value = metaToken.getAttribute('content');
    }
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    
    console.log('Enviando formulario de cancelación...', {
        action: form.action,
        method: form.method,
        inputs: form.querySelectorAll('input')
    });
    
    form.submit();
}

// Función para ir a página específica
function goToSpecificPage() {
    const pageInput = document.getElementById('goToPage');
    const page = parseInt(pageInput.value);
    const maxPage = parseInt(pageInput.max);
    
    if (page && page >= 1 && page <= maxPage) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', page);
        window.location.href = currentUrl.toString();
    } else {
        alert(`Por favor ingrese un número entre 1 y ${maxPage}`);
        pageInput.focus();
    }
}

// Función para cambiar registros por página
function changePerPage() {
    const perPageSelect = document.getElementById('perPage');
    const perPage = perPageSelect.value;
    
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Resetear a página 1
    window.location.href = currentUrl.toString();
}

// Función para limpiar búsqueda
function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Redirigir a la URL sin parámetros de búsqueda
    const currentUrl = new URL(window.location.href);
    const table = currentUrl.searchParams.get('table');
    
    // Mantener solo el parámetro table
    const newUrl = new URL(currentUrl.origin + currentUrl.pathname);
    if (table) {
        newUrl.searchParams.set('table', table);
    }
    
    window.location.href = newUrl.toString();
}

// Función de búsqueda SOLO con botón IR
function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    // ELIMINAR el event listener de 'input' para que no busque automáticamente
    // Solo mantener el event listener para Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
}

// Nueva función para realizar búsqueda
function performSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    const searchTerm = searchInput.value.trim();
    const currentUrl = new URL(window.location.href);
    
    if (searchTerm) {
        currentUrl.searchParams.set('search', searchTerm);
    } else {
        currentUrl.searchParams.delete('search');
    }
    
    currentUrl.searchParams.delete('page'); // Resetear a página 1
    window.location.href = currentUrl.toString();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Botones de eliminación
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', confirmDelete);
    }
    
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', closeDeleteModal);
    }
    
    // Botones de cancelación
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', confirmCancel);
    }
    
    const cancelCancelBtn = document.getElementById('cancelCancelBtn');
    if (cancelCancelBtn) {
        cancelCancelBtn.addEventListener('click', closeCancelModal);
    }
    
    // Cerrar modales al hacer clic en el fondo
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }
    
    const cancelModal = document.getElementById('cancelModal');
    if (cancelModal) {
        cancelModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    }
    
    // Cerrar modales con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (deleteModal && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
            if (cancelModal && !cancelModal.classList.contains('hidden')) {
                closeCancelModal();
            }
        }
    });
    
    // Configurar búsqueda
    setupSearch();
    
    // Event listener para cambio de registros por página
    const perPageSelect = document.getElementById('perPage');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', changePerPage);
    }
    
    // Event listener para enter en el input de página
    const goToPageInput = document.getElementById('goToPage');
    if (goToPageInput) {
        goToPageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                goToSpecificPage();
            }
        });
    }
    
    // Scroll superior sincronizado con el contenedor inferior
    const topScroll = document.getElementById('top-scroll');
    const topScrollContent = document.getElementById('top-scroll-content');
    const bottomScroll = document.getElementById('bottom-scroll');
    const tableEl = bottomScroll ? bottomScroll.querySelector('table') : null;

    function syncWidths() {
        if (tableEl && topScrollContent) {
            topScrollContent.style.width = tableEl.scrollWidth + 'px';
        }
    }

    if (topScroll && bottomScroll && tableEl && topScrollContent) {
        // Sincronizar desplazamiento
        let syncing = false;
        topScroll.addEventListener('scroll', () => {
            if (syncing) return;
            syncing = true;
            bottomScroll.scrollLeft = topScroll.scrollLeft;
            syncing = false;
        });
        bottomScroll.addEventListener('scroll', () => {
            if (syncing) return;
            syncing = true;
            topScroll.scrollLeft = bottomScroll.scrollLeft;
            syncing = false;
        });

        // Ajustar ancho del "dummy" del scroll superior
        syncWidths();
        // Observar cambios de tamaño del contenido de la tabla
        try {
            const ro = new ResizeObserver(syncWidths);
            ro.observe(tableEl);
            window.addEventListener('resize', syncWidths);
        } catch (e) {
            // Fallback simple
            window.addEventListener('resize', syncWidths);
            setTimeout(syncWidths, 250);
        }
    }
 });
</script>

<!-- Alpine.js para el popover -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection