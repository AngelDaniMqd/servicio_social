@extends('layouts.blaze')

@section('title', 'Dashboard - Tablas')

@section('content')
    <div>
        @if ($selectedTable)
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-bold">Tabla: {{ $selectedTable }}</h1>
                <a href="{{ route('record.create', ['table' => $selectedTable]) }}" class="px-4 py-2 bg-green-500 text-white rounded">
                    + Añadir Registro
                </a>
            </div>
            @if($rows->count() > 0)
                <!-- Contenedor uniforme para todas las tablas con scroll -->
                <div class="overflow-auto" style="max-height: 600px;">
                    <table class="min-w-full border-collapse border border-gray-400">
                        <thead>
                            <tr class="bg-gray-200">
                                @foreach(array_keys((array)$rows->first()) as $col)
                                    <th class="border border-gray-400 p-2">{{ $col }}</th>
                                @endforeach
                                <th class="border border-gray-400 p-2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    @foreach ((array)$row as $value)
                                        <td class="border border-gray-400 p-2">{{ $value }}</td>
                                    @endforeach
                                    <td class="border border-gray-400 p-2">
                                        <a href="{{ route('record.edit', ['table' => $selectedTable, 'id' => $row->id]) }}"
                                           class="bg-green-500 text-white px-2 py-1 rounded">Editar</a>
                                        <form action="{{ route('record.delete', ['table' => $selectedTable, 'id' => $row->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Eliminar registro?')" class="bg-red-500 text-white px-2 py-1 rounded">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No existen registros en <strong>{{ $selectedTable }}</strong>.</p>
            @endif
        @else
            <!-- Mostrar listado de tablas -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold mb-4">Vista General de Tablas</h1>
                <div>
                    <h2 class="text-xl font-semibold mb-2">Tablas de la Base de Datos</h2>
                    <ul class="flex flex-wrap gap-4">
                        @foreach ($tables as $table)
                            <li>
                                <a href="{{ route('dashboard', ['table' => $table]) }}"
                                   class="block py-1 px-2 hover:bg-blue-100 rounded">{{ $table }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <p class="text-lg">Selecciona una tabla desde la lista de arriba para ver sus registros.</p>
        @endif
    </div>
@endsection