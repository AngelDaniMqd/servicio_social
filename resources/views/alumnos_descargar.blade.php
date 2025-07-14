@extends('layouts.blaze')

@section('title', 'Alumnos Recientes')
@section('page-title', 'Alumnos Recientes')
@section('page-description', 'Listado de alumnos registrados recientemente con acceso a sus documentos')

@section('content')
<!-- Agregar espaciado adicional desde el header -->
<div class="pt-8 space-y-10"> <!-- Cambiar de space-y-10 a pt-8 space-y-10 -->
    @if($alumnos->count() > 0)
        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Alumnos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alumnos->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Documentos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alumnos->count() * 3 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h8m-6 0v1a2 2 0 01-2 2H5a2 2 0 01-2-2V8m0 0V6a2 2 0 012-2h2M7 10h10l-1 8H8l-1-8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Registros Hoy</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alumnos->where('fecha_registro', '>=', now()->startOfDay())->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de alumnos -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header de la tabla -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-semibold text-gray-900">Listado de Alumnos</h3>
                    <div class="flex items-center gap-2">
                       
                    </div>
                </div>
            </div>

            <!-- Tabla responsive -->
            <div class="overflow-x-auto">
                <table class="w-full" id="alumnosTable">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alumno
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Registro
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Documentos Disponibles
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($alumnos as $index => $alumno)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-4">
                                            {{ strtoupper(substr($alumno->nombre, 0, 1) . substr($alumno->apellido_p, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $alumno->nombre }} {{ $alumno->apellido_p }} {{ $alumno->apellido_m }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: {{ $alumno->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($alumno->fecha_registro)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($alumno->fecha_registro)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                                        <a href="{{ route('formatos.download', ['id' => $alumno->id, 'tipo' => 'word']) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors w-full sm:w-auto justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Carta
                                        </a>
                                        <a href="{{ route('formatos.download', ['id' => $alumno->id, 'tipo' => 'reporte']) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors w-full sm:w-auto justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Reporte
                                        </a>
                                        <a href="{{ route('formatos.download', ['id' => $alumno->id, 'tipo' => 'reporte_final']) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg hover:bg-purple-200 transition-colors w-full sm:w-auto justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            Reporte Final
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                <div class="text-sm text-gray-700">
                    Mostrando <span class="font-medium">{{ $alumnos->count() }}</span> alumnos registrados recientemente
                </div>
            </div>
        </div>
    @else
        <!-- Mensaje cuando no hay alumnos -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay alumnos registrados</h3>
            <p class="text-gray-500 mb-6">No hay alumnos registrados recientemente. Los nuevos registros aparecerán aquí.</p>
            <a href="{{ route('record.create', ['table' => 'alumno']) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Registrar Alumno
            </a>
        </div>
    @endif
</div>

<script>
function exportTable() {
    // Función simple para exportar datos (puedes implementar CSV, Excel, etc.)
    alert('Función de exportación - Por implementar');
}
</script>
@endsection