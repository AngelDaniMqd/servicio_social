@extends('layouts.blaze')

@section('title', "Añadir Registro en $selectedTable")

@section('content')
    <!-- Modal de creación -->
    <div id="createModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow-lg w-1/2 overflow-auto" style="max-height: 90vh;">
            <h1 class="text-2xl font-bold mb-4">Añadir Registro en {{ $selectedTable }}</h1>
            <form action="{{ route('record.store', ['table' => $selectedTable]) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Campos generales para cualquier tabla -->
                    @foreach((array)$record as $field => $value)
                        @if($selectedTable !== 'alumno' || ($selectedTable === 'alumno' && $field !== 'ubicaciones_id'))
                            <div>
                                <label for="{{ $field }}" class="block text-gray-700 font-semibold capitalize">{{ $field }}:</label>
                                @if($field === 'fecha_registro')
                                    <input type="date" name="{{ $field }}" id="{{ $field }}" 
                                           value="{{ old($field) }}" 
                                           class="mt-1 p-2 border rounded w-full">
                                @elseif(isset($foreignMapping[$field]))
                                    <select name="{{ $field }}" id="{{ $field }}" class="mt-1 p-2 border rounded w-full">
                                        <option value="">Seleccione</option>
                                        @foreach($foreignOptions[$field] as $option)
                                            <option value="{{ $option->id }}" {{ old($field)==$option->id ? 'selected' : '' }}>
                                                {{ $option->{$foreignMapping[$field]['display_column']} }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}" class="mt-1 p-2 border rounded w-full">
                                @endif
                            </div>
                        @endif
                    @endforeach

                    @if($selectedTable === 'alumno')
                        <!-- Sección: Ubicaciones -->
                        <fieldset class="mb-4 border p-4 rounded">
                            <legend class="font-bold text-lg">Ubicaciones</legend>
                            <div class="mb-2">
                                <label for="ubicacion_localidad" class="block text-gray-700">Localidad:</label>
                                <input type="text" name="ubicacion_localidad" id="ubicacion_localidad" value="{{ old('ubicacion_localidad') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="ubicacion_cp" class="block text-gray-700">Código Postal:</label>
                                <input type="number" name="ubicacion_cp" id="ubicacion_cp" value="{{ old('ubicacion_cp') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="ubicacion_estado_id" class="block text-gray-700">Estado:</label>
                                <select name="ubicacion_estado_id" id="ubicacion_estado_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione un estado</option>
                                    @foreach(DB::table('estados')->select('id','nombre')->get() as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="ubicacion_municipios_id" class="block text-gray-700">Municipio:</label>
                                <!-- Se llenará dinámicamente vía AJAX -->
                                <select name="ubicacion_municipios_id" id="ubicacion_municipios_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione un municipio</option>
                                </select>
                            </div>
                        </fieldset>

                        <!-- Sección: Programa Servicio Social -->
                        <fieldset class="mb-4 border p-4 rounded">
                            <legend class="font-bold text-lg">Programa Servicio Social</legend>
                            <div class="mb-2">
                                <label for="programa_instituciones_id" class="block text-gray-700">Institución:</label>
                                <select name="programa_instituciones_id" id="programa_instituciones_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['instituciones_id']))
                                        @foreach($foreignOptions['instituciones_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('programa_instituciones_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="programa_nombre_programa" class="block text-gray-700">Nombre del Programa:</label>
                                <input type="text" name="programa_nombre_programa" id="programa_nombre_programa" value="{{ old('programa_nombre_programa') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_encargado_nombre" class="block text-gray-700">Nombre del Encargado:</label>
                                <input type="text" name="programa_encargado_nombre" id="programa_encargado_nombre" value="{{ old('programa_encargado_nombre') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_titulos_id" class="block text-gray-700">Titulos:</label>
                                <select name="programa_titulos_id" id="programa_titulos_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['titulos_id']))
                                        @foreach($foreignOptions['titulos_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('programa_titulos_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->titulo }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="programa_puesto_encargado" class="block text-gray-700">Puesto del Encargado:</label>
                                <input type="text" name="programa_puesto_encargado" id="programa_puesto_encargado" value="{{ old('programa_puesto_encargado') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_metodo_servicio_id" class="block text-gray-700">Método de Servicio:</label>
                                <select name="programa_metodo_servicio_id" id="programa_metodo_servicio_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['metodo_servicio_id']))
                                        @foreach($foreignOptions['metodo_servicio_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('programa_metodo_servicio_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->metodo }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="programa_telefono_institucion" class="block text-gray-700">Teléfono Institución:</label>
                                <input type="number" name="programa_telefono_institucion" id="programa_telefono_institucion" value="{{ old('programa_telefono_institucion') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_fecha_inicio" class="block text-gray-700">Fecha de Inicio:</label>
                                <input type="date" name="programa_fecha_inicio" id="programa_fecha_inicio" value="{{ old('programa_fecha_inicio') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_fecha_final" class="block text-gray-700">Fecha Final:</label>
                                <input type="date" name="programa_fecha_final" id="programa_fecha_final" value="{{ old('programa_fecha_final') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_tipos_programa_id" class="block text-gray-700">Tipos de Programa:</label>
                                <select name="programa_tipos_programa_id" id="programa_tipos_programa_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['programa_status_id'])) {{-- Se podría usar el mapeo en status o bien un mapeo propio --}}
                                        @foreach($foreignOptions['programa_status_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('programa_tipos_programa_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->tipo }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="programa_otro_programa" class="block text-gray-700">Otro Programa:</label>
                                <input type="text" name="programa_otro_programa" id="programa_otro_programa" value="{{ old('programa_otro_programa') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_status_id" class="block text-gray-700">Status:</label>
                                <select name="programa_status_id" id="programa_status_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['status_id']))
                                        @foreach($foreignOptions['status_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('programa_status_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->tipo }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </fieldset>

                        <!-- Sección: Escolaridad Alumno -->
                        <fieldset class="mb-4 border p-4 rounded">
                            <legend class="font-bold text-lg">Escolaridad Alumno</legend>
                            <div class="mb-2">
                                <label for="escolaridad_numero_control" class="block text-gray-700">Número de Control:</label>
                                <input type="number" name="escolaridad_numero_control" id="escolaridad_numero_control" value="{{ old('escolaridad_numero_control') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_meses_servicio" class="block text-gray-700">Meses de Servicio:</label>
                                <input type="number" name="escolaridad_meses_servicio" id="escolaridad_meses_servicio" value="{{ old('escolaridad_meses_servicio') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_modalidad_id" class="block text-gray-700">Modalidad:</label>
                                <select name="escolaridad_modalidad_id" id="escolaridad_modalidad_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['escolaridad_modalidad_id']))
                                        @foreach($foreignOptions['escolaridad_modalidad_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('escolaridad_modalidad_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_carreras_id" class="block text-gray-700">Carreras:</label>
                                <select name="escolaridad_carreras_id" id="escolaridad_carreras_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['escolaridad_carreras_id']))
                                        @foreach($foreignOptions['escolaridad_carreras_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('escolaridad_carreras_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_semestres_id" class="block text-gray-700">Semestres:</label>
                                <select name="escolaridad_semestres_id" id="escolaridad_semestres_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['escolaridad_semestres_id']))
                                        @foreach($foreignOptions['escolaridad_semestres_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('escolaridad_semestres_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->nombre }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_grupos_id" class="block text-gray-700">Grupos:</label>
                                <select name="escolaridad_grupos_id" id="escolaridad_grupos_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione</option>
                                    @if(isset($foreignOptions['escolaridad_grupos_id']))
                                        @foreach($foreignOptions['escolaridad_grupos_id'] as $option)
                                            <option value="{{ $option->id }}" {{ old('escolaridad_grupos_id')==$option->id ? 'selected' : '' }}>
                                                {{ $option->letra }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </fieldset>
                    @endif
                </div>
                <div class="flex justify-end mt-4">
                    <a href="{{ route('dashboard', ['table' => $selectedTable]) }}" class="mr-3 px-4 py-2 bg-gray-300 rounded">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                        Guardar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts: Carga dinámica de municipios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function(){
            $('#ubicacion_estado_id').change(function(){
                var estadoId = $(this).val();
                if(estadoId){
                    $.ajax({
                        url: '/municipios-por-estado/' + estadoId,
                        dataType: 'json',
                        success: function(data){
                            var opciones = '<option value="">Seleccione un municipio</option>';
                            $.each(data, function(i, municipio){
                                opciones += '<option value="'+ municipio.id +'">'+ municipio.nombre +'</option>';
                            });
                            $('#ubicacion_municipios_id').html(opciones);
                        }
                    });
                } else {
                    $('#ubicacion_municipios_id').html('<option value="">Seleccione un municipio</option>');
                }
            });
        });
    </script>
@endsection