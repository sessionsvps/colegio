@extends('layouts.main')

@section('contenido')

    <a href="{{ route('exoneraciones.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
        Volver
    </a>
    <h2 class="text-xl lg:text-2xl font-bold my-10">Registro de Exoneraciones</h2>
    
    <form
        action="{{ route('exoneraciones.update', ['codigo_estudiante' => $estudiante->codigo_estudiante, 'año_escolar' => $estudiante->año_escolar]) }}"
        method="POST">
        @csrf
        @method('PUT')
    
        <div class="mt-10 grid lg:grid-cols-2 gap-5">
            <div class="flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $estudiante->estudiante->primer_nombre . ' ' .
                        $estudiante->estudiante->otros_nombres . ' ' . $estudiante->estudiante->apellido_paterno . ' ' .
                        $estudiante->estudiante->apellido_materno }}</h3>
                    <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{
                        $estudiante->codigo_estudiante
                        }}
                    </p>
                    <p class="text-gray-700"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}
                    </p>
                    <p class="text-gray-700"><span class="font-semibold">Aula:</span> {{
                        $estudiante->seccion->grado->detalle .
                        ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
                </div>
            </div>
            <div class="bg-gray-50 shadow-md rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4">Cursos Exonerables</h3>
                <ul class="space-y-4">
                    @foreach ($cursos_exonerables as $curso)
                    <li class="flex items-center p-4 bg-white rounded-lg shadow-sm">
                        <input type="checkbox" id="curso_exonerado_{{ $curso->codigo_curso }}" name="cursos_exonerados[]"
                            value="{{ $curso->codigo_curso }}"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" @if
                            ($exoneraciones->contains('codigo_curso', $curso->codigo_curso))
                        checked
                        @endif
                        >
                        <label for="curso_exonerado_{{ $curso->codigo_curso }}" class="ml-3 text-gray-800">
                            {{ $curso->descripcion }}
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    
        <div class="flex mt-10 items-center justify-center">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Actualizar
            </button>
        </div>
    </form>

@endsection

@section('scripts')
@endsection