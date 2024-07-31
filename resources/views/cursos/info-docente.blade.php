@extends('layouts.main')

@section('contenido')
<!-- Información del Curso -->
<div class="mb-6 bg-gray-50 shadow-lg rounded-lg p-6">
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
