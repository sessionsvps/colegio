@extends('layouts.main')

@section('contenido')
<!-- Información del Curso -->
{{-- <div class="mb-6 bg-gray-50 shadow-lg rounded-lg p-6">
    <h3 class="text-xl font-semibold text-gray-800">Secciones</h3>
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
                </tr>
            </thead>
            <tbody>
                @forelse ($aulas as $aula)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $aula->grado->nivel->detalle }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $aula->grado->detalle }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $aula->detalle }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center">
                        No hay secciones asignadas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> --}}

<div class="mb-6 flex flex-col md:flex-row items-center md:justify-between bg-gray-50 shadow-lg rounded-lg p-6">
    <div class="flex-1">
        <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-800 mb-2">{{ $curso->descripcion }}</h2>
        <p class="text-gray-600 sm:text-lg lg:text-xl mt-3"><span class="font-semibold">Código:</span> {{ $curso->codigo_curso }}</p>
    </div>
</div>

<div class="mt-2 md:mt-6 grid lg:grid-cols-2 gap-y-5">
    @forelse ($aulas as $aula)  
    <div class="bg-slate-50 flex items-center rounded-lg p-6 space-x-4">
        <div class="flex-shrink-0">
            <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
            </svg>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">{{ $aula->grado->detalle . ' ' . $aula->detalle . ' de ' . $aula->grado->nivel->detalle }}</h3>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Nivel:</span> {{ $aula->grado->nivel->detalle }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Grado:</span> {{ $aula->grado->detalle }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Sección:</span> {{ $aula->detalle }}</p>
        </div>
    </div>
    <div class="bg-slate-50 rounded-lg p-6 flex flex-col justify-center items-center">
        <div class="flex w-full justify-center md:justify-end mb-4">
            <a href="{{ route('estudiantes.filtrar-por-aula', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$aula->grado->nivel->id_nivel, 'grado' => $aula->grado->id_grado, 'seccion' => $aula->id_seccion ]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-700 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Ver Estudiantes
            </a>
        </div>
    </div>
    @empty
    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="text-gray-800">No hay secciones asignadas.</p>
    </div>
    @endforelse
</div>

@endsection

@section('scripts')
    <script>
        @can('estudiantes.control')
            function updateGrados() {
            var nivel = document.getElementById('nivel').value;
            var grados = @json(['primaria' => $grados_primaria, 'secundaria' => $grados_secundaria]);
            
            var gradoSelect = document.getElementById('grado');
            gradoSelect.innerHTML = '<option value="" selected>Todos</option>'; // Reset options
            
            if (nivel == 1 || nivel == 0) {
            grados.primaria.forEach(function(grado) {
            var option = document.createElement('option');
            option.value = grado.id_grado;
            option.text = grado.detalle;
            gradoSelect.appendChild(option);
            });
            } else if (nivel == 2) {
            grados.secundaria.forEach(function(grado) {
            var option = document.createElement('option');
            option.value = grado.id_grado;
            option.text = grado.detalle;
            gradoSelect.appendChild(option);
            });
            }
            }
        @endcan
    </script>
@endsection
