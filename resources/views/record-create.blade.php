@extends('layouts.blaze')

@section('title', "Añadir Registro en $selectedTable")

@section('content')
    <!-- Modal de creación -->
    <div id="createModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-1/2">
            <h1 class="text-2xl font-bold mb-4">Añadir Registro en {{ $selectedTable }}</h1>
            <!-- Contenedor con scroll para el contenido grande -->
            <div class="overflow-auto" style="max-height: 70vh;">
                <form action="{{ route('record.store', ['table' => $selectedTable]) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @foreach((array)$record as $field => $value)
                            <div>
                                <label for="{{ $field }}" class="block text-gray-700 font-semibold capitalize">{{ $field }}:</label>
                                @if(isset($foreignOptions[$field]))
                                    <!-- Renderizar select para llave foránea -->
                                    <select name="{{ $field }}" id="{{ $field }}" class="mt-1 p-2 border rounded w-full">
                                        <option value="">Seleccione...</option>
                                        @foreach($foreignOptions[$field] as $option)
                                            <option value="{{ $option->id }}">
                                                {{ $option->{$foreignOptions[$field][0]->display_column ?? 'id'} }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}" class="mt-1 p-2 border rounded w-full">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-4">
                        <a href="{{ route('dashboard', ['table' => $selectedTable]) }}" class="mr-3 px-4 py-2 bg-gray-300 rounded">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection