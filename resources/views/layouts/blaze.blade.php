<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Multiverso Comics - Admin')</title>
    
    <!-- Tailwind CSS y Flowbite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" />

    @yield('styles')
</head>
<body class="bg-gray-900 text-white flex h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 p-5 flex flex-col justify-between">
        <div>
            <h1 class="text-lg font-bold mb-5">Multiverso Comics</h1>
            <nav>
                <ul>
                    <li class="mb-3"><a href="{{ route('caja.index') }}" class="text-gray-400 hover:text-white">📦 Caja</a></li>
                    <li class="mb-3"><a href="{{ route('devoluciones.index') }}" class="text-gray-400 hover:text-white">🔄 Devoluciones</a></li>
                    <!-- Ejemplo en layouts/blaze.blade.php (dentro de la lista del sidebar) -->
<li class="mb-3"><a href="{{ route('inventario.index') }}" class="text-gray-400 hover:text-white">📦 Inventario</a></li>
                    <li class="mb-3"><a href="{{ route('proveedores.index') }}" class="text-gray-400 hover:text-white">📦 Proveedores</a></li>
                    <li class="mb-3"><a href="{{ route('empleados.index') }}" class="text-gray-400 hover:text-white">🧑‍💼 Empleados</a></li>
                    <li class="mb-3"><a href="{{ route('membresias.index') }}" class="text-gray-400 hover:text-white">👤 Clientes</a></li>
                    <li class="mb-3"><a href="{{ route('historial.index') }}" class="text-gray-400 hover:text-white">📜 Hist. Ventas</a></li>
                    <li class="mb-3"><a href="{{ route('corte.index') }}" class="text-gray-400 hover:text-white">💰 Corte de caja</a></li>
                    <li class="mb-3"><a href="{{ route('notificaciones.index') }}" class="text-yellow-400 hover:text-white">🔔 Notificaciones</a></li>
                    <li class="mb-3">
                        <a href="#"
                           onclick="event.preventDefault(); localStorage.removeItem('user_authenticated'); document.getElementById('logout-form').submit();"
                           class="text-red-400 hover:text-white">
                            🔐 Cerrar sesión
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('cliente.login') }}" class="text-gray-400 hover:text-white">
                            🚀 Vista Cliente
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

    <!-- Formulario de Logout Oculto -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

    <script>
        function logout() {
            localStorage.removeItem("user_authenticated");
            window.location.assign("{{ route('login') }}");
            window.location.reload();
        }
    </script>

    @yield('scripts')

</body>
</html>
