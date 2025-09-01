@extends('layouts.blaze')

@section('title', "Editar Registro - {$selectedTable}")
@section('page-title', "Editar Registro")
@section('page-description', "Modificar información del registro en: " . ucfirst(str_replace('_', ' ', $selectedTable)))

@section('content')
<div class="max-w-6xl mx-auto">
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
                    <a href="{{ route('dashboard', ['table' => $selectedTable]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                        {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500">Editar Registro</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header con información contextual -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200 p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Editar {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                </h1>
                <p class="text-gray-600 mt-1">
                    Modifica la información del registro seleccionado
                </p>
            </div>
        </div>
    </div>

    <!-- Formulario principal -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <form id="editForm" method="POST" action="{{ route('record.update', ['table' => $selectedTable, 'id' => $record->id]) }}" class="space-y-6">
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

            <div class="p-6">
                @if($selectedTable === 'alumno')
                    <!-- Formulario completo para alumno con todas las secciones -->
                    <div class="space-y-8">
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
                                    </label>
                                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $record->nombre ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label for="apellido_p" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <input type="text" name="apellido_p" id="apellido_p" value="{{ old('apellido_p', $record->apellido_p ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label for="apellido_m" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <input type="text" name="apellido_m" id="apellido_m" value="{{ old('apellido_m', $record->apellido_m ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Correo Institucional -->
                                <div>
                                    <label for="correo_institucional" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <input type="email" name="correo_institucional" id="correo_institucional" value="{{ old('correo_institucional', $record->correo_institucional ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono', $record->telefono ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" inputmode="numeric" pattern="[0-9]{10}">
                                </div>

                                <!-- Código Postal -->
                                @php
                                    $ubicacion = DB::table('ubicaciones')->where('alumno_id', $record->id)->first();
                                @endphp
                                <div>
                                    <label for="ubicacion_cp" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <input type="text" name="ubicacion_cp" id="ubicacion_cp" value="{{ old('ubicacion_cp', $ubicacion->cp ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" inputmode="numeric" pattern="[0-9]{5}">
                                </div>

                                <!-- Edad -->
                                <div>
                                    <label for="edad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <select name="edad_id" id="edad_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione una edad</option>
                                        @foreach(DB::table('edad')->orderBy('edades')->get() as $edad)
                                            <option value="{{ $edad->id }}" {{ (string)old('edad_id', $record->edad_id ?? '') === (string)$edad->id ? 'selected' : '' }}>
                                                {{ $edad->edades }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sexo -->
                                <div>
                                    <label for="sexo_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <select name="sexo_id" id="sexo_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione sexo</option>
                                        @foreach(DB::table('sexo')->orderBy('tipo')->get() as $sexo)
                                            <option value="{{ $sexo->id }}" {{ (string)old('sexo_id', $record->sexo_id ?? '') === (string)$sexo->id ? 'selected' : '' }}>
                                                {{ $sexo->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Rol -->
                                <div>
                                    <label for="rol_id" class="block text_sm font-medium text-gray-700 mb-2">
                                    </label>
                                    <select name="rol_id" id="rol_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione rol</option>
                                        @foreach(DB::table('rol')->orderBy('tipo')->get() as $rol)
                                            <option value="{{ $rol->id }}" {{ (string)old('rol_id', $record->rol_id ?? '') === (string)$rol->id ? 'selected' : '' }}>
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
                                        @foreach(DB::table('estados')->get() as $estado)
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
                                        @foreach(DB::table('municipios')->get() as $municipio)
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
                        @php
                            $escolaridad = DB::table('escolaridad_alumno')->where('alumno_id', $record->id)->first();
                        @endphp
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
                                        @foreach(DB::table('carreras')->get() as $carrera)
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
                                        @foreach(DB::table('semestres')->get() as $semestre)
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
                                        @foreach(DB::table('grupos')->get() as $grupo)
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
                                        @foreach(DB::table('modalidad')->get() as $modalidad)
                                            <option value="{{ $modalidad->id }}" {{ (old('escolaridad_modalidad_id', $escolaridad->modalidad_id ?? '') == $modalidad->id) ? 'selected' : '' }}>
                                                {{ $modalidad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: PROGRAMA DE SERVICIO SOCIAL -->
                        @php
                            $programa = DB::table('programa_servicio_social')->where('alumno_id', $record->id)->first();
                        @endphp
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
                                        @foreach(DB::table('instituciones')->get() as $institucion)
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
                                        @foreach(DB::table('titulos')->get() as $titulo)
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
                                        @foreach(DB::table('metodo_servicio')->get() as $metodo)
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
                                        @foreach(DB::table('tipos_programa')->get() as $tipo)
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

                        <!-- Campos de solo lectura -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Información del Sistema</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">ID del Registro:</span>
                                    <span class="text-gray-600 ml-2">{{ $record->id }}</span>
                                </div>
                                @if(isset($record->fecha_registro))
                                    <div>
                                        <span class="font-medium text-gray-700">Fecha de Registro:</span>
                                        <span class="text-gray-600 ml-2">{{ date('d/m/Y H:i', strtotime($record->fecha_registro)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif($selectedTable === 'programa_servicio_social')
                    <!-- Formulario específico para programa de servicio social -->
                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                Editar Programa de Servicio Social
                            </h3>
                            <p class="text-sm text-gray-600">
                                Modifica la información del registro seleccionado
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Alumno ID (Solo lectura) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alumno (Solo lectura)
                                </label>
                                @php
                                    $alumno = DB::table('alumno')->where('id', $record->alumno_id)->first();
                                @endphp
                                <input type="text" readonly
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                                       value="{{ $alumno ? $alumno->nombre . ' ' . $alumno->apellido_p . ' ' . $alumno->apellido_m : $record->alumno_id }}">
                                <input type="hidden" name="alumno_id" value="{{ $record->alumno_id }}">
                            </div>

                            <!-- Instituciones -->
                            <div>
                                <label for="instituciones_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Institución <span class="text-red-500">*</span>
                                </label>
                                <select name="instituciones_id" id="instituciones_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione una institución</option>
                                    @foreach(DB::table('instituciones')->orderBy('nombre')->get() as $institucion)
                                        <option value="{{ $institucion->id }}" {{ $record->instituciones_id == $institucion->id ? 'selected' : '' }}>
                                            {{ $institucion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Otra Institución -->
                            <div>
                                <label for="otra_institucion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Otra Institución
                                </label>
                                <input type="text" name="otra_institucion" id="otra_institucion" 
                                       value="{{ old('otra_institucion', $record->otra_institucion) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ingrese otra institución">
                            </div>

                            <!-- Nombre Programa -->
                            <div>
                                <label for="nombre_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre Programa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nombre_programa" id="nombre_programa" required
                                       value="{{ old('nombre_programa', $record->nombre_programa) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nombre del programa">
                            </div>

                            <!-- Encargado Nombre -->
                            <div>
                                <label for="encargado_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Encargado Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="encargado_nombre" id="encargado_nombre" required
                                       value="{{ old('encargado_nombre', $record->encargado_nombre) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Nombre del encargado">
                            </div>

                            <!-- Títulos -->
                            <div>
                                <label for="titulos_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Título <span class="text-red-500">*</span>
                                </label>
                                <select name="titulos_id" id="titulos_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione un título</option>
                                    @foreach(DB::table('titulos')->orderBy('titulo')->get() as $titulo)
                                        <option value="{{ $titulo->id }}" {{ $record->titulos_id == $titulo->id ? 'selected' : '' }}>
                                            {{ $titulo->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Puesto Encargado -->
                            <div>
                                <label for="puesto_encargado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Puesto Encargado <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="puesto_encargado" id="puesto_encargado" required
                                       value="{{ old('puesto_encargado', $record->puesto_encargado) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Puesto del encargado">
                            </div>

                            <!-- Método Servicio -->
                            <div>
                                <label for="metodo_servicio_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Método Servicio <span class="text-red-500">*</span>
                                </label>
                                <select name="metodo_servicio_id" id="metodo_servicio_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione un método</option>
                                    @foreach(DB::table('metodo_servicio')->orderBy('metodo')->get() as $metodo)
                                        <option value="{{ $metodo->id }}" {{ $record->metodo_servicio_id == $metodo->id ? 'selected' : '' }}>
                                            {{ $metodo->metodo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Teléfono Institución -->
                            <div>
                                <label for="telefono_institucion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono Institución <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="telefono_institucion" id="telefono_institucion" required
                                       value="{{ old('telefono_institucion', $record->telefono_institucion) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Teléfono de la institución" maxlength="10">
                            </div>

                            <!-- Fecha Inicio -->
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" required
                                       value="{{ old('fecha_inicio', $record->fecha_inicio) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Fecha Final -->
                            <div>
                                <label for="fecha_final" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha Final <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_final" id="fecha_final" required
                                       value="{{ old('fecha_final', $record->fecha_final) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Tipos Programa -->
                            <div>
                                <label for="tipos_programa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo Programa <span class="text-red-500">*</span>
                                </label>
                                <select name="tipos_programa_id" id="tipos_programa_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach(DB::table('tipos_programa')->orderBy('tipo')->get() as $tipo)
                                        <option value="{{ $tipo->id }}" {{ $record->tipos_programa_id == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Otro Programa -->
                            <div>
                                <label for="otro_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                    Otro Programa
                                </label>
                                <input type="text" name="otro_programa" id="otro_programa"
                                       value="{{ old('otro_programa', $record->otro_programa) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ingrese otro programa">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                
                                <select name="status_id" id="status_id" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach(DB::table('status')->whereIn('id', [3, 4])->orderBy('tipo')->get() as $status)
                                        <option value="{{ $status->id }}" {{ $record->status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <!-- Las advertencias se agregan dinámicamente con JavaScript -->
                            </div>
                        </div>

                        <!-- Información del Sistema -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Información del Sistema</h4>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">ID del Registro:</span> {{ $record->id }}
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Formulario genérico para otras tablas -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Editar {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach((array)$record as $field => $value)
                                @if($field !== 'id' && !in_array($field, ['created_at', 'updated_at']))
                                    <div>
                                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                            @if(!in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']))
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        
                                        @if(isset($foreignOptions[$field]))
                                            <!-- Select para llaves foráneas -->
                                            <select name="{{ $field }}" id="{{ $field }}" 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                    {{ !in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']) ? 'required' : '' }}>
                                                <option value="">Seleccione una opción</option>
                                                @foreach($foreignOptions[$field] as $option)
                                                    <option value="{{ $option->id }}" {{ ($value == $option->id) ? 'selected' : '' }}>
                                                        {{ $option->nombre ?? $option->tipo ?? $option->titulo ?? $option->metodo ?? $option->letra ?? $option->edades ?? $option->nombre_column ?? $option->id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif(str_contains($field, 'fecha') || str_contains($field, 'date'))
                                            <!-- Campo de fecha -->
                                            <input type="date" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $value ? date('Y-m-d', strtotime($value)) : '') }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   {{ !in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']) ? 'required' : '' }}>
                                        @elseif(str_contains($field, 'telefono') || str_contains($field, 'phone'))
                                            <!-- Campo de teléfono -->
                                            <input type="tel" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $value) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="Número de teléfono"
                                                   {{ !in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']) ? 'required' : '' }}>
                                        @elseif(str_contains($field, 'email') || str_contains($field, 'correo'))
                                            <!-- Campo de email -->
                                            <input type="email" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $value) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="correo@ejemplo.com"
                                                   {{ !in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']) ? 'required' : '' }}>
                                        @elseif(str_contains($field, 'password'))
                                            <!-- Campo de contraseña -->
                                            <input type="password" name="{{ $field }}" id="{{ $field }}" value=""
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="Dejar en blanco para mantener la actual">
                                        @elseif(in_array($field, ['descripcion', 'observaciones', 'comentarios']))
                                            <!-- Textarea para campos largos -->
                                            <textarea name="{{ $field }}" id="{{ $field }}" rows="3"
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                      placeholder="Ingrese {{ strtolower(str_replace('_', ' ', $field)) }}">{{ old($field, $value) }}</textarea>
                                        @elseif(is_numeric($value) && !str_contains($field, 'telefono'))
                                            <!-- Campo numérico -->
                                            <input type="number" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $value) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="Ingrese {{ strtolower(str_replace('_', ' ', $field)) }}"
                                                   {{ !in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']) ? 'required' : '' }}>
                                        @else
                                            <!-- Campo de texto normal -->
                                            <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $value) }}"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                   placeholder="Ingrese {{ strtolower(str_replace('_', ' ', $field)) }}"
                                                   {{ !in_array($field, ['otra_institucion', 'otro_programa', 'descripcion', 'observaciones']) ? 'required' : '' }}>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Información del registro -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Información del Sistema</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">ID del Registro:</span>
                                    <span class="text-gray-600 ml-2">{{ $record->id }}</span>
                                </div>
                                @if(isset($record->created_at))
                                    <div>
                                        <span class="font-medium text-gray-700">Creado:</span>
                                        <span class="text-gray-600 ml-2">{{ date('d/m/Y H:i', strtotime($record->created_at)) }}</span>
                                    </div>
                                @endif
                                @if(isset($record->updated_at))
                                    <div>
                                        <span class="font-medium text-gray-700">Última modificación:</span>
                                        <span class="text-gray-600 ml-2">{{ date('d/m/Y H:i', strtotime($record->updated_at)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('database.overview', ['table' => $selectedTable]) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancelar
                </a>
                <button id="submitBtn" type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="submitText">Actualizar Registro</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
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

        $('#correo_institucional').on('blur', function() {
            var email = $(this).val();
            if (email && !email.endsWith('@cbta256.edu.mx')) {
                alert('El correo debe terminar en @cbta256.edu.mx');
                $(this).focus();
            }
        });

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
        var submitBtn = $('#submitBtn');
        var submitText = $('#submitText');
        var originalText = submitText.text();

        // Cambiar estado del botón
        submitBtn.prop('disabled', true);
        submitText.text('Actualizando...');
        
        // Agregar spinner
        submitBtn.find('svg').removeClass('w-4 h-4').addClass('w-4 h-4 animate-spin');
        
        // Resetear después de 30 segundos si no se redirige
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitText.text(originalText);
            submitBtn.find('svg').removeClass('animate-spin');
        }, 30000);
    });

    // Validación en tiempo real para campos específicos
    $('#telefono').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    $('#correo_institucional').on('blur', function() {
        var email = $(this).val();
        if (email && !email.endsWith('@cbta256.edu.mx')) {
            alert('El correo debe terminar en @cbta256.edu.mx');
            $(this).focus();
        }
    });

    // Capitalizar nombres automáticamente
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaFinalInput = document.getElementById('fecha_final');
    const statusSelect = document.getElementById('status_id');
    if (!fechaFinalInput || !statusSelect) {
        return; // No ejecutar esta lógica si no estamos editando programa_servicio_social
    }
    const statusContainer = statusSelect.parentElement;
    
    function validarFechaYStatus() {
        // Obtener fecha real del cliente (navegador)
        const hoy = new Date();
        const fechaFinal = new Date(fechaFinalInput.value);
        
        // Resetear horas para comparar solo fechas
        hoy.setHours(0, 0, 0, 0);
        fechaFinal.setHours(0, 0, 0, 0);
        
        const fechaPasada = hoy > fechaFinal;
        
        console.log('=== DEBUG FECHAS ===');
        console.log('Fecha de hoy (cliente):', hoy.toLocaleDateString('es-MX'));
        console.log('Fecha final del programa:', fechaFinal.toLocaleDateString('es-MX'));
        console.log('¿Fecha pasada?:', fechaPasada);
        console.log('===================');
        
        // Remover advertencias anteriores
        const advertenciasAnteriores = statusContainer.querySelectorAll('.advertencia-dinamica');
        advertenciasAnteriores.forEach(el => el.remove());
        
        // Rehabilitar todas las opciones primero
        Array.from(statusSelect.options).forEach(option => {
            option.disabled = false;
            option.textContent = option.textContent.replace(' (No disponible - fecha expirada)', '');
        });
        
        if (fechaPasada) {
            // Deshabilitar opción "En proceso" (id=3) solo si la fecha YA PASÓ
            Array.from(statusSelect.options).forEach(option => {
                if (option.value == '3') {
                    option.disabled = true;
                    option.textContent += ' (No disponible - fecha expirada)';
                }
            });
            
            // Mostrar advertencia roja
            const advertencia = document.createElement('div');
            advertencia.className = 'advertencia-dinamica mt-2 p-3 bg-red-50 border border-red-200 rounded-lg';
            advertencia.innerHTML = `
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-400 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm">
                        <p class="font-medium text-red-800">El servicio debería estar finalizado</p>
                        <p class="text-red-700 mt-1">
                            La fecha de finalización (${fechaFinal.toLocaleDateString('es-MX')}) ya ha pasado. 
                            Solo se puede marcar como "Finalizado" o actualizar la fecha de finalización.
                        </p>
                    </div>
                </div>
            `;
            statusContainer.appendChild(advertencia);
        } else {
            // Mostrar info azul - servicio activo
            const info = document.createElement('div');
            info.className = 'advertencia-dinamica mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg';
            info.innerHTML = `
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm">
                        <p class="font-medium text-blue-800">Servicio activo hasta ${fechaFinal.toLocaleDateString('es-MX')}</p>
                        <p class="text-blue-700 mt-1">
                            Puede cambiar entre "En proceso" y "Finalizado". Hoy es ${hoy.toLocaleDateString('es-MX')}.
                        </p>
                    </div>
                </div>
            `;
            statusContainer.appendChild(info);
        }
    }
    
    // Validar al cargar la página
    validarFechaYStatus();
    
    // Validar cuando cambie la fecha
    fechaFinalInput.addEventListener('change', validarFechaYStatus);
});
</script>
@endsection