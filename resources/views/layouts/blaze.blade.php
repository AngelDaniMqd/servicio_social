<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Servicio Social')</title>

    <!-- Tailwind CSS y Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" />

    @yield('styles')
</head>
<body class="bg-gray-100 text-gray-900 flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white p-5 border-r border-gray-300 flex flex-col justify-between">
        <div>
            <h1 class="text-lg font-bold mb-5">Servicio Social</h1>
            <nav>
                <ul class="space-y-2">
                    <!-- Alumno -->
                    <li>
                        <button onclick="toggleSubmenu('alumnoMenu')" class="w-full flex items-center justify-between text-left text-gray-700 font-semibold hover:text-blue-600">
                            👨‍🎓 Alumno
                            <span>▼</span>
                        </button>
                        <ul id="alumnoMenu" class="ml-4 mt-2 hidden text-sm text-gray-600">
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'alumno']) }}" class="block py-1 hover:text-blue-500">
                                    Alumno
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'escolaridad_alumno']) }}" class="block py-1 hover:text-blue-500">
                                    Escolaridad
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Formatos -->
                    <li>
                        <button onclick="toggleSubmenu('formatosMenu')" class="w-full flex items-center justify-between text-left text-gray-700 font-semibold hover:text-blue-600">
                            📝 Formatos
                            <span>▼</span>
                        </button>
                        <ul id="formatosMenu" class="ml-4 mt-2 hidden text-sm text-gray-600">
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'formatos']) }}" class="block py-1 hover:text-blue-500">
                                    Formatos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'programa_servicio_social']) }}" class="block py-1 hover:text-blue-500">
                                    Programa Servicio
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Catálogos -->
                    <li>
                        <button onclick="toggleSubmenu('catalogosMenu')" class="w-full flex items-center justify-between text-left text-gray-700 font-semibold hover:text-blue-600">
                            📚 Catálogos
                            <span>▼</span>
                        </button>
                        <ul id="catalogosMenu" class="ml-4 mt-2 hidden text-sm text-gray-600">
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'carreras']) }}" class="block py-1 hover:text-blue-500">
                                    Carreras
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'grupos']) }}" class="block py-1 hover:text-blue-500">
                                    Grupos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'semestres']) }}" class="block py-1 hover:text-blue-500">
                                    Semestres
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'titulos']) }}" class="block py-1 hover:text-blue-500">
                                    Títulos
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'sexo']) }}" class="block py-1 hover:text-blue-500">
                                    Sexo
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'edad']) }}" class="block py-1 hover:text-blue-500">
                                    Edad
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'status']) }}" class="block py-1 hover:text-blue-500">
                                    Status
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Ubicación -->
                    <li>
                        <button onclick="toggleSubmenu('ubicacionMenu')" class="w-full flex items-center justify-between text-left text-gray-700 font-semibold hover:text-blue-600">
                            📍 Ubicación
                            <span>▼</span>
                        </button>
                        <ul id="ubicacionMenu" class="ml-4 mt-2 hidden text-sm text-gray-600">
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'estados']) }}" class="block py-1 hover:text-blue-500">
                                    Estados
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'municipios']) }}" class="block py-1 hover:text-blue-500">
                                    Municipios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'ubicaciones']) }}" class="block py-1 hover:text-blue-500">
                                    Ubicaciones
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Instituciones -->
                    <li>
                        <button onclick="toggleSubmenu('institucionesMenu')" class="w-full flex items-center justify-between text-left text-gray-700 font-semibold hover:text-blue-600">
                            🏫 Instituciones
                            <span>▼</span>
                        </button>
                        <ul id="institucionesMenu" class="ml-4 mt-2 hidden text-sm text-gray-600">
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'instituciones']) }}" class="block py-1 hover:text-blue-500">
                                    Instituciones
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'modalidad']) }}" class="block py-1 hover:text-blue-500">
                                    Modalidad
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'metodo_servicio']) }}" class="block py-1 hover:text-blue-500">
                                    Método Servicio
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'tipos_programa']) }}" class="block py-1 hover:text-blue-500">
                                    Tipos Programa
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Usuarios -->
                    <li>
                        <button onclick="toggleSubmenu('usuariosMenu')" class="w-full flex items-center justify-between text-left text-gray-700 font-semibold hover:text-blue-600">
                            👥 Usuarios
                            <span>▼</span>
                        </button>
                        <ul id="usuariosMenu" class="ml-4 mt-2 hidden text-sm text-gray-600">
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'usuario']) }}" class="block py-1 hover:text-blue-500">
                                    Usuarios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['table' => 'rol']) }}" class="block py-1 hover:text-blue-500">
                                    Roles
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="mt-6">
                        <form method="POST" action="#">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">
                                🔐 Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        function toggleSubmenu(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
    @yield('scripts')
</body>
</html>
