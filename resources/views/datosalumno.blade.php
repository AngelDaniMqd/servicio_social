@php
    $estados = DB::table('estados')->select('id','nombre')->get();
    $municipios = DB::table('municipios')->select('id','nombre','estado_id')->get();
    $edades = DB::table('edad')->select('id','edades')->get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Alumno</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-4 sm:py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-lg mb-6 p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-primary to-red-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Datos del Alumno</h1>
                <p class="text-gray-600 text-sm sm:text-base">Complete su información personal para continuar con el registro</p>
            </div>
        </div>

        <!-- Alert Message -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
                <p class="text-red-600 text-sm mt-1">Intenta nuevamente.</p>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <form id="formAlumno" action="{{ url('/guardar-datos-alumno') }}" method="POST">
                @csrf
                
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Contact Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información de Contacto</h2>
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="correo" class="block text-sm font-medium text-gray-700">
                                Correo Institucional <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" name="correo_institucional" id="correo" required 
                                       pattern="^[a-zA-Z0-9._%+-]+@cbta256\.edu\.mx$" 
                                       placeholder="ejemplo@cbta256.edu.mx"
                                       value="{{ old('correo_institucional', $datosGuardados['correo_institucional'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="error-message hidden text-red-500 text-sm" id="correo-error"></div>
                        </div>

                        <!-- Phone Field -->
                        <div class="space-y-2">
                            <label for="telefono" class="block text-sm font-medium text-gray-700">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="tel" name="telefono" id="telefono" required 
                                       maxlength="10" minlength="10" pattern="[0-9]{10}" 
                                       placeholder="1234567890"
                                       value="{{ old('telefono', $datosGuardados['telefono'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="error-message hidden text-red-500 text-sm" id="telefono-error"></div>
                        </div>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información Personal</h2>
                        </div>

                        <!-- Name Fields Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Apellido Paterno -->
                            <div class="space-y-2">
                                <label for="apellido_paterno" class="block text-sm font-medium text-gray-700">
                                    Apellido Paterno <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="apellido_paterno" id="apellido_paterno" required 
                                       maxlength="45" placeholder="García"
                                       value="{{ old('apellido_paterno', $datosGuardados['apellido_paterno'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="apellido_paterno-error"></div>
                            </div>

                            <!-- Apellido Materno -->
                            <div class="space-y-2">
                                <label for="apellido_materno" class="block text-sm font-medium text-gray-700">
                                    Apellido Materno <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="apellido_materno" id="apellido_materno" required 
                                       maxlength="45" placeholder="López"
                                       value="{{ old('apellido_materno', $datosGuardados['apellido_materno'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="apellido_materno-error"></div>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">
                                Nombre(s) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre" required 
                                   maxlength="45" placeholder="Juan Carlos"
                                    value="{{ old('nombre', $datosGuardados['nombre'] ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                            <div class="error-message hidden text-red-500 text-sm" id="nombre-error"></div>
                        </div>

                        <!-- Age and Gender Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Edad -->
                            <div class="space-y-2">
                                <label for="edad" class="block text-sm font-medium text-gray-700">
                                    Edad <span class="text-red-500">*</span>
                                </label>
                                <select name="edad" id="edad" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                    <option value="" disabled {{ !old('edad', $datosGuardados['edad'] ?? '') ? 'selected' : '' }}>Selecciona tu edad</option>
                                    @foreach($edades as $edad)
                                        <option value="{{ $edad->id }}" {{ (old('edad', $datosGuardados['edad'] ?? '') == $edad->id) ? 'selected' : '' }}>
                                            {{ $edad->edades }} años
                                        </option>
                                    @endforeach
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="edad-error"></div>
                            </div>

                            <!-- Sexo -->
                            <div class="space-y-2">
                                <label for="sexo" class="block text-sm font-medium text-gray-700">
                                    Sexo <span class="text-red-500">*</span>
                                </label>
                                <select name="sexo" id="sexo" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                    <option value="" disabled {{ !old('sexo', $datosGuardados['sexo'] ?? '') ? 'selected' : '' }}>Selecciona tu sexo</option>
                                    <option value="1" {{ (old('sexo', $datosGuardados['sexo'] ?? '') == '1') ? 'selected' : '' }}>Femenino</option>
                                    <option value="2" {{ (old('sexo', $datosGuardados['sexo'] ?? '') == '2') ? 'selected' : '' }}>Masculino</option>
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="sexo-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información de Ubicación</h2>
                        </div>

                        <!-- Location Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Localidad -->
                            <div class="space-y-2">
                                <label for="localidad" class="block text-sm font-medium text-gray-700">
                                    Localidad <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="localidad" id="localidad" required 
                                       placeholder="San Pedro Tlaquepaque"
                                       value="{{ old('localidad', $datosGuardados['localidad'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="localidad-error"></div>
                            </div>

                            <!-- Código Postal -->
                            <div class="space-y-2">
                                <label for="cp" class="block text-sm font-medium text-gray-700">
                                    Código Postal <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="cp" id="cp" required 
                                       maxlength="5" minlength="5" pattern="[0-9]{5}" 
                                       placeholder="45500"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="cp-error"></div>
                            </div>
                        </div>

                        <!-- State and Municipality Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Estado -->
                            <div class="space-y-2">
                                <label for="estado" class="block text-sm font-medium text-gray-700">
                                    Estado <span class="text-red-500">*</span>
                                </label>
                                <select name="estado" id="estado" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base">
                                    <option value="" disabled selected>Selecciona un estado</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="estado-error"></div>
                            </div>

                            <!-- Municipio -->
                            <div class="space-y-2">
                                <label for="municipio" class="block text-sm font-medium text-gray-700">
                                    Municipio <span class="text-red-500">*</span>
                                </label>
                                <select name="municipio" id="municipio" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base opacity-50 cursor-not-allowed"
                                        disabled>
                                    <option value="" disabled selected>Primero selecciona un estado</option>
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="municipio-error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 sm:px-8 py-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <a href="{{ url('/solicitud') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Atrás
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        Siguiente
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-6 bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Progreso del registro</span>
                <span>1 de 3</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="modalError" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 transform transition-all">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Error de validación</h3>
                <p class="text-gray-600 mb-6">Por favor complete todos los campos requeridos correctamente.</p>
                <button id="btnCerrarModal" 
                        class="w-full inline-flex justify-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Datos de municipios
        const municipiosData = @json($municipios);
        
        // Función mejorada para capitalizar nombres
        function capitalizarNombres(input) {
            const start = input.selectionStart;
            const end = input.selectionEnd;
            
            let value = input.value.toLowerCase()
                .replace(/(^|\s)\S/g, function(letra) {
                    return letra.toUpperCase();
                });
            
            input.value = value;
            input.setSelectionRange(start, end);
            return value;
        }
        
        // Validación de nombres y apellidos
        function validarNombreApellido(input) {
            const value = capitalizarNombres(input);
            const regex = /^[A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+( [A-ZÁÉÍÓÚÜÑ][a-záéíóúüñ]+)*$/;
            
            if (value.trim() === "") {
                mostrarError(input, "Este campo es obligatorio");
                return false;
            } else if (!regex.test(value)) {
                mostrarError(input, "Formato incorrecto. Ejemplo: José Armando");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Función para mostrar errores
        function mostrarError(campo, mensaje) {
            const errorElement = document.getElementById(`${campo.id}-error`);
            if (mensaje) {
                errorElement.textContent = mensaje;
                errorElement.classList.remove('hidden');
                campo.classList.add('border-red-500', 'ring-red-500');
                campo.classList.remove('border-gray-300', 'border-green-500');
            } else {
                errorElement.classList.add('hidden');
                campo.classList.remove('border-red-500', 'ring-red-500');
                campo.classList.add('border-green-500');
            }
        }
        
        // Validación de correo institucional
        function validarCorreo(input) {
            const regex = /^[a-zA-Z0-9._%+-]+@cbta256\.edu\.mx$/;
            if (!regex.test(input.value)) {
                mostrarError(input, "El correo debe terminar en @cbta256.edu.mx");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Validación de teléfono
        function validarTelefono(input) {
            const regex = /^[0-9]{10}$/;
            if (!regex.test(input.value)) {
                mostrarError(input, "Debe ingresar exactamente 10 dígitos");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Validación de código postal
        function validarCP(input) {
            const regex = /^[0-9]{5}$/;
            if (!regex.test(input.value)) {
                mostrarError(input, "El código postal debe tener 5 dígitos");
                return false;
            } else {
                mostrarError(input, '');
                return true;
            }
        }
        
        // Validación para selects
        function validarSelect(select) {
            if (select.required && select.value === "") {
                mostrarError(select, "Este campo es requerido");
                return false;
            } else {
                mostrarError(select, '');
                return true;
            }
        }
        
        // Cargar municipios según el estado seleccionado
        function cargarMunicipios() {
            const estadoSelect = document.getElementById('estado');
            const municipioSelect = document.getElementById('municipio');
            const selectedEstadoId = estadoSelect.value;
            
            municipioSelect.innerHTML = '<option value="" disabled selected>Selecciona un municipio</option>';
            
            const filtrados = municipiosData.filter(m => m.estado_id == selectedEstadoId);
            
            if (filtrados.length > 0) {
                municipioSelect.disabled = false;
                municipioSelect.classList.remove('opacity-50', 'cursor-not-allowed');
                
                filtrados.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = m.nombre;
                    municipioSelect.appendChild(opt);
                });
            } else {
                municipioSelect.disabled = true;
                municipioSelect.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            validarSelect(municipioSelect);
        }

        // Modal de error
        function mostrarModalError() {
            document.getElementById('modalError').classList.remove('hidden');
            document.getElementById('modalError').classList.add('flex');
        }
        
        function ocultarModalError() {
            document.getElementById('modalError').classList.add('hidden');
            document.getElementById('modalError').classList.remove('flex');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Event listeners para modal
            document.getElementById('btnCerrarModal').onclick = ocultarModalError;
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") ocultarModalError();
            });

            // Event listeners para campos de nombres
            const camposNombres = ['apellido_paterno', 'apellido_materno', 'nombre', 'localidad'];
            camposNombres.forEach(id => {
                const campo = document.getElementById(id);
                
                campo.addEventListener('input', function() {
                    capitalizarNombres(this);
                });
                
                campo.addEventListener('blur', function() {
                    validarNombreApellido(this);
                });
            });
            
            // Event listeners para correo
            const correo = document.getElementById('correo');
            correo.addEventListener('input', function() {
                validarCorreo(this);
            });
            correo.addEventListener('blur', function() {
                validarCorreo(this);
            });
            
            // Event listeners para teléfono
            const telefono = document.getElementById('telefono');
            telefono.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                validarTelefono(this);
            });
            telefono.addEventListener('blur', function() {
                validarTelefono(this);
            });
            
            // Event listeners para código postal
            const cp = document.getElementById('cp');
            cp.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
                validarCP(this);
            });
            cp.addEventListener('blur', function() {
                validarCP(this);
            });
            
            // Event listeners para selects
            const selects = ['edad', 'sexo', 'estado', 'municipio'];
            selects.forEach(id => {
                const select = document.getElementById(id);
                if (select) {
                    select.addEventListener('change', function() {
                        validarSelect(this);
                    });
                }
            });
            
            // Cargar municipios cuando cambie el estado
            const estadoSelect = document.getElementById('estado');
            estadoSelect.addEventListener('change', cargarMunicipios);
            
            // Validar formulario antes de enviar
            document.getElementById('formAlumno').addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validar todos los campos requeridos
                const requiredFields = this.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (field.id === 'correo' && !validarCorreo(field)) isValid = false;
                    else if (field.id === 'telefono' && !validarTelefono(field)) isValid = false;
                    else if (field.id === 'cp' && !validarCP(field)) isValid = false;
                    else if (camposNombres.includes(field.id) && !validarNombreApellido(field)) isValid = false;
                    else if (selects.includes(field.id) && !validarSelect(field)) isValid = false;
                });
                
                if (!isValid) {
                    e.preventDefault();
                    mostrarModalError();
                }
            });
        });
    </script>
</body>
</html>