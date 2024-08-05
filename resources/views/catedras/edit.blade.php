@extends('layouts.main')

@section('contenido')
    <div>
        <h2 class="text-xl font-bold mb-4">Modificar Docente Asignado a la Cátedra</h2>
        
        <div class="bg-gray-50 rounded px-8 py-6 mb-4">
            <form method="POST" action="{{ route('catedras.custom-update', ['codigo_curso' => $curso->codigo_curso, 'nivel' => $nivel, 'grado' => $grado, 'seccion' => $seccion]) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="codigo_curso" value="{{ $curso->codigo_curso }}">
                <input type="hidden" name="id_nivel" value="{{ $nivel }}">
                <input type="hidden" name="id_grado" value="{{ $grado }}">
                <input type="hidden" name="id_seccion" value="{{ $seccion }}">
                <input type="hidden" name="año_escolar" value="{{ $año }}">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="curso">
                        Curso
                    </label>
                    <input type="text" id="curso" name="curso" value="{{ $curso->descripcion }}" readonly
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nivel">
                        Nivel
                    </label>
                    <input type="text" id="nivel" name="nivel_text" value="{{ $aula->grado->nivel->detalle }}" readonly
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="grado">
                        Grado
                    </label>
                    <input type="text" id="grado" name="grado_text" value="{{ $aula->grado->detalle }}" readonly
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="seccion">
                        Sección
                    </label>
                    <input type="text" id="seccion" name="seccion_text" value="{{ $aula->detalle }}" readonly
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200">
                </div>


                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="docente_actual">
                        Docente Actual
                    </label>
                    <input type="text" id="docente_actual" name="docente_actual" value="{{ $catedra->docente->primer_nombre }} {{ $catedra->docente->apellido_paterno }} {{ $catedra->docente->apellido_materno }}" readonly
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-200">
                </div>

                <!-- Campo de selección de Docente -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="docente">
                        Nuevo Docente
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="docente" name="codigo_docente" required>
                        <option value="" selected disabled>Seleccione un docente</option>
                        @foreach($docentes as $docente)
                            @if($docente->codigo_docente != $catedra->docente->codigo_docente)
                                <option value="{{ $docente->codigo_docente }}">
                                    {{ $docente->primer_nombre }} {{ $docente->apellido_paterno }} {{ $docente->apellido_materno }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('codigo_docente')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botón de envío -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Modificar Docente
                    </button>
                    <a href="{{ route('catedras.cancelar', ['nivel' => $nivel, 'grado' => $grado, 'seccion' => $seccion]) }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
