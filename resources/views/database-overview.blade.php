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
           
        @endif
    </div>
@endsection