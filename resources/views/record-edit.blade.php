@extends('layouts.blaze')

@section('title', "Editar Registro en $selectedTable")

@section('content')
    <!-- Modal de edición -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-1/2">
            <h1 class="text-2xl font-bold mb-4">Editar Registro en {{ $selectedTable }}</h1>
            <form action="{{ route('record.update', ['table' => $selectedTable, 'id' => $record->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    @foreach((array)$record as $field => $value)
                        @if($field !== 'id')
                            <div>
                                <label for="{{ $field }}" class="block text-gray-700 font-semibold capitalize">{{ $field }}:</label>
                                @if(isset($foreignOptions[$field]))
                                    <!-- Renderizar select para llave foránea -->
                                    <select name="{{ $field }}" id="{{ $field }}" class="mt-1 p-2 border rounded w-full">
                                        @foreach($foreignOptions[$field] as $option)
                                            <option value="{{ $option->id }}"
                                                {{ $option->id == $value ? 'selected' : '' }}>
                                                {{ $option->{$foreignOptions[$field][0]->display_column ?? 'id'} }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <!-- Input de texto genérico -->
                                    <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ $value }}" class="mt-1 p-2 border rounded w-full">
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeModal()" class="mr-3 px-4 py-2 bg-gray-300 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function closeModal(){
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
@endsection