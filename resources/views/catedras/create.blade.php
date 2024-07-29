@extends('layouts.main')

@section('contenido')
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Asignar Docente
                @if(isset($aula))
                    {{ $aula->grado->detalle }} {{ $aula->detalle }} <span> de </span> {{ $aula->grado->nivel->detalle }}
                @endif
            </h2>
        </div>
        <div class="bg-gray-50 rounded px-8 py-6 mb-4">
            <form method="POST" action="{{ route('catedras.store') }}">
                @csrf
                <input type="hidden" name="codigo_curso" value="{{ $curso->codigo_curso }}">
                <input type="hidden" name="id_nivel" value="{{ $nivel }}">
                <input type="hidden" name="id_grado" value="{{ $grado }}">
                <input type="hidden" name="id_seccion" value="{{ $seccion }}">
                <input type="hidden" name="año_escolar" value="2024"> {{-- Ajusta el año escolar según sea necesario --}}

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
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="docente">
                        Docente
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="docente" name="codigo_docente" required>
                        <option value="" selected disabled>Seleccione un docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->codigo_docente }}">
                                {{ $docente->primer_nombre }} {{ $docente->apellido_paterno }} {{ $docente->apellido_materno }}
                            </option>
                        @endforeach
                    </select>
                    @error('codigo_docente')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Asignar Docente
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection