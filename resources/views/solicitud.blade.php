<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Servicio Social</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header con logo -->
        <div class="text-center mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 max-w-4xl mx-auto">
                <img src="{{ asset('img/servicio-social-banner.jpg') }}" 
                     alt="Logo CBTa" 
                     class="mx-auto mb-4 max-w-xs h-auto rounded-lg">
                
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                    SOLICITUD DE SERVICIO SOCIAL (SSS)
                </h1>
                
                <div class="text-sm text-gray-600 space-y-1">
                    <p class="font-semibold text-red-800">DIRECCIÓN GENERAL DE EDUCACIÓN TECNOLÓGICA AGROPECUARIA Y CIENCIAS DEL MAR</p>
                    <p class="font-semibold text-red-800">CENTRO DE BACHILLERATO TECNOLÓGICO AGROPECUARIO N°256</p>
                    <p class="text-gray-700">DEPARTAMENTO DE VINCULACIÓN Y DESARROLLO INSTITUCIONAL</p>
                </div>
            </div>
        </div>

        {{-- ALERTA DE ERROR SI VIENE DESDE EL CONTROLADOR --}}
        @if(session('error'))
            <div class="max-w-4xl mx-auto mb-6">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <strong>{{ session('error') }}</strong><br>
                            Por favor, vuelva a intentarlo o contacte al personal de apoyo.
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido principal -->
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Información del proceso -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Información del Proceso</h2>
                    </div>
                    
                    <div class="space-y-4 text-gray-700">
                        <p class="leading-relaxed">
                            El presente formulario debe ser llenado de manera correcta y con la información que se solicita dentro del periodo que esté disponible el alta para la realización del servicio social, esto con la finalidad de agilizar el proceso.
                        </p>
                        
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <p class="text-amber-800 font-medium">
                                Una vez revisado se enviará la confirmación para el proceso de servicio social, 
                                <span class="font-bold">en dado caso que la información sea errónea se notificará</span> 
                                y se habilitará de nueva cuenta el formulario para que sea contestado de manera idónea.
                            </p>
                        </div>
                        
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-bold">
                                Debe ser llenado con el correo institucional que te proporciono la institución, 
                                de no ser así no se tomará en cuenta tu registro.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Opciones de registro -->
                <div class="space-y-6">
                    
                    <!-- Nuevo registro -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Nuevo Registro</h3>
                        </div>
                        
                        <p class="text-gray-600 mb-4">Realizar un nuevo registro para servicio social.</p>
                        
                        <a href="{{ url('/datos-alumno') }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Iniciar Nuevo Registro
                        </a>
                    </div>

                    <!-- Actualizar registro existente -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Actualizar Registro</h3>
                        </div>
                        
                        <p class="text-gray-600 mb-4">¿Ya tienes un registro? Actualiza tu información existente.</p>
                        
                        <button onclick="mostrarFormularioActualizar()" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Actualizar Registro
                        </button>
                        
                        <!-- Formulario de actualización (inicialmente oculto) -->
                        <div id="formularioActualizar" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                            <form action="{{ route('buscar.registro') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Folio de Registro (ID) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="folio" 
                                           placeholder="Ej: 123"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div class="text-center text-gray-500 font-bold">DEBE LLENAR UNO DE LOS DOS:</div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Número de Control (Matrícula)
                                    </label>
                                    <input type="text" 
                                           name="numero_control" 
                                           placeholder="14 dígitos"
                                           maxlength="14"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div class="text-center text-gray-500 font-medium">O</div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Electrónico Institucional
                                    </label>
                                    <input type="email" 
                                           name="correo" 
                                           placeholder="tucorreo@cbta256.edu.mx"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                
                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Buscar y Editar Registro
                                    </button>
                                    <button type="button" 
                                            onclick="ocultarFormularioActualizar()"
                                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarFormularioActualizar() {
            document.getElementById('formularioActualizar').classList.remove('hidden');
        }
        
        function ocultarFormularioActualizar() {
            document.getElementById('formularioActualizar').classList.add('hidden');
        }
    </script>
</body>
</html>
