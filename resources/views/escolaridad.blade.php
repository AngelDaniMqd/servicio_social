<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolaridad del Alumno</title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Escolaridad del Alumno</h1>
                <p class="text-gray-600 text-sm sm:text-base">Complete su información académica para continuar</p>
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
            <form id="formEscolaridad" action="{{ url('/guardar-escolaridad') }}" method="POST" novalidate>
                @csrf
                
                <div class="p-6 sm:p-8 space-y-8">
                    <!-- Academic Control Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Control Académico</h2>
                        </div>

                        <!-- Control Number and Service Months Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número de Control -->
                            <div class="space-y-2">
                                <label for="matricula" class="block text-sm font-medium text-gray-700">
                                    Número de Control <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="matricula" id="matricula" required 
                                           value="{{ old('matricula', $datosEscolaridadGuardados['numero_control'] ?? '') }}"
                                           maxlength="14" pattern="[0-9]{14}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l-4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="error-message hidden text-red-500 text-sm" id="matriculaError">
                                    El número de control debe tener exactamente 14 dígitos.
                                </div>
                                <p class="text-xs text-gray-500">Ingrese los 14 dígitos de su número de control</p>
                            </div>

                            <!-- Meses de Servicio -->
                            <div class="space-y-2">
                                <label for="meses_servicio" class="block text-sm font-medium text-gray-700">
                                    Meses de Servicio <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="meses_servicio" id="meses_servicio" required 
                                           min="1" max="12" placeholder="6"
                                           value="{{ old('meses_servicio', $datosEscolaridadGuardados['meses_servicio'] ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm sm:text-base">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">Duración del servicio social (1-12 meses)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">Información Académica</h2>
                        </div>

                        <!-- Career and Modality Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Modalidad -->
                            <div class="space-y-2">
                                <label for="modalidad_id" class="block text-sm font-medium text-gray-700">
                                    Modalidad <span class="text-red-500">*</span>
                                </label>
                               <select name="modalidad_id" id="modalidad_id" required 
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
    <option value="" disabled {{ !old('modalidad_id', $datosEscolaridadGuardados['modalidad_id'] ?? '') ? 'selected' : '' }}>Selecciona una modalidad</option>
    @foreach($modalidades as $modalidad)
        <option value="{{ $modalidad->id }}" {{ (old('modalidad_id', $datosEscolaridadGuardados['modalidad_id'] ?? '') == $modalidad->id) ? 'selected' : '' }}>
            {{ $modalidad->nombre }}
        </option>
    @endforeach
</select>
                            </div>

                            <!-- Carrera -->
                            <div class="space-y-2">
                                <label for="carreras_id" class="block text-sm font-medium text-gray-700">
                                    Carrera <span class="text-red-500">*</span>
                                </label>
                               <select name="carreras_id" id="carreras_id" required 
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
    <option value="" disabled {{ !old('carreras_id', $datosEscolaridadGuardados['carreras_id'] ?? '') ? 'selected' : '' }}>Selecciona una carrera</option>
    @foreach($carreras as $carrera)
        <option value="{{ $carrera->id }}" {{ (old('carreras_id', $datosEscolaridadGuardados['carreras_id'] ?? '') == $carrera->id) ? 'selected' : '' }}>
            {{ $carrera->nombre }}
        </option>
    @endforeach
</select>
                            </div>
                        </div>

                        <!-- Semester and Group Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Semestre -->
                            <div class="space-y-2">
                                <label for="semestres_id" class="block text-sm font-medium text-gray-700">
                                    Semestre <span class="text-red-500">*</span>
                                </label>
                               <select name="semestres_id" id="semestres_id" required 
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
    <option value="" disabled {{ !old('semestres_id', $datosEscolaridadGuardados['semestres_id'] ?? '') ? 'selected' : '' }}>Selecciona un semestre</option>
    @foreach($semestres as $semestre)
        <option value="{{ $semestre->id }}" {{ (old('semestres_id', $datosEscolaridadGuardados['semestres_id'] ?? '') == $semestre->id) ? 'selected' : '' }}>
            {{ $semestre->nombre }}
        </option>
    @endforeach
</select>
                            </div>

                            <!-- Grupo -->
                            <div class="space-y-2">
                                <label for="grupos_id" class="block text-sm font-medium text-gray-700">
                                    Grupo <span class="text-red-500">*</span>
                                </label>
                                <select name="grupos_id" id="grupos_id" required 
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-sm sm:text-base">
    <option value="" disabled {{ !old('grupos_id', $datosEscolaridadGuardados['grupos_id'] ?? '') ? 'selected' : '' }}>Selecciona un grupo</option>
    @foreach($grupos as $grupo)
        <option value="{{ $grupo->id }}" {{ (old('grupos_id', $datosEscolaridadGuardados['grupos_id'] ?? '') == $grupo->id) ? 'selected' : '' }}>
            {{ $grupo->letra }}
        </option>
    @endforeach
</select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-gray-50 px-6 sm:px-8 py-4 flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
                    <a href="{{ url('/datos-alumno') }}" 
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
                <span>2 de 3</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 66%"></div>
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

            // Validación del número de control
            const matriculaInput = document.getElementById('matricula');
            const matriculaError = document.getElementById('matriculaError');
            
            matriculaInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 14);
                
                if (this.value.length !== 14) {
                    matriculaError.classList.remove('hidden');
                    this.classList.add('border-red-500', 'ring-red-500');
                    this.classList.remove('border-green-500');
                } else {
                    matriculaError.classList.add('hidden');
                    this.classList.remove('border-red-500', 'ring-red-500');
                    this.classList.add('border-green-500');
                }
            });

            // Validación de selects
            const selects = document.querySelectorAll('select[required]');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    if (this.value) {
                        this.classList.remove('border-red-500', 'ring-red-500');
                        this.classList.add('border-green-500');
                    }
                });
            });

            // Validación del formulario
            document.getElementById('formEscolaridad').addEventListener('submit', function(e) {
                let isValid = true;
                let onlyMatriculaError = false;

                // Validar campos requeridos
                const requiredFields = this.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (field.tagName === "SELECT" && (field.value === "" || field.value === null)) {
                        isValid = false;
                        field.classList.add('border-red-500', 'ring-red-500');
                    }
                    if (field.tagName === "INPUT" && (field.value.trim() === "")) {
                        isValid = false;
                        field.classList.add('border-red-500', 'ring-red-500');
                    }
                    if (field.id === "matricula" && !/^[0-9]{14}$/.test(field.value.trim())) {
                        isValid = false;
                        onlyMatriculaError = true;
                    }
                    if (field.id === "meses_servicio" && (parseInt(field.value) < 1 || isNaN(parseInt(field.value)))) {
                        isValid = false;
                        field.classList.add('border-red-500', 'ring-red-500');
                    }
                });

                // Mostrar solo el error de matrícula si es el único problema
                if (onlyMatriculaError && requiredFields.length === 1) {
                    e.preventDefault();
                    matriculaError.classList.remove('hidden');
                    matriculaInput.focus();
                    return;
                } else {
                    matriculaError.classList.add('hidden');
                }

                if (!isValid) {
                    e.preventDefault();
                    mostrarModalError();
                }
            });
        });
    </script>
</body>
</html>