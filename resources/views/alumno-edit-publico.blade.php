<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Mi Información - CBTA 256</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Estilos personalizados para mejor responsive */
        @media (max-width: 640px) {
            .tab-button {
                font-size: 0.75rem;
                padding: 0.75rem 0.25rem;
            }
            .tab-button span {
                display: none;
            }
        }
        
        /* Animaciones suaves */
        .tab-content {
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.3s ease-in-out;
        }
        
        .tab-content:not(.hidden) {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Mejores focus states para móviles */
        input:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            
            <!-- Header Responsive -->
            <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-center sm:text-left">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-600">📝 Actualizar Mi Información</h1>
                        <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Modifica tu información personal, académica y de servicio social</p>
                    </div>
                    <div class="text-center sm:text-right bg-gray-50 sm:bg-transparent p-3 sm:p-0 rounded-lg sm:rounded-none">
                        <p class="text-xs sm:text-sm text-gray-500">FOLIO: <span class="font-bold text-blue-600">#{{ $alumno->id }}</span></p>
                        <p class="text-xs sm:text-sm text-gray-500">Nombre: <span class="font-bold">{{ $alumno->nombre }} {{ $alumno->apellido_p }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Breadcrumb Responsive -->
            <nav class="flex mb-4 sm:mb-6 overflow-x-auto" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-nowrap">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors">
                            🏠 <span class="ml-1 hidden sm:inline">Inicio</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mx-1 sm:mx-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-gray-500">Actualizar</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Mostrar errores - Responsive -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xs sm:text-sm font-medium text-red-800">Se encontraron errores:</h3>
                            <div class="mt-2 text-xs sm:text-sm text-red-700">
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

            <!-- Mostrar mensaje de éxito - Responsive -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs sm:text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario Responsive -->
            <div class="bg-white rounded-lg sm:rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <form action="{{ url('/alumno/' . $alumno->id . '/actualizar') }}" method="POST" id="editAlumnoForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Tabs Navigation - Responsive -->
                    <div class="border-b border-gray-200 overflow-x-auto">
                        <nav class="flex px-2 sm:px-6" aria-label="Tabs">
                            <button type="button" class="tab-button border-b-2 border-blue-500 py-3 sm:py-4 px-2 sm:px-4 text-xs sm:text-sm font-medium text-blue-600 whitespace-nowrap" data-tab="personal">
                                <span class="sm:hidden">👤</span>
                                <span class="hidden sm:inline">👤 Datos Personales</span>
                            </button>
                            <button type="button" class="tab-button border-b-2 border-transparent py-3 sm:py-4 px-2 sm:px-4 text-xs sm:text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="ubicacion">
                                <span class="sm:hidden">📍</span>
                                <span class="hidden sm:inline">📍 Ubicación</span>
                            </button>
                            <button type="button" class="tab-button border-b-2 border-transparent py-3 sm:py-4 px-2 sm:px-4 text-xs sm:text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="escolaridad">
                                <span class="sm:hidden">🎓</span>
                                <span class="hidden sm:inline">🎓 Escolaridad</span>
                            </button>
                            <button type="button" class="tab-button border-b-2 border-transparent py-3 sm:py-4 px-2 sm:px-4 text-xs sm:text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="programa">
                                <span class="sm:hidden">🏢</span>
                                <span class="hidden sm:inline">🏢 Programa</span>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-3 sm:p-6">
                        
                        <!-- Datos Personales -->
                        <div id="personal-tab" class="tab-content">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">👤 Información Personal</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                
                                <!-- Nombre -->
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre(s) *</label>
                                    <input type="text" name="nombre" value="{{ old('nombre', $alumno->nombre) }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="45">
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Apellido Paterno *</label>
                                    <input type="text" name="apellido_p" value="{{ old('apellido_p', $alumno->apellido_p) }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="45">
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Apellido Materno *</label>
                                    <input type="text" name="apellido_m" value="{{ old('apellido_m', $alumno->apellido_m) }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="45">
                                </div>

                                <!-- Correo Institucional -->
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Institucional *</label>
                                    <input type="email" name="correo_institucional" value="{{ old('correo_institucional', $alumno->correo_institucional) }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required pattern=".*@cbta256\.edu\.mx$" title="Debe terminar en @cbta256.edu.mx">
                                    <p class="text-xs text-gray-500 mt-1">Debe terminar en @cbta256.edu.mx</p>
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                                    <input type="tel" name="telefono" value="{{ old('telefono', $alumno->telefono) }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required pattern="[0-9]{10}" title="Debe ser un número de 10 dígitos" maxlength="10">
                                </div>

                                <!-- Edad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Edad *</label>
                                    <select name="edad_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione una edad</option>
                                        @foreach($foreignOptions['edad_id'] as $edad)
                                            <option value="{{ $edad->id }}" {{ old('edad_id', $alumno->edad_id) == $edad->id ? 'selected' : '' }}>
                                                {{ $edad->edades }} años
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sexo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sexo *</label>
                                    <select name="sexo_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione el sexo</option>
                                        @foreach($foreignOptions['sexo_id'] as $sexo)
                                            <option value="{{ $sexo->id }}" {{ old('sexo_id', $alumno->sexo_id) == $sexo->id ? 'selected' : '' }}>
                                                {{ $sexo->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div id="ubicacion-tab" class="tab-content hidden">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">📍 Información de Ubicación</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                
                                <!-- Estado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                                    <select name="ubicacion_estados_id" id="estadoSelect" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un estado</option>
                                        @foreach($foreignOptions['estados_id'] as $estado)
                                            <option value="{{ $estado->id }}" 
                                                    {{ old('ubicacion_estados_id', $ubicacion->estado_id ?? '') == $estado->id ? 'selected' : '' }}>
                                                {{ $estado->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($ubicacion && $ubicacion->estado_id)
                                        <p class="text-xs text-green-600 mt-1">✓ Estado actual: {{ $ubicacion->estado_nombre ?? 'Cargando...' }}</p>
                                    @endif
                                </div>

                                <!-- Municipio -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Municipio *</label>
                                    <select name="ubicacion_municipios_id" id="municipioSelect" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un municipio</option>
                                        @if($foreignOptions['municipios_del_estado']->count() > 0)
                                            @foreach($foreignOptions['municipios_del_estado'] as $municipio)
                                                <option value="{{ $municipio->id }}" 
                                                        {{ old('ubicacion_municipios_id', $ubicacion->municipios_id ?? '') == $municipio->id ? 'selected' : '' }}>
                                                    {{ $municipio->nombre }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($ubicacion && $ubicacion->municipios_id)
                                        <p class="text-xs text-green-600 mt-1">✓ Municipio actual: {{ $ubicacion->municipio_nombre ?? 'Cargando...' }}</p>
                                    @endif
                                    <div id="municipioLoading" class="hidden text-xs text-blue-600 mt-1">
                                        <span class="inline-flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Cargando municipios...
                                        </span>
                                    </div>
                                </div>

                                <!-- Localidad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Localidad *</label>
                                    <input type="text" name="ubicacion_localidad" value="{{ old('ubicacion_localidad', $ubicacion->localidad ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="100" placeholder="Ej: Centro, Col. Jardines, etc.">
                                </div>

                                <!-- Código Postal -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Código Postal *</label>
                                    <input type="text" name="ubicacion_cp" value="{{ old('ubicacion_cp', $ubicacion->cp ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required pattern="[0-9]{5}" title="Debe ser un código de 5 dígitos" maxlength="5" placeholder="76650">
                                    <p class="text-xs text-gray-500 mt-1">5 dígitos numéricos</p>
                                </div>
                            </div>
                        </div>

                        <!-- Escolaridad -->
                        <div id="escolaridad-tab" class="tab-content hidden">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">🎓 Información Académica</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                
                                <!-- Número de Control -->
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Control *</label>
                                    <input type="text" name="escolaridad_numero_control" value="{{ old('escolaridad_numero_control', $escolaridad->numero_control ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="14">
                                </div>

                                <!-- Meses de Servicio -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meses de Servicio *</label>
                                    <input type="number" name="escolaridad_meses_servicio" value="{{ old('escolaridad_meses_servicio', $escolaridad->meses_servicio ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required min="1" max="12">
                                </div>

                                <!-- Modalidad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Modalidad *</label>
                                    <select name="escolaridad_modalidad_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione una modalidad</option>
                                        @foreach($foreignOptions['modalidad_id'] as $modalidad)
                                            <option value="{{ $modalidad->id }}" {{ old('escolaridad_modalidad_id', $escolaridad->modalidad_id ?? '') == $modalidad->id ? 'selected' : '' }}>
                                                {{ $modalidad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Carrera -->
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Carrera *</label>
                                    <select name="escolaridad_carreras_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione una carrera</option>
                                        @foreach($foreignOptions['carreras_id'] as $carrera)
                                            <option value="{{ $carrera->id }}" {{ old('escolaridad_carreras_id', $escolaridad->carreras_id ?? '') == $carrera->id ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Semestre -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Semestre *</label>
                                    <select name="escolaridad_semestres_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un semestre</option>
                                        @foreach($foreignOptions['semestres_id'] as $semestre)
                                            <option value="{{ $semestre->id }}" {{ old('escolaridad_semestres_id', $escolaridad->semestres_id ?? '') == $semestre->id ? 'selected' : '' }}>
                                                {{ $semestre->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Grupo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Grupo *</label>
                                    <select name="escolaridad_grupos_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un grupo</option>
                                        @foreach($foreignOptions['grupos_id'] as $grupo)
                                            <option value="{{ $grupo->id }}" {{ old('escolaridad_grupos_id', $escolaridad->grupos_id ?? '') == $grupo->id ? 'selected' : '' }}>
                                                {{ $grupo->letra }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Programa -->
                        <div id="programa-tab" class="tab-content hidden">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">🏢 Programa de Servicio Social</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                
                                <!-- Institución -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Institución *</label>
                                    <select name="programa_instituciones_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione una institución</option>
                                        @foreach($foreignOptions['instituciones_id'] as $institucion)
                                            <option value="{{ $institucion->id }}" {{ old('programa_instituciones_id', $programa->instituciones_id ?? '') == $institucion->id ? 'selected' : '' }}>
                                                {{ $institucion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nombre del Programa -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Programa *</label>
                                    <input type="text" name="programa_nombre_programa" value="{{ old('programa_nombre_programa', $programa->nombre_programa ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="255">
                                </div>

                                <!-- Encargado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Encargado *</label>
                                    <input type="text" name="programa_encargado_nombre" value="{{ old('programa_encargado_nombre', $programa->encargado_nombre ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="100">
                                </div>

                                <!-- Título del Encargado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Título del Encargado *</label>
                                    <select name="programa_titulos_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un título</option>
                                        @foreach($foreignOptions['titulos_id'] as $titulo)
                                            <option value="{{ $titulo->id }}" {{ old('programa_titulos_id', $programa->titulos_id ?? '') == $titulo->id ? 'selected' : '' }}>
                                                {{ $titulo->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Puesto del Encargado -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Puesto del Encargado *</label>
                                    <input type="text" name="programa_puesto_encargado" value="{{ old('programa_puesto_encargado', $programa->puesto_encargado ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required maxlength="100">
                                </div>

                                <!-- Teléfono de la Institución -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono de la Institución *</label>
                                    <input type="tel" name="programa_telefono_institucion" value="{{ old('programa_telefono_institucion', $programa->telefono_institucion ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required pattern="[0-9]{10}" maxlength="10">
                                </div>

                                <!-- Método de Servicio -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Método de Servicio *</label>
                                    <select name="programa_metodo_servicio_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un método</option>
                                        @foreach($foreignOptions['metodo_servicio_id'] as $metodo)
                                            <option value="{{ $metodo->id }}" {{ old('programa_metodo_servicio_id', $programa->metodo_servicio_id ?? '') == $metodo->id ? 'selected' : '' }}>
                                                {{ $metodo->metodo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Fecha de Inicio -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio *</label>
                                    <input type="date" name="programa_fecha_inicio" value="{{ old('programa_fecha_inicio', $programa->fecha_inicio ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required>
                                </div>

                                <!-- Fecha Final -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Final *</label>
                                    <input type="date" name="programa_fecha_final" value="{{ old('programa_fecha_final', $programa->fecha_final ?? '') }}" 
                                           class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                           required>
                                </div>

                                <!-- Tipo de Programa -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Programa *</label>
                                    <select name="programa_tipos_programa_id" class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" required>
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($foreignOptions['tipos_programa_id'] as $tipo)
                                            <option value="{{ $tipo->id }}" {{ old('programa_tipos_programa_id', $programa->tipos_programa_id ?? '') == $tipo->id ? 'selected' : '' }}>
                                                {{ $tipo->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Campos opcionales -->
                                <div class="sm:col-span-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Otra Institución (opcional)</label>
                                            <input type="text" name="programa_otra_institucion" value="{{ old('programa_otra_institucion', $programa->otra_institucion ?? '') }}" 
                                                   class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                                   maxlength="255">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Otro Programa (opcional)</label>
                                            <input type="text" name="programa_otro_programa" value="{{ old('programa_otro_programa', $programa->otro_programa ?? '') }}" 
                                                   class="w-full px-3 py-2.5 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base sm:text-sm" 
                                                   maxlength="255">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción - Responsive -->
                    <div class="p-3 sm:p-6 bg-gray-50 border-t border-gray-200">
                        <!-- Botones para móviles (stack vertical) -->
                        <div class="flex flex-col space-y-3 sm:hidden">
                            <div class="flex space-x-3">
                                <button type="button" id="prevBtnMobile" onclick="showPrevTab()" 
                                        class="hidden flex-1 inline-flex items-center justify-center px-4 py-3 bg-gray-600 border border-transparent rounded-lg font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                    ← Anterior
                                </button>
                                
                                <button type="button" id="nextBtnMobile" onclick="showNextTab()" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                    Siguiente →
                                </button>
                            </div>
                            
                            <button type="submit" id="submitBtnMobile" 
                                    class="hidden w-full inline-flex items-center justify-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                💾 Actualizar Mi Información
                            </button>
                            
                            <a href="{{ url('/solicitud') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-500 border border-transparent rounded-lg font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                ✕ Cancelar
                            </a>
                        </div>

                        <!-- Botones para desktop (layout horizontal) -->
                        <div class="hidden sm:flex sm:flex-col lg:flex-row lg:justify-between space-y-3 lg:space-y-0 lg:space-x-4">
                            <div class="flex space-x-3">
                                <a href="{{ url('/solicitud') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-lg font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                    ← Cancelar
                                </a>
                                
                                <button type="button" id="prevBtn" onclick="showPrevTab()" 
                                        class="hidden inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                    ← Anterior
                                </button>
                            </div>
                            
                            <div class="flex space-x-3">
                                <button type="button" id="nextBtn" onclick="showNextTab()" 
                                        class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                    Siguiente →
                                </button>
                                
                                <button type="submit" id="submitBtn" 
                                        class="hidden inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                    💾 Actualizar Mi Información
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentTab = 0;
        const tabs = ['personal', 'ubicacion', 'escolaridad', 'programa'];

        function showTab(n) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Mostrar el tab actual
            document.getElementById(tabs[n] + '-tab').classList.remove('hidden');
            
            // Actualizar navegación de tabs
            document.querySelectorAll('.tab-button').forEach((btn, index) => {
                if (index === n) {
                    btn.classList.remove('border-transparent', 'text-gray-500');
                    btn.classList.add('border-blue-500', 'text-blue-600');
                } else {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                }
            });
            
            // Mostrar/ocultar botones para desktop
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            if (prevBtn) prevBtn.style.display = n === 0 ? 'none' : 'inline-flex';
            if (nextBtn) nextBtn.style.display = n === tabs.length - 1 ? 'none' : 'inline-flex';
            if (submitBtn) submitBtn.style.display = n === tabs.length - 1 ? 'inline-flex' : 'none';

            // Mostrar/ocultar botones para móvil
            const prevBtnMobile = document.getElementById('prevBtnMobile');
            const nextBtnMobile = document.getElementById('nextBtnMobile');
            const submitBtnMobile = document.getElementById('submitBtnMobile');
            
            if (prevBtnMobile) prevBtnMobile.style.display = n === 0 ? 'none' : 'inline-flex';
            if (nextBtnMobile) nextBtnMobile.style.display = n === tabs.length - 1 ? 'none' : 'inline-flex';
            if (submitBtnMobile) submitBtnMobile.style.display = n === tabs.length - 1 ? 'inline-flex' : 'none';
        }

        function showNextTab() {
            // Validar tab actual antes de avanzar
            if (validateCurrentTab()) {
                if (currentTab < tabs.length - 1) {
                    currentTab++;
                    showTab(currentTab);
                    scrollToTop();
                }
            }
        }

        function showPrevTab() {
            if (currentTab > 0) {
                currentTab--;
                showTab(currentTab);
                scrollToTop();
            }
        }

        // Validar tab actual
        function validateCurrentTab() {
            const currentTabElement = document.getElementById(tabs[currentTab] + '-tab');
            const requiredFields = currentTabElement.querySelectorAll('input[required], select[required]');
            let isValid = true;
            let firstInvalidField = null;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    markFieldAsInvalid(field, 'Este campo es obligatorio');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    markFieldAsValid(field);
                }
            });

            if (!isValid && firstInvalidField) {
                firstInvalidField.focus();
                showError('Por favor complete todos los campos obligatorios');
            }

            return isValid;
        }

        // Marcar campo como inválido
        function markFieldAsInvalid(field, message) {
            field.classList.add('border-red-500');
            field.classList.remove('border-gray-300');
            
            // Remover mensaje anterior
            const existingError = field.parentNode.querySelector('.validation-error');
            if (existingError) existingError.remove();
            
            // Agregar nuevo mensaje
            const errorDiv = document.createElement('p');
            errorDiv.className = 'validation-error text-red-500 text-xs mt-1';
            errorDiv.textContent = message;
            field.parentNode.appendChild(errorDiv);
        }

        // Marcar campo como válido
        function markFieldAsValid(field) {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
            
            const existingError = field.parentNode.querySelector('.validation-error');
            if (existingError) existingError.remove();
        }

        // Mostrar mensaje de error
        function showError(message) {
            // Crear o actualizar mensaje de error temporal
            let errorDiv = document.getElementById('temp-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'temp-error';
                errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                document.body.appendChild(errorDiv);
            }
            
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            
            // Ocultar después de 3 segundos
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        }

        // Navegación por clicks en tabs
        document.querySelectorAll('.tab-button').forEach((btn, index) => {
            btn.addEventListener('click', () => {
                currentTab = index;
                showTab(currentTab);
                scrollToTop();
            });
        });

        function scrollToTop() {
            if (window.innerWidth < 640) {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        // Inicializar
        showTab(0);

        // Validaciones en tiempo real
        $(document).ready(function() {
            // Validar nombres (solo letras y espacios)
            $('input[name="nombre"], input[name="apellido_p"], input[name="apellido_m"], input[name="programa_encargado_nombre"]').on('input', function() {
                const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/;
                if (!regex.test(this.value)) {
                    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                }
                validateField(this);
            });

            // Validar correo institucional
            $('input[name="correo_institucional"]').on('blur', function() {
                const email = $(this).val().trim();
                if (email && !email.endsWith('@cbta256.edu.mx')) {
                    markFieldAsInvalid(this, 'El correo debe terminar en @cbta256.edu.mx');
                } else if (email) {
                    markFieldAsValid(this);
                }
            });

            // Validar solo números en teléfonos
            $('input[type="tel"]').on('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 10);
                validateField(this);
            });

            // Validar código postal
            $('input[name="ubicacion_cp"]').on('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 5);
                validateField(this);
            });

            // Validar número de control
            $('input[name="escolaridad_numero_control"]').on('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 14);
                validateField(this);
            });

            // Validar fechas
            $('input[name="programa_fecha_final"]').on('change', function() {
                const fechaInicio = $('input[name="programa_fecha_inicio"]').val();
                const fechaFinal = $(this).val();
                
                if (fechaInicio && fechaFinal && fechaFinal <= fechaInicio) {
                    markFieldAsInvalid(this, 'La fecha final debe ser posterior a la fecha de inicio');
                    $(this).val('');
                } else if (fechaFinal) {
                    markFieldAsValid(this);
                }
            });

            // Validación de campos requeridos al salir
            $('input[required], select[required]').on('blur', function() {
                validateField(this);
            });

            function validateField(field) {
                if (field.hasAttribute('required') && !field.value.trim()) {
                    markFieldAsInvalid(field, 'Este campo es obligatorio');
                } else {
                    markFieldAsValid(field);
                }
            }

            // Validación final del formulario
            $('#editAlumnoForm').on('submit', function(e) {
                console.log('=== ENVIANDO FORMULARIO ===');
                
                // Validar todos los campos
                let isValid = true;
                let firstInvalidField = null;

                $(this).find('input[required], select[required]').each(function() {
                    if (!this.value.trim()) {
                        markFieldAsInvalid(this, 'Este campo es obligatorio');
                        isValid = false;
                        if (!firstInvalidField) firstInvalidField = this;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    if (firstInvalidField) {
                        // Ir al tab que contiene el campo inválido
                        const invalidTab = $(firstInvalidField).closest('.tab-content').attr('id').replace('-tab', '');
                        const tabIndex = tabs.indexOf(invalidTab);
                        if (tabIndex !== -1) {
                            currentTab = tabIndex;
                            showTab(currentTab);
                        }
                        firstInvalidField.focus();
                    }
                    showError('Por favor complete todos los campos obligatorios');
                    return false;
                }

                // Mostrar indicador de carga
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Guardando...');

                // Log de datos del formulario
                const formData = new FormData(this);
                console.log('Datos del formulario:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                // Restaurar botón después de 10 segundos (por si hay error)
                setTimeout(() => {
                    submitBtn.prop('disabled', false).text(originalText);
                }, 10000);
            });

            // Touch/swipe para navegación en móviles
            let startX = 0;
            let endX = 0;

            document.addEventListener('touchstart', function(e) {
                startX = e.changedTouches[0].screenX;
            });

            document.addEventListener('touchend', function(e) {
                endX = e.changedTouches[0].screenX;
                handleSwipe();
            });

            function handleSwipe() {
                const threshold = 100;
                const diff = startX - endX;

                if (Math.abs(diff) > threshold) {
                    if (diff > 0 && currentTab < tabs.length - 1) {
                        showNextTab();
                    } else if (diff < 0 && currentTab > 0) {
                        showPrevTab();
                    }
                }
            }

            // Mejorar experiencia en formularios móviles
            $('input, select').on('focus', function() {
                if (window.innerWidth < 640) {
                    setTimeout(() => {
                        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                }
            });
        });

        // Filtrado de municipios por estado - MEJORADO
        $('#estadoSelect').on('change', function() {
            const estadoId = $(this).val();
            const municipioSelect = $('#municipioSelect');
            const loadingDiv = $('#municipioLoading');
            
            console.log('Estado seleccionado:', estadoId);
            
            // Limpiar municipios
            municipioSelect.html('<option value="">Seleccione un municipio</option>');
            
            if (estadoId) {
                // Mostrar loading
                loadingDiv.removeClass('hidden');
                municipioSelect.prop('disabled', true);
                
                // Hacer petición AJAX para obtener municipios
                $.ajax({
                    url: '/api/municipios-por-estado/' + estadoId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(municipios) {
                        console.log('Municipios recibidos:', municipios);
                        loadingDiv.addClass('hidden');
                        municipioSelect.prop('disabled', false);
                        
                        if (municipios.length > 0) {
                            // Agregar opción por defecto
                            let options = '<option value="">Seleccione un municipio</option>';
                            
                            // Agregar municipios
                            municipios.forEach(function(municipio) {
                                options += `<option value="${municipio.id}">${municipio.nombre}</option>`;
                            });
                            
                            municipioSelect.html(options);
                            
                            // Si hay un municipio preseleccionado, mantenerlo
                            const municipioActual = '{{ old("ubicacion_municipios_id", $ubicacion->municipios_id ?? "") }}';
                            if (municipioActual) {
                                municipioSelect.val(municipioActual);
                                console.log('Municipio preseleccionado:', municipioActual);
                            }
                        } else {
                            municipioSelect.html('<option value="">No hay municipios disponibles</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar municipios:', error);
                        loadingDiv.addClass('hidden');
                        municipioSelect.prop('disabled', false);
                        municipioSelect.html('<option value="">Error al cargar municipios</option>');
                        showError('Error al cargar los municipios. Inténtelo de nuevo.');
                    }
                });
            } else {
                municipioSelect.html('<option value="">Primero seleccione un estado</option>');
            }
        });

        // Inicialización mejorada al cargar la página
        $(document).ready(function() {
            console.log('=== INICIALIZANDO FORMULARIO ===');
            
            // Datos actuales del alumno
            const estadoActual = '{{ old("ubicacion_estados_id", $ubicacion->estado_id ?? "") }}';
            const municipioActual = '{{ old("ubicacion_municipios_id", $ubicacion->municipios_id ?? "") }}';
            
            console.log('Estado actual:', estadoActual);
            console.log('Municipio actual:', municipioActual);
            
            // Si hay estado actual, asegurarse de que esté seleccionado
            if (estadoActual) {
                $('#estadoSelect').val(estadoActual);
                console.log('Estado seleccionado en el select');
                
                // Si ya hay municipios cargados (desde el servidor), no hacer AJAX
                const municipiosYaCargados = $('#municipioSelect option').length > 1;
                console.log('Municipios ya cargados:', municipiosYaCargados);
                
                if (!municipiosYaCargados) {
                    // Cargar municipios via AJAX
                    console.log('Cargando municipios via AJAX...');
                    $('#estadoSelect').trigger('change');
                } else if (municipioActual) {
                    // Si ya están cargados, solo seleccionar el actual
                    $('#municipioSelect').val(municipioActual);
                    console.log('Municipio seleccionado directamente');
                }
            }
            
            // Verificar que los valores estén correctamente seleccionados después de un momento
            setTimeout(function() {
                const estadoSeleccionado = $('#estadoSelect').val();
                const municipioSeleccionado = $('#municipioSelect').val();
                
                console.log('=== VERIFICACIÓN FINAL ===');
                console.log('Estado en select:', estadoSeleccionado);
                console.log('Municipio en select:', municipioSeleccionado);
                
                if (estadoActual && estadoSeleccionado !== estadoActual) {
                    console.warn('⚠️ Estado no se seleccionó correctamente');
                    $('#estadoSelect').val(estadoActual);
                }
                
                if (municipioActual && municipioSeleccionado !== municipioActual) {
                    console.warn('⚠️ Municipio no se seleccionó correctamente');
                    $('#municipioSelect').val(municipioActual);
                }
            }, 1500);
        });

        // Barra de progreso
        function updateProgress() {
            const progress = ((currentTab + 1) / tabs.length) * 100;
            let progressBar = document.getElementById('progressBar');
            
            if (!progressBar) {
                progressBar = document.createElement('div');
                progressBar.id = 'progressBar';
                progressBar.className = 'h-1 bg-blue-600 transition-all duration-300 ease-in-out';
                progressBar.style.width = progress + '%';
                
                const progressContainer = document.createElement('div');
                progressContainer.className = 'w-full bg-gray-200 h-1 mb-4';
                progressContainer.appendChild(progressBar);
                
                document.querySelector('.tab-content').parentNode.insertBefore(progressContainer, document.querySelector('.tab-content'));
            } else {
                progressBar.style.width = progress + '%';
            }
        }

        // Actualizar barra de progreso cuando cambie el tab
        const originalShowTab = showTab;
        showTab = function(n) {
            originalShowTab(n);
            updateProgress();
        };
    </script>
</body>
</html>