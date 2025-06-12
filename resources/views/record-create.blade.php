@extends('layouts.blaze')

@section('title', "Añadir Registro - {$selectedTable}")
@section('page-title', "Crear Nuevo Registro")
@section('page-description', "Formulario para agregar un nuevo registro en: " . ucfirst(str_replace('_', ' ', $selectedTable)))

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb mejorado -->
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
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500">Nuevo Registro</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header con información contextual -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                @if($selectedTable === 'alumno')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @elseif($selectedTable === 'formatos')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                @elseif($selectedTable === 'instituciones')
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                @else
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    @if($selectedTable === 'alumno')
                        Registro Completo de Alumno
                    @elseif($selectedTable === 'formatos')
                        Subir Formatos de Documentos
                    @elseif($selectedTable === 'instituciones')
                        Registrar Nueva Institución
                    @elseif($selectedTable === 'usuario')
                        Crear Nuevo Usuario
                    @else
                        Crear Nuevo {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                    @endif
                </h1>
                <p class="text-gray-600 mt-1">
                    @if($selectedTable === 'alumno')
                        Complete toda la información del alumno incluyendo datos académicos y de servicio social
                    @elseif($selectedTable === 'formatos')
                        Suba los archivos de formato requeridos para el sistema
                    @elseif($selectedTable === 'instituciones')
                        Agregue una nueva institución donde los alumnos pueden realizar su servicio social
                    @else
                        Complete todos los campos requeridos para crear el registro
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Formulario principal -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Contenido del formulario -->
        <form action="{{ route('record.store', ['table' => $selectedTable]) }}" method="POST" enctype="multipart/form-data" id="mainForm">
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
                    <!-- Formulario específico para alumno completo -->
                    <div class="space-y-8">
                        <!-- Paso 1: Datos Personales -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                <h3 class="text-lg font-semibold text-gray-900">Información Personal del Alumno</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre(s) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Nombre completo">
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label for="apellido_p" class="block text-sm font-medium text-gray-700 mb-2">
                                        Apellido Paterno <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="apellido_p" id="apellido_p" value="{{ old('apellido_p') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Apellido paterno">
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label for="apellido_m" class="block text-sm font-medium text-gray-700 mb-2">
                                        Apellido Materno <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="apellido_m" id="apellido_m" value="{{ old('apellido_m') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Apellido materno">
                                </div>

                                <!-- Correo Institucional -->
                                <div>
                                    <label for="correo_institucional" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Institucional <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="correo_institucional" id="correo_institucional" value="{{ old('correo_institucional') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="correo@cbta256.edu.mx">
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="4271234567">
                                </div>

                                <!-- Código Postal de ubicación -->
                                <div>
                                    <label for="cp" class="block text-sm font-medium text-gray-700 mb-2">
                                        Código Postal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="cp" id="cp" value="{{ old('cp') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="12345">
                                </div>

                                <!-- Edad -->
                                <div>
                                    <label for="edad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Edad <span class="text-red-500">*</span>
                                    </label>
                                    <select name="edad_id" id="edad_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Seleccione una opción</option>
                                        @foreach(DB::table('edad')->get() as $edad)
                                            <option value="{{ $edad->id }}" {{ old('edad_id') == $edad->id ? 'selected' : '' }}>
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
                                        @foreach(DB::table('sexo')->get() as $sexo)
                                            <option value="{{ $sexo->id }}" {{ old('sexo_id') == $sexo->id ? 'selected' : '' }}>
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
                                        @foreach(DB::table('rol')->get() as $rol)
                                            <option value="{{ $rol->id }}" {{ old('rol_id') == $rol->id ? 'selected' : '' }}>
                                                {{ $rol->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 2: Ubicación -->
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                <h3 class="text-lg font-semibold text-gray-900">Información de Ubicación</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label for="localidad" class="block text-sm font-medium text-gray-700 mb-2">Localidad <span class="text-red-500">*</span></label>
                                    <input type="text" name="localidad" id="localidad" value="{{ old('localidad') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                           placeholder="Nombre de la localidad">
                                </div>
                                <div>
                                    <label for="estado_id" class="block text-sm font-medium text-gray-700 mb-2">Estado <span class="text-red-500">*</span></label>
                                    <select name="estado_id" id="estado_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                        <option value="">Seleccione un estado</option>
                                        @foreach(DB::table('estados')->select('id','nombre')->get() as $estado)
                                            <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="municipio_id" class="block text-sm font-medium text-gray-700 mb-2">Municipio <span class="text-red-500">*</span></label>
                                    <select name="municipio_id" id="municipio_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                        <option value="">Primero seleccione un estado</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="cp" class="block text-sm font-medium text-gray-700 mb-2">Código Postal (Ubicación) <span class="text-red-500">*</span></label>
                                    <input type="number" name="cp" id="cp" value="{{ old('cp') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                           placeholder="Ej: 12345">
                                </div>
                            </div>
                        </div>

                        <!-- Paso 3: Información Académica -->
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                                <h3 class="text-lg font-semibold text-gray-900">Información Académica</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Número Control -->
                                <div>
                                    <label for="numero_control" class="block text-sm font-medium text-gray-700 mb-2">
                                        Número de Control <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="numero_control" id="numero_control" value="{{ old('numero_control') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                           placeholder="12345678">
                                </div>

                                <!-- Meses Servicio -->
                                <div>
                                    <label for="meses_servicio" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meses de Servicio <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="meses_servicio" id="meses_servicio" value="{{ old('meses_servicio') }}" required min="1" max="24"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                           placeholder="6">
                                </div>

                                <!-- Carrera -->
                                <div>
                                    <label for="carreras_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Carrera <span class="text-red-500">*</span>
                                    </label>
                                    <select name="carreras_id" id="carreras_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                        <option value="">Seleccione una carrera</option>
                                        @foreach(DB::table('carreras')->get() as $carrera)
                                            <option value="{{ $carrera->id }}" {{ old('carreras_id') == $carrera->id ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Semestre -->
                                <div>
                                    <label for="semestres_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Semestre <span class="text-red-500">*</span>
                                    </label>
                                    <select name="semestres_id" id="semestres_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                        <option value="">Seleccione un semestre</option>
                                        @foreach(DB::table('semestres')->get() as $semestre)
                                            <option value="{{ $semestre->id }}" {{ old('semestres_id') == $semestre->id ? 'selected' : '' }}>
                                                {{ $semestre->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Grupo -->
                                <div>
                                    <label for="grupos_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Grupo <span class="text-red-500">*</span>
                                    </label>
                                    <select name="grupos_id" id="grupos_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                        <option value="">Seleccione un grupo</option>
                                        @foreach(DB::table('grupos')->get() as $grupo)
                                            <option value="{{ $grupo->id }}" {{ old('grupos_id') == $grupo->id ? 'selected' : '' }}>
                                                {{ $grupo->letra }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Modalidad -->
                                <div>
                                    <label for="modalidad_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Modalidad <span class="text-red-500">*</span>
                                    </label>
                                    <select name="modalidad_id" id="modalidad_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                        <option value="">Seleccione una modalidad</option>
                                        @foreach(DB::table('modalidad')->get() as $modalidad)
                                            <option value="{{ $modalidad->id }}" {{ old('modalidad_id') == $modalidad->id ? 'selected' : '' }}>
                                                {{ $modalidad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 4: Programa de Servicio Social -->
                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</div>
                                <h3 class="text-lg font-semibold text-gray-900">Programa de Servicio Social</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Nombre Programa -->
                                <div class="md:col-span-2">
                                    <label for="nombre_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Programa <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombre_programa" id="nombre_programa" value="{{ old('nombre_programa') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                           placeholder="Nombre del programa de servicio social">
                                </div>

                                <!-- Encargado Nombre -->
                                <div>
                                    <label for="encargado_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre del Encargado <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="encargado_nombre" id="encargado_nombre" value="{{ old('encargado_nombre') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                           placeholder="Nombre completo del encargado">
                                </div>

                                <!-- Fecha Inicio -->
                                <div>
                                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha de Inicio <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>

                                <!-- Fecha Final -->
                                <div>
                                    <label for="fecha_final" class="block text-sm font-medium text-gray-700 mb-2">
                                        Fecha Final <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="fecha_final" id="fecha_final" value="{{ old('fecha_final') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>

                                <!-- Institución -->
                                <div>
                                    <label for="instituciones_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Institución <span class="text-red-500">*</span>
                                    </label>
                                    <select name="instituciones_id" id="instituciones_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                        <option value="">Seleccione una institución</option>
                                        @foreach(DB::table('instituciones')->get() as $institucion)
                                            <option value="{{ $institucion->id }}" {{ old('instituciones_id') == $institucion->id ? 'selected' : '' }}>
                                                {{ $institucion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Título -->
                                <div>
                                    <label for="titulos_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Título <span class="text-red-500">*</span>
                                    </label>
                                    <select name="titulos_id" id="titulos_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                        <option value="">Seleccione un título</option>
                                        @foreach(DB::table('titulos')->get() as $titulo)
                                            <option value="{{ $titulo->id }}" {{ old('titulos_id') == $titulo->id ? 'selected' : '' }}>
                                                {{ $titulo->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Puesto Encargado -->
                                <div>
                                    <label for="puesto_encargado" class="block text-sm font-medium text-gray-700 mb-2">
                                        Puesto del Encargado <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="puesto_encargado" id="puesto_encargado" value="{{ old('puesto_encargado') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                           placeholder="Cargo o puesto del encargado">
                                </div>

                                <!-- Método -->
                                <div>
                                    <label for="metodo_servicio_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Método de Servicio <span class="text-red-500">*</span>
                                    </label>
                                    <select name="metodo_servicio_id" id="metodo_servicio_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                        <option value="">Seleccione un método</option>
                                        @foreach(DB::table('metodo_servicio')->get() as $metodo)
                                            <option value="{{ $metodo->id }}" {{ old('metodo_servicio_id') == $metodo->id ? 'selected' : '' }}>
                                                {{ $metodo->metodo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo Programa -->
                                <div>
                                    <label for="tipos_programa_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Programa <span class="text-red-500">*</span>
                                    </label>
                                    <select name="tipos_programa_id" id="tipos_programa_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                        <option value="">Seleccione un tipo</option>
                                        @foreach(DB::table('tipos_programa')->get() as $tipo_programa)
                                            <option value="{{ $tipo_programa->id }}" {{ old('tipos_programa_id') == $tipo_programa->id ? 'selected' : '' }}>
                                                {{ $tipo_programa->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Teléfono Institución -->
                                <div>
                                    <label for="telefono_institucion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono de la Institución <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="telefono_institucion" id="telefono_institucion" value="{{ old('telefono_institucion') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                           placeholder="Teléfono de contacto">
                                </div>

                                <!-- Otra Institución (opcional) -->
                                <div>
                                    <label for="otra_institucion" class="block text-sm font-medium text-gray-700 mb-2">
                                        Otra Institución (Opcional)
                                    </label>
                                    <input type="text" name="otra_institucion" id="otra_institucion" value="{{ old('otra_institucion') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                           placeholder="Si no está en la lista anterior">
                                </div>

                                <!-- Otro Programa (opcional) -->
                                <div>
                                    <label for="otro_programa" class="block text-sm font-medium text-gray-700 mb-2">
                                        Otro Programa (Opcional)
                                    </label>
                                    <input type="text" name="otro_programa" id="otro_programa" value="{{ old('otro_programa') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                           placeholder="Si no está en la lista anterior">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields con valores por defecto -->
                        <input type="hidden" name="status_id" value="1">
                        <input type="hidden" name="fecha_registro" value="{{ date('Y-m-d H:i:s') }}">
                    </div>

                @else
                    <!-- Formulario genérico para otras tablas -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información de {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(isset($record))
                                @foreach((array)$record as $field => $value)
                                    @if($field !== 'id')
                                        <div>
                                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                                            </label>
                                            @if($field === 'fecha_registro' || str_contains($field, 'fecha'))
                                                <input type="date" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            @elseif(str_contains($field, 'telefono') || str_contains($field, 'phone'))
                                                <input type="tel" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                       placeholder="Número de teléfono">
                                            @elseif(str_contains($field, 'email') || str_contains($field, 'correo'))
                                                <input type="email" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                       placeholder="correo@ejemplo.com">
                                            @else
                                                <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                       placeholder="Ingrese {{ strtolower(str_replace('_', ' ', $field)) }}">
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Botones de acción mejorados -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('dashboard', ['table' => $selectedTable]) }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitText">
                        @if($selectedTable === 'alumno')
                            Registrar Alumno Completo
                        @else
                            Guardar {{ ucfirst(str_replace('_', ' ', $selectedTable)) }}
                        @endif
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts mejorados -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Cargar municipios cuando se selecciona un estado
    $('#estado_id').change(function(){
        var estadoId = $(this).val();
        var municipioSelect = $('#municipio_id');
        
        if(estadoId){
            municipioSelect.html('<option value="">Cargando municipios...</option>').prop('disabled', true);
            
            $.ajax({
                url: '/municipios-por-estado/' + estadoId,
                dataType: 'json',
                success: function(data){
                    var opciones = '<option value="">Seleccione un municipio</option>';
                    $.each(data, function(i, municipio){
                        opciones += '<option value="'+ municipio.id +'">'+ municipio.nombre +'</option>';
                    });
                    municipioSelect.html(opciones).prop('disabled', false);
                },
                error: function() {
                    municipioSelect.html('<option value="">Error al cargar municipios</option>').prop('disabled', false);
                    alert('Error al cargar los municipios. Por favor, intente de nuevo.');
                }
            });
        } else {
            municipioSelect.html('<option value="">Primero seleccione un estado</option>').prop('disabled', false);
        }
    });

    // Validación de fechas
    $('#fecha_inicio, #fecha_final').change(function(){
        var fechaInicio = new Date($('#fecha_inicio').val());
        var fechaFinal = new Date($('#fecha_final').val());
        
        if(fechaInicio && fechaFinal && fechaFinal <= fechaInicio){
            alert('La fecha final debe ser posterior a la fecha de inicio.');
            $('#fecha_final').val('');
        }
    });

    // Validación del formulario antes de enviar
    $('#mainForm').submit(function(e) {
        var submitBtn = $('#submitBtn');
        var submitText = $('#submitText');
        var originalText = submitText.text();

        // Cambiar estado del botón
        submitBtn.prop('disabled', true);
        submitText.text('Guardando...');
        
        // Agregar spinner
        submitBtn.find('svg').replaceWith('<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');

        // Resetear después de 10 segundos si no se redirige
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitText.text(originalText);
            submitBtn.find('svg').replaceWith('<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>');
        }, 10000);
    });
});
</script>
@endsection