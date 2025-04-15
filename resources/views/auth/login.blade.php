<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrativos del CBTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex flex-col justify-center items-center h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-md w-96 text-center">
        <img src="{{ asset('logo.png') }}" alt="Logo CBTA" class="mx-auto w-16 h-16 mb-4">
        <h1 class="text-xl font-bold mb-4 text-blue-400">CBTA Administrativos</h1>
        <h2 class="text-lg font-semibold mb-4">Login de Administrativos</h2>
        
        @if ($errors->any())
            <div class="bg-red-600 text-white p-3 rounded-md mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.auth') }}" id="loginForm">
            @csrf
            <div class="mb-4 text-left">
                <label class="block text-gray-300">Correo electrónico</label>
                <input type="email" name="correo" class="w-full p-2 border rounded-md bg-gray-700 text-white" value="{{ old('correo') }}" required>
            </div>

            <div class="mb-4 text-left relative">
                <label class="block text-gray-300">Contraseña</label>
                <input type="password" name="password" id="password" class="w-full p-2 pr-10 border rounded-md bg-gray-700 text-white" required>
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-6 ml-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />
                    </svg>
                </button>
            </div>

        

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 w-full rounded-md hover:bg-blue-700">
                Iniciar Sesión
            </button>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Cambiar el icono según el estado
            if (type === 'text') {
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.418 0-8-3.582-8-8 0-1.09.222-2.125.625-3.08m1.375-2.42A9.986 9.986 0 0112 5c4.418 0 8 3.582 8 8 0 1.09-.222 2.125-.625 3.08m-1.375 2.42a10.05 10.05 0 01-2.375 2.375M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
            } else {
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7S3.732 16.057 2.458 12z" />';
            }
        });
    </script>
</body>
</html>