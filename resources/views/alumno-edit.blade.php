@extends('layouts.blaze')

@section('title', 'Editar Alumno')
@section('page-title', 'Editar Información del Alumno')
@section('page-description', 'Modifica toda la información del alumno incluyendo datos académicos y de servicio social')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('dashboard', ['table' => 'alumno']) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        Alumnos
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500">Editar</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header con título correcto -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200 p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Registro Completo de Alumno
                </h1>
                <p class="text-gray-600 mt-1">
                    Complete toda la información del alumno incluyendo datos académicos y de servicio social
                </p>
            </div>
        </div>
    </div>

    <!-- Formulario con ACTION CORRECTO -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="{{ route('alumno.update', $alumno->id) }}" method="POST" enctype="multipart/form-data" id="editAlumnoForm">
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

            <div class="p-6 space-y-8">
                <!-- SECCIÓN 1: INFORMACIÓN PERSONAL DEL ALUMNO -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                        <h3 class="text-lg font-semibold text-gray-900">Información Personal del Alumno</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre(s) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $alumno->nombre) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Nombre completo">
                        </div>

                        <!-- Apellido Paterno -->
                        <div>
                            <label for="apellido_p" class="block text-sm font-medium text-gray-700 mb-2">
                                Apellido Paterno <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellido_p" id="apellido_p" value="{{ old('apellido_p', $alumno->apellido_p) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Apellido paterno">
                        </div>

                        <!-- Apellido Materno -->
                        <div>
                            <label for="apellido_m" class="block text-sm font-medium text-gray-700 mb-2">
                                Apellido Materno <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellido_m" id="apellido_m" value="{{ old('apellido_m', $alumno->apellido_m) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="Apellido materno">
                        </div>

                        <!-- Correo Institucional -->
                        <div>
                            <label for="correo_institucional" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Institucional <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="correo_institucional" id="correo_institucional" value="{{ old('correo_institucional', $alumno->correo_institucional) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="correo@cbta256.edu.mx">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="telefono" id="telefono" value="{{ old('telefono', $alumno->telefono) }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="4271234567" maxlength="10">
                        </div>

                        <!-- Código Postal -->
                        <div>
                            <label for="ubicacion_cp" class="block text-sm font-medium text-gray-700 mb-2">
                                Código Postal <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ubicacion_cp" id="ubicacion_cp" value="{{ old('ubicacion_cp', $ubicacion->cp ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   placeholder="12345" maxlength="5">
                        </div>

                        <!-- Edad -->
                        <div>
                            <label for="edad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Edad <span class="text-red-500">*</span>
                            </label>
                            <select name="edad_id" id="edad_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Seleccione una opción</option>
                                @foreach($foreignOptions['edad_id'] as $edad)
                                    <option value="{{ $edad->id }}" {{ (old('edad_id', $alumno->edad_id) == $edad->id) ? 'selected' : '' }}>
                                        {{ $edad->edades }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label for="sexo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sexo <span class="text-red-500">*</span>
                            </label>
                            <select name="sexo_id" id="sexo_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Seleccione una opción</option>
                                @foreach($foreignOptions['sexo_id'] as $sexo)
                                    <option value="{{ $sexo->id }}" {{ (old('sexo_id', $alumno->sexo_id) == $sexo->id) ? 'selected' : '' }}>
                                        {{ $sexo->tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rol -->
                        <div>
                            <label for="rol_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select name="rol_id" id="rol_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Seleccione una opción</option>
                                @foreach($foreignOptions['rol_id'] as $rol)
                                    <option value="{{ $rol->id }}" {{ (old('rol_id', $alumno->rol_id) == $rol->id) ? 'selected' : '' }}>
                                        {{ $rol->tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: INFORMACIÓN DE UBICACIÓN -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                        <h3 class="text-lg font-semibold text-gray-900">Información de Ubicación</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Localidad -->
                        <div>
                            <label for="ubicacion_localidad" class="block text-sm font-medium text-gray-700 mb-2">
                                Localidad <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ubicacion_localidad" id="ubicacion_localidad" value="{{ old('ubicacion_localidad', $ubicacion->localidad ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Nombre de la localidad">
                        </div>

                        <!-- Estado -->
                        <div>
                            <label for="ubicacion_estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select name="ubicacion_estado_id" id="ubicacion_estado_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                <option value="">Seleccione un estado</option>
                                @foreach($foreignOptions['estado_id'] as $estado)
                                    @php
                                        $currentEstadoId = null;
                                        if($ubicacion && $ubicacion->municipios_id) {
                                            $municipio = DB::table('municipios')->where('id', $ubicacion->municipios_id)->first();
                                            $currentEstadoId = $municipio ? $municipio->estado_id : null;
                                        }
                                    @endphp
                                    <option value="{{ $estado->id }}" {{ (old('ubicacion_estado_id', $currentEstadoId) == $estado->id) ? 'selected' : '' }}>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Municipio -->
                        <div>
                            <label for="ubicacion_municipios_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Municipio <span class="text-red-500">*</span>
                            </label>
                            <select name="ubicacion_municipios_id" id="ubicacion_municipios_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                <option value="">Primero seleccione un estado</option>
                                @foreach($foreignOptions['municipios_id'] as $municipio)
                                    <option value="{{ $municipio->id }}" {{ (old('ubicacion_municipios_id', $ubicacion->municipios_id ?? '') == $municipio->id) ? 'selected' : '' }}>
                                        {{ $municipio->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Código Postal (Ubicación) duplicado -->
                        <div>
                            <label for="ubicacion_cp_duplicate" class="block text-sm font-medium text-gray-700 mb-2">
                                Código Postal (Ubicación) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ubicacion_cp_duplicate" id="ubicacion_cp_duplicate" value="{{ old('ubicacion_cp', $ubicacion->cp ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Ej: 12345" maxlength="5" readonly>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 3: INFORMACIÓN ACADÉMICA -->
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                        <h3 class="text-lg font-semibold text-gray-900">Información Académica</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Número de Control -->
                        <div>
                            <label for="escolaridad_numero_control" class="block text-sm font-medium text-gray-700 mb-2">
                                Número de Control <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="escolaridad_numero_control" id="escolaridad_numero_control" value="{{ old('escolaridad_numero_control', $escolaridad->numero_control ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="12345678" maxlength="14">
                        </div>

                        <!-- Meses de Servicio -->
                        <div>
                            <label for="escolaridad_meses_servicio" class="block text-sm font-medium text-gray-700 mb-2">
                                Meses de Servicio <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="escolaridad_meses_servicio" id="escolaridad_meses_servicio" value="{{ old('escolaridad_meses_servicio', $escolaridad->meses_servicio ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="6" min="1" max="12">
                        </div>

                        <!-- Carrera -->
                        <div>
                            <label for="escolaridad_carreras_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Carrera <span class="text-red-500">*</span>
                            </label>
                            <select name="escolaridad_carreras_id" id="escolaridad_carreras_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="">Seleccione una carrera</option>
                                @foreach($foreignOptions['carreras_id'] as $carrera)
                                    <option value="{{ $carrera->id }}" {{ (old('escolaridad_carreras_id', $escolaridad->carreras_id ?? '') == $carrera->id) ? 'selected' : '' }}>
                                        {{ $carrera->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Semestre -->
                        <div>
                            <label for="escolaridad_semestres_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Semestre <span class="text-red-500">*</span>
                            </label>
                            <select name="escolaridad_semestres_id" id="escolaridad_semestres_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="">Seleccione un semestre</option>
                                @foreach($foreignOptions['semestres_id'] as $semestre)
                                    <option value="{{ $semestre->id }}" {{ (old('escolaridad_semestres_id', $escolaridad->semestres_id ?? '') == $semestre->id) ? 'selected' : '' }}>
                                        {{ $semestre->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Grupo -->
                        <div>
                            <label for="escolaridad_grupos_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Grupo <span class="text-red-500">*</span>
                            </label>
                            <select name="escolaridad_grupos_id" id="escolaridad_grupos_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="">Seleccione un grupo</option>
                                @foreach($foreignOptions['grupos_id'] as $grupo)
                                    <option value="{{ $grupo->id }}" {{ (old('escolaridad_grupos_id', $escolaridad->grupos_id ?? '') == $grupo->id) ? 'selected' : '' }}>
                                        {{ $grupo->letra }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Modalidad -->
                        <div>
                            <label for="escolaridad_modalidad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Modalidad <span class="text-red-500">*</span>
                            </label>
                            <select name="escolaridad_modalidad_id" id="escolaridad_modalidad_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="">Seleccione una modalidad</option>
                                @foreach($foreignOptions['modalidad_id'] as $modalidad)
                                    <option value="{{ $modalidad->id }}" {{ (old('escolaridad_modalidad_id', $escolaridad->modalidad_id ?? '') == $modalidad->id) ? 'selected' : '' }}>
                                        {{ $modalidad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 4: PROGRAMA DE SERVICIO SOCIAL -->
                <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</div>
                        <h3 class="text-lg font-semibold text-gray-900">Programa de Servicio Social</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Nombre del Programa -->
                        <div>
                            <label for="programa_nombre_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Programa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="programa_nombre_programa" id="programa_nombre_programa" value="{{ old('programa_nombre_programa', $programa->nombre_programa ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                   placeholder="Nombre del programa de servicio social">
                        </div>

                        <!-- Nombre del Encargado -->
                        <div>
                            <label for="programa_encargado_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del Encargado <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="programa_encargado_nombre" id="programa_encargado_nombre" value="{{ old('programa_encargado_nombre', $programa->encargado_nombre ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                   placeholder="Nombre completo del encargado">
                        </div>

                        <!-- Fecha de Inicio -->
                        <div>
                            <label for="programa_fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="programa_fecha_inicio" id="programa_fecha_inicio" value="{{ old('programa_fecha_inicio', $programa->fecha_inicio ? date('Y-m-d', strtotime($programa->fecha_inicio)) : '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>

                        <!-- Fecha Final -->
                        <div>
                            <label for="programa_fecha_final" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha Final <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="programa_fecha_final" id="programa_fecha_final" value="{{ old('programa_fecha_final', $programa->fecha_final ? date('Y-m-d', strtotime($programa->fecha_final)) : '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        </div>

                        <!-- Institución -->
                        <div>
                            <label for="programa_instituciones_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Institución <span class="text-red-500">*</span>
                            </label>
                            <select name="programa_instituciones_id" id="programa_instituciones_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Seleccione una institución</option>
                                @foreach($foreignOptions['instituciones_id'] as $institucion)
                                    <option value="{{ $institucion->id }}" {{ (old('programa_instituciones_id', $programa->instituciones_id ?? '') == $institucion->id) ? 'selected' : '' }}>
                                        {{ $institucion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Título -->
                        <div>
                            <label for="programa_titulos_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <select name="programa_titulos_id" id="programa_titulos_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Seleccione un título</option>
                                @foreach($foreignOptions['titulos_id'] as $titulo)
                                    <option value="{{ $titulo->id }}" {{ (old('programa_titulos_id', $programa->titulos_id ?? '') == $titulo->id) ? 'selected' : '' }}>
                                        {{ $titulo->titulo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Puesto del Encargado -->
                        <div>
                            <label for="programa_puesto_encargado" class="block text-sm font-medium text-gray-700 mb-2">
                                Puesto del Encargado <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="programa_puesto_encargado" id="programa_puesto_encargado" value="{{ old('programa_puesto_encargado', $programa->puesto_encargado ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                   placeholder="Cargo o puesto del encargado">
                        </div>

                        <!-- Método de Servicio -->
                        <div>
                            <label for="programa_metodo_servicio_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Método de Servicio <span class="text-red-500">*</span>
                            </label>
                            <select name="programa_metodo_servicio_id" id="programa_metodo_servicio_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Seleccione un método</option>
                                @foreach($foreignOptions['metodo_servicio_id'] as $metodo)
                                    <option value="{{ $metodo->id }}" {{ (old('programa_metodo_servicio_id', $programa->metodo_servicio_id ?? '') == $metodo->id) ? 'selected' : '' }}>
                                        {{ $metodo->metodo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tipo de Programa -->
                        <div>
                            <label for="programa_tipos_programa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Programa <span class="text-red-500">*</span>
                            </label>
                            <select name="programa_tipos_programa_id" id="programa_tipos_programa_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                                <option value="">Seleccione un tipo</option>
                                @foreach($foreignOptions['tipos_programa_id'] as $tipo)
                                    <option value="{{ $tipo->id }}" {{ (old('programa_tipos_programa_id', $programa->tipos_programa_id ?? '') == $tipo->id) ? 'selected' : '' }}>
                                        {{ $tipo->tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Teléfono de la Institución -->
                        <div>
                            <label for="programa_telefono_institucion" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono de la Institución <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="programa_telefono_institucion" id="programa_telefono_institucion" value="{{ old('programa_telefono_institucion', $programa->telefono_institucion ?? '') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                   placeholder="Teléfono de contacto" maxlength="10">
                        </div>

                        <!-- Otra Institución (Opcional) -->
                        <div>
                            <label for="programa_otra_institucion" class="block text-sm font-medium text-gray-700 mb-2">
                                Otra Institución (Opcional)
                            </label>
                            <input type="text" name="programa_otra_institucion" id="programa_otra_institucion" value="{{ old('programa_otra_institucion', $programa->otra_institucion ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                   placeholder="Si no está en la lista anterior">
                        </div>

                        <!-- Otro Programa (Opcional) -->
                        <div>
                            <label for="programa_otro_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                Otro Programa (Opcional)
                            </label>
                            <input type="text" name="programa_otro_programa" id="programa_otro_programa" value="{{ old('programa_otro_programa', $programa->otro_programa ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                   placeholder="Si no está en la lista anterior">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('dashboard', ['table' => 'alumno']) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Cancelar
                </a>

                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors shadow-sm"
                        id="submitBtn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitText">Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Información del Sistema (como en la imagen) -->
    <div class="mt-6 p-6 bg-gray-50 rounded-xl border border-gray-200">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Información del Sistema</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-sm font-medium text-gray-600">ID del Registro:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $alumno->id }}</span>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-600">Fecha de Registro:</span>
                <span class="text-sm text-gray-900 ml-2">{{ $alumno->fecha_registro ? date('d/m/Y H:i', strtotime($alumno->fecha_registro)) : 'N/A' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Variable para controlar si ya se mostró la alerta
    let emailAlertShown = false;
    
    // Sincronizar códigos postales (solo para alumnos)
    @if($selectedTable === 'alumno')
        $('#ubicacion_cp').on('input', function() {
            var value = this.value;
            $('#ubicacion_cp_duplicate').val(value);
        });

        $('#ubicacion_cp_duplicate').on('input', function() {
            var value = this.value;
            $('#ubicacion_cp').val(value);
        });

        // Carga dinámica de municipios
        $('#ubicacion_estado_id').change(function(){
            var estadoId = $(this).val();
            if(estadoId){
                $.ajax({
                    url: '/municipios-por-estado/' + estadoId,
                    dataType: 'json',
                    success: function(data){
                        var opciones = '<option value="">Seleccione un municipio</option>';
                        $.each(data, function(i, municipio){
                            opciones += '<option value="'+ municipio.id +'">'+ municipio.nombre +'</option>';
                        });
                        $('#ubicacion_municipios_id').html(opciones);
                    },
                    error: function() {
                        $('#ubicacion_municipios_id').html('<option value="">Error al cargar municipios</option>');
                    }
                });
            } else {
                $('#ubicacion_municipios_id').html('<option value="">Primero seleccione un estado</option>');
            }
        });

        // Validaciones específicas para alumnos
        $('#telefono, #programa_telefono_institucion').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        $('#ubicacion_cp, #ubicacion_cp_duplicate').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
        });

        $('#escolaridad_numero_control').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 14);
        });

        // ELIMINAMOS LA VALIDACIÓN DE CORREO CON ALERT
        // $('#correo_institucional').on('blur', function() {
        //     var email = $(this).val();
        //     if (email && !email.endsWith('@cbta256.edu.mx')) {
        //         alert('El correo debe terminar en @cbta256.edu.mx');
        //         $(this).focus();
        //     }
        // });

        // Capitalizar nombres automáticamente
        $('#nombre, #apellido_p, #apellido_m, #ubicacion_localidad, #programa_encargado_nombre').on('input', function() {
            var start = this.selectionStart;
            var end = this.selectionEnd;
            
            this.value = this.value.toLowerCase()
                .replace(/(?:^|\s)\S/g, function(a) { 
                    return a.toUpperCase(); 
                });
                
            this.setSelectionRange(start, end);
        });
    @endif

    // Validación del formulario antes de enviar
    $('#editForm').submit(function(e) {
        @if($selectedTable === 'alumno')
            // Verificar si hay errores de validación antes de enviar
            var email = $('#correo_institucional').val();
            if (email && !email.endsWith('@cbta256.edu.mx')) {
                e.preventDefault();
                
                // Mostrar error visual sin alert
                $('#correo_institucional').addClass('border-red-500');
                
                // Agregar mensaje de error si no existe
                if ($('#email-error').length === 0) {
                    $('#correo_institucional').after('<div id="email-error" class="text-red-500 text-sm mt-1">El correo debe terminar en @cbta256.edu.mx</div>');
                }
                
                $('#correo_institucional').focus();
                return false;
            } else {
                // Remover errores si todo está bien
                $('#correo_institucional').removeClass('border-red-500');
                $('#email-error').remove();
            }
        @endif
        
        var submitBtn = $('#submitBtn');
        var submitText = $('#submitText');
        var originalText = submitText.text();

        // Cambiar estado del botón
        submitBtn.prop('disabled', true);
        submitText.text('Actualizando...');
        
        // Agregar spinner
        submitBtn.find('svg').addClass('animate-spin');
        
        // Resetear después de 30 segundos si no se redirige
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitText.text(originalText);
            submitBtn.find('svg').removeClass('animate-spin');
        }, 30000);
    });

    // Validación en tiempo real para campos específicos (sin alertas)
    $('#telefono').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    // Capitalizar nombres automáticamente (para formularios genéricos)
    $('#nombre, #apellido_p, #apellido_m').on('input', function() {
        var start = this.selectionStart;
        var end = this.selectionEnd;
        
        this.value = this.value.toLowerCase()
            .replace(/(?:^|\s)\S/g, function(a) { 
                return a.toUpperCase(); 
            });
            
        this.setSelectionRange(start, end);
    });
});
</script>
@endsection