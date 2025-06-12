
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
        <form action="{{ route('record.update', ['table' => $selectedTable, 'id' => $record->id]) }}" method="POST" enctype="multipart/form-data" id="editForm">
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
                    <!-- Formulario específico para alumno -->
                    <div class="space-y-8">
                        <!-- Información Personal -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                <h3 class="text-lg font-semibold text-gray-900">Información Personal</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre(s) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $record->nombre ?? '') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Nombre completo">
                                </div>

                                <!-- Apellido Paterno -->
                                <div>
                                    <label for="apellido_p" class="block text-sm font-medium text-gray-700 mb-2">
                                        Apellido Paterno <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="apellido_p" id="apellido_p" value="{{ old('apellido_p', $record->apellido_p ?? '') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Apellido paterno">
                                </div>

                                <!-- Apellido Materno -->
                                <div>
                                    <label for="apellido_m" class="block text-sm font-medium text-gray-700 mb-2">
                                        Apellido Materno <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="apellido_m" id="apellido_m" value="{{ old('apellido_m', $record->apellido_m ?? '') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="Apellido materno">
                                </div>

                                <!-- Correo Institucional -->
                                <div>
                                    <label for="correo_institucional" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Institucional <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="correo_institucional" id="correo_institucional" value="{{ old('correo_institucional', $record->correo_institucional ?? '') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="correo@cbta256.edu.mx">
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="telefono" id="telefono" value="{{ old('telefono', $record->telefono ?? '') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="4271234567">
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
                                            <option value="{{ $edad->id }}" {{ (old('edad_id', $record->edad_id ?? '') == $edad->id) ? 'selected' : '' }}>
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
                                            <option value="{{ $sexo->id }}" {{ (old('sexo_id', $record->sexo_id ?? '') == $sexo->id) ? 'selected' : '' }}>
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
                                            <option value="{{ $rol->id }}" {{ (old('rol_id', $record->rol_id ?? '') == $rol->id) ? 'selected' : '' }}>
                                                {{ $rol->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status_id" id="status_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Seleccione una opción</option>
                                        @foreach(DB::table('status')->get() as $status)
                                            <option value="{{ $status->id }}" {{ (old('status_id', $record->status_id ?? '') == $status->id) ? 'selected' : '' }}>
                                                {{ $status->tipo }}
                                            </option>
                                        @endforeach
                                    </select>
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
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('dashboard', ['table' => $selectedTable]) }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Cancelar
                </a>

                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-amber-600 border border-transparent rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors shadow-sm"
                        id="submitBtn">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
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
@endsection