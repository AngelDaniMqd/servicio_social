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
                                            <option value="{{ $option->id }}" {{ old($field) == $option->id ? 'selected' : '' }}>
                                                {{ $option->id . " = " . $option->{$foreignMapping[$field]['display_column']} }}
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
                                        <option value="{{ $estado->id }}">{{ $estado->id . " = " . $estado->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="ubicacion_municipios_id" class="block text-gray-700">Municipio:</label>
                                <!-- Se llenará dinámicamente vía AJAX en función del estado seleccionado -->
                                <select name="ubicacion_municipios_id" id="ubicacion_municipios_id" class="mt-1 p-2 border rounded w-full">
                                    <option value="">Seleccione un municipio</option>
                                </select>
                            </div>
                        </fieldset>

                        <!-- Sección: Programa de Servicio Social -->
                        <fieldset class="mb-4 border p-4 rounded">
                            <legend class="font-bold text-lg">Programa Servicio Social</legend>
                            <div class="mb-2">
                                <label for="programa_instituciones_id" class="block text-gray-700">Institución:</label>
                                <input type="number" name="programa_instituciones_id" id="programa_instituciones_id" value="{{ old('programa_instituciones_id') }}"
                                       class="mt-1 p-2 border rounded w-full" placeholder="ID de la institución">
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
                                <label for="programa_titulos_id" class="block text-gray-700">Titulos ID:</label>
                                <input type="number" name="programa_titulos_id" id="programa_titulos_id" value="{{ old('programa_titulos_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_puesto_encargado" class="block text-gray-700">Puesto del Encargado:</label>
                                <input type="text" name="programa_puesto_encargado" id="programa_puesto_encargado" value="{{ old('programa_puesto_encargado') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_metodo_servicio_id" class="block text-gray-700">Método de Servicio ID:</label>
                                <input type="number" name="programa_metodo_servicio_id" id="programa_metodo_servicio_id" value="{{ old('programa_metodo_servicio_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
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
                                <label for="programa_tipos_programa_id" class="block text-gray-700">Tipos Programa ID:</label>
                                <input type="number" name="programa_tipos_programa_id" id="programa_tipos_programa_id" value="{{ old('programa_tipos_programa_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_otro_programa" class="block text-gray-700">Otro Programa:</label>
                                <input type="text" name="programa_otro_programa" id="programa_otro_programa" value="{{ old('programa_otro_programa') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="programa_status_id" class="block text-gray-700">Status ID:</label>
                                <input type="number" name="programa_status_id" id="programa_status_id" value="{{ old('programa_status_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                        </fieldset>

                        <!-- Sección: Escolaridad Alumno -->
                        <fieldset class="mb-4 border p-4 rounded">
                            <legend class="font-bold text-lg">Escolaridad Alumno</legend>
                            <div class="mb-2">
                                <label for="escolaridad_numero_control" class="block text-gray-700">Número de Control:</label>
                                <input type="text" name="escolaridad_numero_control" id="escolaridad_numero_control" value="{{ old('escolaridad_numero_control') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_meses_servicio" class="block text-gray-700">Meses de Servicio:</label>
                                <input type="number" name="escolaridad_meses_servicio" id="escolaridad_meses_servicio" value="{{ old('escolaridad_meses_servicio') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_modalidad_id" class="block text-gray-700">Modalidad ID:</label>
                                <input type="number" name="escolaridad_modalidad_id" id="escolaridad_modalidad_id" value="{{ old('escolaridad_modalidad_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_carreras_id" class="block text-gray-700">Carreras ID:</label>
                                <input type="number" name="escolaridad_carreras_id" id="escolaridad_carreras_id" value="{{ old('escolaridad_carreras_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_semestres_id" class="block text-gray-700">Semestres ID:</label>
                                <input type="number" name="escolaridad_semestres_id" id="escolaridad_semestres_id" value="{{ old('escolaridad_semestres_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
                            </div>
                            <div class="mb-2">
                                <label for="escolaridad_grupos_id" class="block text-gray-700">Grupos ID:</label>
                                <input type="number" name="escolaridad_grupos_id" id="escolaridad_grupos_id" value="{{ old('escolaridad_grupos_id') }}"
                                       class="mt-1 p-2 border rounded w-full">
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

    <!-- Scripts: Autocompletado y carga dinámica de municipios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function(){
            // Autocompletado para campos configurados
            $('.autocomplete').autocomplete({
                source: function(request, response) {
                    var input = this.element;
                    var foreignTable = $(input).data('foreign-table');
                    var displayColumn = $(input).data('display-column');
                    $.ajax({
                        url: "{{ route('autocomplete', ['table' => 'dummy', 'column' => 'dummy']) }}"
                                .replace('dummy', foreignTable)
                                .replace('dummy', displayColumn),
                        dataType: "json",
                        data: { term: request.term },
                        success: function(data) {
                            response($.map(data, function(item){
                                return {
                                    label: item[displayColumn],
                                    value: item.id + " = " + item[displayColumn]
                                };
                            }));
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui){
                    $(this).val(ui.item.value);
                    return false;
                }
            });

            // Carga dinámica de municipios según el estado seleccionado
            $('#ubicacion_estado_id').change(function(){
                var estadoId = $(this).val();
                if(estadoId){
                    $.ajax({
                        url: '/municipios-por-estado/' + estadoId,
                        dataType: 'json',
                        success: function(data){
                            var opciones = '<option value="">Seleccione un municipio</option>';
                            $.each(data, function(i, municipio){
                                opciones += '<option value="'+ municipio.id +'">'+ municipio.id +' = '+ municipio.nombre +'</option>';
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