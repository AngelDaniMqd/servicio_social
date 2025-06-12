
@extends('layouts.blaze')

@section('title', 'Crear Registro')
@section('page-title', 'Crear Nuevo Registro')
@section('page-description', 'Agregar un nuevo registro a la tabla ' . ucfirst(str_replace('_', ' ', $table)))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Nuevo {{ ucfirst(str_replace('_', ' ', $table)) }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Complete todos los campos requeridos para crear el registro
                    </p>
                </div>
                <a href="{{ route('dashboard', ['table' => $table]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('record.store', ['table' => $table]) }}" class="p-6">
            @csrf
            
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($fields as $fieldName => $fieldInfo)
                    <div class="{{ in_array($fieldName, ['nombre_programa', 'encargado_nombre', 'puesto_encargado', 'otra_institucion', 'otro_programa']) ? 'md:col-span-2' : '' }}">
                        <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $fieldInfo['label'] }}
                            @if(!$fieldInfo['nullable'])
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if(isset($relationships[$fieldName]))
                            <!-- Select para relaciones -->
                            <select name="{{ $fieldName }}" 
                                    id="{{ $fieldName }}" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                                <option value="">Seleccionar {{ $fieldInfo['label'] }}</option>
                                @foreach($relationships[$fieldName] as $option)
                                    <option value="{{ $option->id ?? $option['id'] }}" {{ old($fieldName) == ($option->id ?? $option['id']) ? 'selected' : '' }}>
                                        {{ $option->text ?? $option['text'] }}
                                    </option>
                                @endforeach
                            </select>
                        @elseif(in_array($fieldInfo['type'], ['date', 'datetime']))
                            <!-- Input para fechas -->
                            <input type="{{ $fieldInfo['type'] === 'datetime' ? 'datetime-local' : 'date' }}" 
                                   name="{{ $fieldName }}" 
                                   id="{{ $fieldName }}" 
                                   value="{{ old($fieldName) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                        @elseif($fieldInfo['type'] === 'integer' && str_contains($fieldName, 'telefono'))
                            <!-- Input para teléfonos -->
                            <input type="tel" 
                                   name="{{ $fieldName }}" 
                                   id="{{ $fieldName }}" 
                                   value="{{ old($fieldName) }}"
                                   placeholder="Número de teléfono"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                        @elseif($fieldInfo['type'] === 'integer')
                            <!-- Input para números -->
                            <input type="number" 
                                   name="{{ $fieldName }}" 
                                   id="{{ $fieldName }}" 
                                   value="{{ old($fieldName) }}"
                                   placeholder="Ingrese {{ strtolower($fieldInfo['label']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                        @elseif(str_contains($fieldName, 'email') || str_contains($fieldName, 'correo'))
                            <!-- Input para email -->
                            <input type="email" 
                                   name="{{ $fieldName }}" 
                                   id="{{ $fieldName }}" 
                                   value="{{ old($fieldName) }}"
                                   placeholder="correo@ejemplo.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                        @elseif(str_contains($fieldName, 'password'))
                            <!-- Input para contraseña -->
                            <input type="password" 
                                   name="{{ $fieldName }}" 
                                   id="{{ $fieldName }}" 
                                   placeholder="Contraseña"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                        @else
                            <!-- Input de texto general -->
                            <input type="text" 
                                   name="{{ $fieldName }}" 
                                   id="{{ $fieldName }}" 
                                   value="{{ old($fieldName) }}"
                                   placeholder="Ingrese {{ strtolower($fieldInfo['label']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   {{ !$fieldInfo['nullable'] ? 'required' : '' }}>
                        @endif

                        @error($fieldName)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-end pt-6 border-t border-gray-200 mt-6 space-x-3">
                <a href="{{ route('dashboard', ['table' => $table]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Crear Registro
                </button>
            </div>
        </form>
    </div>
</div>
@endsection