@extends('layouts.main')

@section('contenido')
<!-- Información del Curso -->
<div class="mb-6 flex flex-col md:flex-row items-center md:justify-between bg-gray-50 shadow-lg rounded-lg p-6">
    <div class="flex-1">
        <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-800 mb-2">{{ $curso->descripcion }}</h2>
        <p class="text-gray-600 sm:text-lg lg:text-xl mt-3"><span class="font-semibold">Código:</span> {{
            $curso->codigo_curso }}</p>
        <p class="text-gray-600 sm:text-lg lg:text-xl mt-1"><span class="font-semibold">Año de actualización:</span> {{
            $curso->año_actualizacion }}</p>
    </div>
    <div class="hidden md:block flex-shrink-0 mt-4 lg:mt-0">
        <img src="{{ asset("img/info_cursos/$curso->codigo_curso.webp") }}" alt="Imagen del Curso"
        class="w-48 h-48 lg:w-64 lg:h-64 object-cover rounded-full shadow-md">
    </div>
</div>

<!-- Competencias del Curso -->
<div class="mb-6 bg-gray-50 shadow-lg rounded-lg p-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Competencias</h3>
    <ul class="space-y-4">
        @forelse ($competencias as $competencia)
        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
            <div class="flex-shrink-0 mr-4">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                </svg>
            </div>
            <div class="flex-1 text-gray-800">
                {{ $competencia->descripcion }}
            </div>
        </li>
        @empty
        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
            <div class="flex-shrink-0 mr-4">
                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                </svg>
            </div>
            <div class="flex-1 text-gray-800">
                No hay Competencias para este curso
            </div>
        </li>
        @endforelse
    </ul>
</div>

@if(!Auth::user()->hasRole('Docente'))
    <div class="mb-6 bg-gray-50 shadow-lg rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800">Docentes</h3>
        <div class="mt-6 relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
                <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Código
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre Completo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Correo
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($docentes as $docente)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $docente->codigo_docente }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $docente->primer_nombre . ' ' . $docente->otros_nombres .
                            ' ' .
                            $docente->apellido_paterno . ' ' .
                            $docente->apellido_materno }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $docente->email }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center">
                            No hay docentes asignados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

@if(Auth::user()->hasRole('Docente'))
<div class="mb-6 bg-gray-50 shadow-lg rounded-lg p-6">
    <h3 class="text-xl font-semibold text-gray-800">Aulas Asignadas</h3>
    <div class="mt-6 relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nivel
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Grado
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Sección
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Estudiantes
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aulas as $aula)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $aula->grado->nivel->detalle }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $aula->grado->detalle }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $aula->detalle }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-center">
                            <a href="{{ route('estudiantes.filtrar-por-aula', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$aula->id_nivel, 'grado' => $aula->id_grado, 'seccion' => $aula->id_seccion ]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver
                                Estudiantes</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center">
                        No hay aulas asignadas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@section('scripts')
@endsection
