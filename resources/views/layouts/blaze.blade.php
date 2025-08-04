<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Servicio Social')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        border: "hsl(214.3 31.8% 91.4%)",
                        input: "hsl(214.3 31.8% 91.4%)",
                        ring: "hsl(222.2 84% 4.9%)",
                        background: "hsl(0 0% 100%)",
                        foreground: "hsl(222.2 84% 4.9%)",
                        primary: {
                            DEFAULT: "hsl(222.2 47.4% 11.2%)",
                            foreground: "hsl(210 40% 98%)",
                        },
                        secondary: {
                            DEFAULT: "hsl(210 40% 96%)",
                            foreground: "hsl(222.2 84% 4.9%)",
                        },
                        muted: {
                            DEFAULT: "hsl(210 40% 96%)",
                            foreground: "hsl(215.4 16.3% 46.9%)",
                        },
                        accent: {
                            DEFAULT: "hsl(210 40% 96%)",
                            foreground: "hsl(222.2 84% 4.9%)",
                        },
                        destructive: {
                            DEFAULT: "hsl(0 84.2% 60.2%)",
                            foreground: "hsl(210 40% 98%)",
                        },
                    },
                }
            }
        }
    </script>
    
    @yield('styles')
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar FIJO -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 shadow-sm flex flex-col">
            <!-- Header del Sidebar -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">Servicio Social</h1>
                        <p class="text-xs text-gray-500">CBTa 256</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <!--
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                -->

                <!-- Alumnos Recientes (Destacado) -->
                <a href="{{ route('alumnos.recientes') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('alumnos.recientes') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
</svg>
                    <span>Alumnos Recientes</span>
                    <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full"></div>
                </a>

                <!-- Separador -->
                <div class="border-t border-gray-200 my-4"></div>

                <!-- Secciones Colapsables -->
                <div class="space-y-1">
                    <!-- Alumnos -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('alumnoMenu')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Alumnos</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" id="alumnoMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="alumnoMenu" class="hidden ml-8 space-y-1">
                            <a href="{{ route('dashboard', ['table' => 'alumno']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Ver Alumnos</a>
                        </div>
                    </div>

                    <!-- Formatos -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('formatosMenu')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Formatos</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" id="formatosMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="formatosMenu" class="hidden ml-8 space-y-1">
                            <a href="{{ route('dashboard', ['table' => 'formatos']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Formatos</a>
                            <a href="{{ route('dashboard', ['table' => 'programa_servicio_social']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Programa Servicio</a>
                        </div>
                    </div>

                    <!-- Catálogos -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('catalogosMenu')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span>Catálogos</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" id="catalogosMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="catalogosMenu" class="hidden ml-8 space-y-1">
                            <a href="{{ route('dashboard', ['table' => 'carreras']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Carreras</a>
                            <a href="{{ route('dashboard', ['table' => 'grupos']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Grupos</a>
                            <a href="{{ route('dashboard', ['table' => 'semestres']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Semestres</a>
                            <a href="{{ route('dashboard', ['table' => 'titulos']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Títulos</a>
                            <a href="{{ route('dashboard', ['table' => 'sexo']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Sexo</a>
                            <a href="{{ route('dashboard', ['table' => 'edad']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Edad</a>
                            <a href="{{ route('dashboard', ['table' => 'status']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Status</a>
                        </div>
                    </div>

                    <!-- Ubicación -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('ubicacionMenu')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Ubicación</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" id="ubicacionMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="ubicacionMenu" class="hidden ml-8 space-y-1">
                            <a href="{{ route('dashboard', ['table' => 'estados']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Estados</a>
                            <a href="{{ route('dashboard', ['table' => 'municipios']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Municipios</a>
                            <a href="{{ route('dashboard', ['table' => 'ubicaciones']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Ubicaciones</a>
                        </div>
                    </div>

                    <!-- Instituciones -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('institucionesMenu')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Instituciones</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" id="institucionesMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="institucionesMenu" class="hidden ml-8 space-y-1">
                            <a href="{{ route('dashboard', ['table' => 'instituciones']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Instituciones</a>
                            <a href="{{ route('dashboard', ['table' => 'modalidad']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Modalidad</a>
                            <a href="{{ route('dashboard', ['table' => 'metodo_servicio']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Método Servicio</a>
                            <a href="{{ route('dashboard', ['table' => 'tipos_programa']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Tipos Programa</a>
                        </div>
                    </div>

                    <!-- Usuarios -->
                    <div class="space-y-1">
                        <button onclick="toggleSubmenu('usuariosMenu')" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
</svg>
                                <span>Usuarios</span>
                            </div>
                            <svg class="w-4 h-4 transform transition-transform" id="usuariosMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="usuariosMenu" class="hidden ml-8 space-y-1">
                            <a href="{{ route('dashboard', ['table' => 'usuario']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Usuarios</a>
                            <a href="{{ route('dashboard', ['table' => 'rol']) }}" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition-colors">Roles</a>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Footer del Sidebar -->
            <div class="p-4 border-t border-gray-200">
             

                <!-- SI ESTÁ FUERA DEL GRUPO ADMIN: -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Contenido Principal con margen para sidebar -->
        <main class="flex-1 flex flex-col overflow-hidden ml-64">
            <!-- Header FIJO -->
            <header class="fixed top-0 right-0 left-64 z-40 bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-500 mt-1">@yield('page-description', 'Panel de administración del sistema de servicio social')</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <!-- Fecha y hora actual -->
                        <div class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 01-2 2z"/>
                            </svg>
                            <span id="fechaHora">{{ now()->setTimezone('America/Mexico_City')->format('d/m/Y H:i') }}</span>
                        </div>

                        <!-- Usuario logueado -->
                        @if(session('admin_id'))
                            @php
                                $usuario = DB::table('usuario')->where('id', session('admin_id'))->first();
                            @endphp
                            @if($usuario)
                                <div class="flex items-center space-x-3 bg-blue-50 px-3 py-2 rounded-lg">
                                    <!-- Avatar del usuario -->
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold text-white">
                                        {{ strtoupper(substr($usuario->nombre, 0, 1)) }}{{ strtoupper(substr($usuario->apellidoP, 0, 1)) }}
                                    </div>
                                    
                                    <!-- Info del usuario -->
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $usuario->nombre }} {{ $usuario->apellidoP }}</div>
                                        <div class="text-gray-500 text-xs">
                                            @php
                                                $rol = DB::table('rol')->where('id', $usuario->rol_id)->first();
                                            @endphp
                                            {{ $rol ? $rol->tipo : 'Usuario' }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Invitado</span>
                            </div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Contenido de la página con espaciado para header -->
            <div class="flex-1 overflow-y-auto pt-20 pb-6">
                <div class="px-6">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Actualizar fecha y hora cada minuto
        function actualizarFechaHora() {
            const ahora = new Date();
            
            // Configurar timezone de México
            const opciones = {
                timeZone: 'America/Mexico_City',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };
            
            const fechaHoraFormateada = ahora.toLocaleDateString('es-MX', opciones).replace(',', '');
            
            const elementoFecha = document.getElementById('fechaHora');
            if (elementoFecha) {
                elementoFecha.textContent = fechaHoraFormateada;
            }
        }

        // Actualizar cada minuto
        setInterval(actualizarFechaHora, 60000);
        
        // Actualizar inmediatamente al cargar la página
        actualizarFechaHora();

        function toggleSubmenu(id) {
            const menu = document.getElementById(id);
            const icon = document.getElementById(id + 'Icon');
            
            menu.classList.toggle('hidden');
            
            if (icon) {
                icon.classList.toggle('rotate-180');
            }
        }

        // Cerrar menús cuando se hace clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('button[onclick*="toggleSubmenu"]') && !event.target.closest('[id*="Menu"]')) {
                const menus = document.querySelectorAll('[id$="Menu"]');
                const icons = document.querySelectorAll('[id$="MenuIcon"]');
                
                menus.forEach(menu => menu.classList.add('hidden'));
                icons.forEach(icon => icon.classList.remove('rotate-180'));
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
