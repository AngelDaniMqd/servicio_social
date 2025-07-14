@php
    $instituciones = DB::table('instituciones')->select('id','nombre')->get();
    $titulos = DB::table('titulos')->select('id','titulo')->get();
    $metodos = DB::table('metodo_servicio')->select('id','metodo')->get();
    $tipos = DB::table('tipos_programa')->select('id','tipo')->get();
    $status = DB::table('status')->select('id','tipo')->get();
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa de Servicio Social</title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Programa de Servicio Social</h1>
                <p class="text-gray-600 text-sm sm:text-base">Complete la información del programa donde realizará su servicio social</p>
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
            <form id="formPrograma" action="{{ url('/finalizar-formulario') }}" method="POST" novalidate>
                @csrf
                
                <div class="p-6 sm:p-8 space-y-8">
                    <!-- Institution Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información de la Institución</h2>
                        </div>

                        <!-- Institution Selection -->
                        <div class="space-y-2">
                            <label for="instituciones_id" class="block text-sm font-medium text-gray-700">
                                Institución <span class="text-red-500">*</span>
                            </label>
                            <select name="instituciones_id" id="instituciones_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                                <option value="" disabled selected>Selecciona una institución</option>
                                @foreach($instituciones as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nombre }}</option>
                                @endforeach
                                <option value="otra">Otra institución</option>
                            </select>
                            <div class="error-message hidden text-red-500 text-sm" id="instituciones_id-error"></div>
                        </div>
                        
                        <!-- Other Institution Field (Hidden by default) -->
                        <div id="otra_institucion_div" class="space-y-2 hidden">
                            <label for="otra_institucion" class="block text-sm font-medium text-gray-700">
                                Especifique la institución <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="otra_institucion" id="otra_institucion" 
                                   maxlength="100" placeholder="Nombre de la institución"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                            <div class="error-message hidden text-red-500 text-sm" id="otra_institucion-error"></div>
                        </div>

                        <!-- Institution Phone -->
                        <div class="space-y-2">
                            <label for="telefono_institucion" class="block text-sm font-medium text-gray-700">
                                Teléfono de la Institución <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="tel" name="telefono_institucion" id="telefono_institucion" required 
                                       maxlength="10" minlength="10" pattern="[0-9]{10}" 
                                       placeholder="1234567890"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="error-message hidden text-red-500 text-sm" id="telefono_institucion-error"></div>
                            <p class="text-xs text-gray-500">Ingrese 10 dígitos sin espacios ni guiones</p>
                        </div>
                    </div>

                    <!-- Program Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información del Programa</h2>
                        </div>

                        <!-- Program Name -->
                        <div class="space-y-2">
                            <label for="nombre_programa" class="block text-sm font-medium text-gray-700">
                                Nombre del Programa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_programa" id="nombre_programa" required 
                                   maxlength="100" placeholder="Ej. Programa de Apoyo Comunitario"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                            <div class="error-message hidden text-red-500 text-sm" id="nombre_programa-error"></div>
                        </div>

                        <!-- Program Type and Method Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Program Type -->
                            <div class="space-y-2">
                                <label for="tipos_programa_id" class="block text-sm font-medium text-gray-700">
                                    Tipo de Programa <span class="text-red-500">*</span>
                                </label>
                                <select name="tipos_programa_id" id="tipos_programa_id" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                    <option value="" disabled selected>Selecciona un tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                                    @endforeach
                                    <option value="0">Otro</option>
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="tipos_programa_id-error"></div>
                            </div>

                            <!-- Service Method -->
                            <div class="space-y-2">
                                <label for="metodo_servicio_id" class="block text-sm font-medium text-gray-700">
                                    Método de Servicio <span class="text-red-500">*</span>
                                </label>
                                <select name="metodo_servicio_id" id="metodo_servicio_id" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                    <option value="" disabled selected>Selecciona un método</option>
                                    @foreach($metodos as $met)
                                        <option value="{{ $met->id }}">{{ $met->metodo }}</option>
                                    @endforeach
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="metodo_servicio_id-error"></div>
                            </div>
                        </div>

                        <!-- Other Program Type Field (Hidden by default) -->
                        <div id="otro_programa_div" class="space-y-2 hidden">
                            <label for="otro_programa" class="block text-sm font-medium text-gray-700">
                                Especifique el tipo de programa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="otro_programa" id="otro_programa" 
                                   maxlength="100" placeholder="Nombre del tipo de programa"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                            <div class="error-message hidden text-red-500 text-sm" id="otro_programa-error"></div>
                        </div>

                        <!-- Dates Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div class="space-y-2">
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">
                                    Fecha de Inicio <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="fecha_inicio-error"></div>
                            </div>

                            <!-- End Date -->
                            <div class="space-y-2">
                                <label for="fecha_final" class="block text-sm font-medium text-gray-700">
                                    Fecha Final <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="fecha_final" id="fecha_final" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="fecha_final-error"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información del Encargado</h2>
                        </div>

                        <!-- Supervisor Name and Title Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Supervisor Title -->
                            <div class="space-y-2">
                                <label for="titulos_id" class="block text-sm font-medium text-gray-700">
                                    Título del Encargado <span class="text-red-500">*</span>
                                </label>
                                <select name="titulos_id" id="titulos_id" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base">
                                    <option value="" disabled selected>Selecciona un título</option>
                                    @foreach($titulos as $titulo)
                                        <option value="{{ $titulo->id }}">{{ $titulo->titulo }}</option>
                                    @endforeach
                                </select>
                                <div class="error-message hidden text-red-500 text-sm" id="titulos_id-error"></div>
                            </div>

                            <!-- Supervisor Name -->
                            <div class="space-y-2">
                                <label for="encargado_nombre" class="block text-sm font-medium text-gray-700">
                                    Nombre del Encargado <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="encargado_nombre" id="encargado_nombre" required 
                                       maxlength="100" placeholder="Nombre completo del encargado"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base">
                                <div class="error-message hidden text-red-500 text-sm" id="encargado_nombre-error"></div>
                            </div>
                        </div>

                        <!-- Supervisor Position -->
                        <div class="space-y-2">
                            <label for="puesto_encargado" class="block text-sm font-medium text-gray-700">
                                Puesto del Encargado <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="puesto_encargado" id="puesto_encargado" required 
                                   maxlength="100" placeholder="Ej. Director, Coordinador, Supervisor"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-sm sm:text-base">
                            <div class="error-message hidden text-red-500 text-sm" id="puesto_encargado-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 sm:px-8 py-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <a href="{{ url('/escolaridad') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Atrás
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        Finalizar Registro
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Progress Indicator -->
        <div class="mt-6 bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Progreso del registro</span>
                <span>3 de 3</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
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
                <p class="text-gray-600 mb-6" id="modalErrorMsg">Por favor complete todos los campos requeridos correctamente.</p>
                <button id="btnCerrarModal" 
                        class="w-full inline-flex justify-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar errores individuales
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

        // Modal de error
        function mostrarModalError(msg) {
            document.getElementById('modalErrorMsg').textContent = msg || "Por favor complete todos los campos requeridos correctamente.";
            document.getElementById('modalError').classList.remove('hidden');
            document.getElementById('modalError').classList.add('flex');
        }
        
        function ocultarModalError() {
            document.getElementById('modalError').classList.add('hidden');
            document.getElementById('modalError').classList.remove('flex');
        }

        // Función para capitalizar nombres
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

        document.addEventListener('DOMContentLoaded', function() {
            // Event listeners para modal
            document.getElementById('btnCerrarModal').onclick = ocultarModalError;
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") ocultarModalError();
            });

            // Mostrar/ocultar campo "Otra Institución"
            const instSelect = document.getElementById('instituciones_id');
            const otraInstDiv = document.getElementById('otra_institucion_div');
            const otraInstInput = document.getElementById('otra_institucion');
            
            instSelect.addEventListener('change', function() {
                if (this.value === 'otra') {
                    otraInstDiv.classList.remove('hidden');
                    otraInstInput.setAttribute('required', 'required');
                } else {
                    otraInstDiv.classList.add('hidden');
                    otraInstInput.removeAttribute('required');
                    otraInstInput.value = '';
                    mostrarError(otraInstInput, '');
                }
                mostrarError(this, '');
            });

            // Mostrar/ocultar campo "Otro Programa"
            const tipoSelect = document.getElementById('tipos_programa_id');
            const otroProgDiv = document.getElementById('otro_programa_div');
            const otroProgInput = document.getElementById('otro_programa');
            
            tipoSelect.addEventListener('change', function() {
                if (this.value === '0') {
                    otroProgDiv.classList.remove('hidden');
                    otroProgInput.setAttribute('required', 'required');
                } else {
                    otroProgDiv.classList.add('hidden');
                    otroProgInput.removeAttribute('required');
                    otroProgInput.value = '';
                    mostrarError(otroProgInput, '');
                }
                mostrarError(this, '');
            });

            // Validación de teléfono
            const telInput = document.getElementById('telefono_institucion');
            telInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
                
                if (this.value.length === 10) {
                    mostrarError(this, '');
                } else if (this.value.length > 0) {
                    mostrarError(this, `Faltan ${10 - this.value.length} dígitos`);
                }
            });

            telInput.addEventListener('blur', function() {
                if (this.value.length !== 10) {
                    mostrarError(this, 'El teléfono debe tener exactamente 10 dígitos');
                } else {
                    mostrarError(this, '');
                }
            });

            // Capitalizar nombres automáticamente
            const camposNombres = ['encargado_nombre', 'otra_institucion', 'nombre_programa', 'puesto_encargado', 'otro_programa'];
            camposNombres.forEach(id => {
                const campo = document.getElementById(id);
                if (campo) {
                    campo.addEventListener('input', function() {
                        capitalizarNombres(this);
                    });
                }
            });

            // Validación de fechas
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFinal = document.getElementById('fecha_final');

            function validarFechas() {
                if (fechaInicio.value && fechaFinal.value) {
                    const inicio = new Date(fechaInicio.value);
                    const final = new Date(fechaFinal.value);
                    
                    if (final <= inicio) {
                        mostrarError(fechaFinal, 'La fecha final debe ser posterior a la fecha de inicio');
                        return false;
                    } else {
                        mostrarError(fechaFinal, '');
                        mostrarError(fechaInicio, '');
                        return true;
                    }
                }
                return true;
            }

            fechaInicio.addEventListener('change', validarFechas);
            fechaFinal.addEventListener('change', validarFechas);

            // Validación de selects
            const selects = document.querySelectorAll('select[required]');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    if (this.value && this.value !== '') {
                        mostrarError(this, '');
                    }
                });
            });

            // Validación de inputs requeridos
            const inputs = document.querySelectorAll('input[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.offsetParent !== null && this.value.trim() === '') {
                        mostrarError(this, 'Este campo es obligatorio');
                    } else if (this.value.trim() !== '') {
                        mostrarError(this, '');
                    }
                });
            });

            // Validación del formulario
            document.getElementById('formPrograma').addEventListener('submit', function(e) {
                let isValid = true;
                let primerError = null;

                // Validar selects requeridos
                selects.forEach(sel => {
                    if (!sel.value || sel.value === "") {
                        mostrarError(sel, 'Este campo es obligatorio');
                        isValid = false;
                        if (!primerError) primerError = sel;
                    }
                });

                // Validar inputs requeridos visibles
                inputs.forEach(inp => {
                    if (inp.offsetParent !== null && inp.value.trim() === "") {
                        mostrarError(inp, 'Este campo es obligatorio');
                        isValid = false;
                        if (!primerError) primerError = inp;
                    }
                });

                // Validar teléfono específicamente
                if (telInput.value.length !== 10) {
                    mostrarError(telInput, 'El teléfono debe tener exactamente 10 dígitos');
                    isValid = false;
                    if (!primerError) primerError = telInput;
                }

                // Validar fechas
                if (!validarFechas()) {
                    isValid = false;
                    if (!primerError) primerError = fechaFinal;
                }

                // Validar "Otra Institución" si está visible
                if (instSelect.value === 'otra' && !otraInstInput.value.trim()) {
                    mostrarError(otraInstInput, 'Especifique el nombre de la institución');
                    isValid = false;
                    if (!primerError) primerError = otraInstInput;
                }

                // Validar "Otro Programa" si está visible
                if (tipoSelect.value === '0' && !otroProgInput.value.trim()) {
                    mostrarError(otroProgInput, 'Especifique el tipo de programa');
                    isValid = false;
                    if (!primerError) primerError = otroProgInput;
                }

                if (!isValid) {
                    e.preventDefault();
                    
                    // Hacer scroll al primer error
                    if (primerError) {
                        primerError.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                        setTimeout(() => primerError.focus(), 500);
                    }
                    
                    mostrarModalError('Por favor revise los campos marcados en rojo y complete la información requerida.');
                }
            });
        });
    </script>
</body>
</html>