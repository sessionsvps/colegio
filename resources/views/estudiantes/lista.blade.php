@extends('layouts.main')

@section('contenido')

    @if (session('success'))
    <div id="success-message"
        class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div>
            <span class="font-medium">¡Éxito!</span> {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row items-center md:justify-between bg-gray-50 shadow-lg rounded-lg p-6">
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-800 mb-2">{{ $curso->descripcion }}</h2>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-3"><span class="font-semibold">Código:</span> {{
                $curso->codigo_curso }}</p>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-1"><span class="font-semibold">Año de actualización:</span> {{
                $curso->año_actualizacion }}</p>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-1"><span class="font-semibold">Aula:</span> {{
                $q_seccion->grado->detalle }} {{$q_seccion->detalle}} de {{$q_seccion->grado->nivel->detalle}}</p>
        </div>
        <div class="hidden md:block flex-shrink-0 mt-4 lg:mt-0">
            <img src="{{ asset("img/info_cursos/$curso->codigo_curso.webp") }}" alt="Imagen del Curso"
            class="w-48 h-48 lg:w-64 lg:h-64 object-cover rounded-full shadow-md">
        </div>
    </div>

    <div class="my-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label for="bimestre" class="block text-sm font-medium text-gray-700">Bimestre</label>
            <select id="bimestre" name="bimestre"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                @foreach($bimestres as $bimestre)
                <option value="{{ $bimestre->id }}" {{ request('bimestre')==$bimestre->id ? 'selected' : '' }}>
                    {{ $bimestre->descripcion }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            @can('Editar Notas')
            <a id="editNotasButton"
                class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                Registrar Notas
            </a>
            @endcan
        </div>
        <div class="grid gap-4 md:grid-cols-2 md:col-span-2 lg:flex lg:justify-end">
            <div>
                <a id="reportButton"
                    class="lg:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                    Reporte de Notas
                </a>
            </div>
            <div>
                <a id="auxiliarButton"
                    class="lg:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                    Registro Auxiliar
                </a>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Código
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombres
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($estudiantes as $estudiante)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $estudiante->codigo_estudiante }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $estudiante->estudiante->primer_nombre . ' ' . $estudiante->estudiante->otros_nombres . ' ' .
                        $estudiante->estudiante->apellido_paterno . ' ' . $estudiante->estudiante->apellido_materno }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-6 py-4 text-center">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            var successMessage = document.getElementById('success-message');
                            if (successMessage) {
                                successMessage.style.transition = 'opacity 0.5s ease';
                                successMessage.style.opacity = '0';
                                setTimeout(function() {
                                    successMessage.remove();
                                }, 500); // Espera el tiempo de la transición para eliminar el elemento
                            }
                        }, 3000); // 3 segundos antes de empezar a desvanecer
                    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bimestreSelect = document.getElementById('bimestre');
            var editNotasButton = document.getElementById('editNotasButton');
    
            function updateEditNotasHref() {
                var selectedBimestre = bimestreSelect.value;
                var route = "{{ route('boleta_notas.edit', [
                    'codigo_curso' => $curso->codigo_curso,
                    'bimestre' => ':bimestre',
                    'nivel' => $q_seccion->id_nivel,
                    'grado' => $q_seccion->id_grado,
                    'seccion' => $q_seccion->id_seccion
                ]) }}";
    
                editNotasButton.href = route.replace(':bimestre', selectedBimestre);
            }
    
            // Update the href when the page loads and whenever the select changes
            updateEditNotasHref();
            bimestreSelect.addEventListener('change', updateEditNotasHref);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var generateReportButton = document.getElementById('reportButton');
            generateReportButton.href = "{{ route('exportPdfNotaProfe', ['codigo_curso' => $curso->codigo_curso,'nivel' =>$q_seccion->id_nivel,'grado' => $q_seccion->id_grado,'seccion' => $q_seccion->id_seccion]) }}";
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var generateReportButton = document.getElementById('auxiliarButton');
    
            generateReportButton.href = "{{ route('exportPdfAuxiliar', [
                    'codigo_curso' => $curso->codigo_curso,
                    'nivel' => $q_seccion->id_nivel,
                    'grado' => $q_seccion->id_grado,
                    'seccion' => $q_seccion->id_seccion
            ]) }}";
        });
    </script>
@endsection
