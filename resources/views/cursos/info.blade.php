@extends('layouts.main')

@section('contenido')
<div class="container mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ $curso->descripcion }}</h2>
        <p class="text-gray-600"><span class="font-semibold">Código:</span> {{ $curso->codigo_curso }}</p>
        <p class="text-gray-600"><span class="font-semibold">Año de actualización:</span> {{ $curso->año_actualizacion
            }}</p>
    </div>

    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Competencias</h3>
        <ul class="mt-3 space-y-2">
            @foreach ($competencias as $competencia)
            <li class="p-4 bg-gray-100 rounded-lg shadow-sm">
                {{ $competencia->descripcion }}
            </li>
            @endforeach
        </ul>
    </div>

    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Docentes</h3>
        <div class="mt-3 relative overflow-x-auto shadow-md sm:rounded-lg">
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
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($catedras as $catedra)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $catedra->docente->codigo_docente }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $catedra->docente->primer_nombre . ' ' . $catedra->docente->otros_nombres .
                            ' ' .
                            $catedra->docente->apellido_paterno . ' ' .
                            $catedra->docente->apellido_materno }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-center">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver Info</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Estudiantes Matriculados</h3>
        <!-- Filtros -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <form id="filter-form" action="{{ route('cursos.info', $curso->codigo_curso) }}" method="GET"
                class="flex flex-wrap w-full">
                <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nivel">
                        Nivel
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nivel" name="nivel" onchange="document.getElementById('filter-form').submit(); updateGrados();">
                        <option value="" selected>Todos</option>
                        @foreach($niveles as $nivel)
                        <option value="{{ $nivel->id_nivel }}" {{ request('nivel')==$nivel->id_nivel ? 'selected' : '' }}>
                            {{ $nivel->detalle }}
                        </option>
                        @endforeach
                    </select>
                    @error('nivel')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="grado">
                        Grado
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="grado" name="grado" onchange="document.getElementById('filter-form').submit();">
                        <option value="" selected>Todos</option>
                        @foreach($grados_primaria as $grado)
                        <option value="{{ $grado->id_grado }}" {{ request('grado')==$grado->id_grado ? 'selected' : '' }}>
                            {{ $grado->detalle }}
                        </option>
                        @endforeach
                    </select>
                    @error('grado')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="seccion">
                        Sección
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="seccion" name="seccion" onchange="document.getElementById('filter-form').submit();">
                        <option value="" selected>Todos</option>
                        <option value="1" {{ request('seccion')=='1' ? 'selected' : '' }}>A</option>
                        <option value="2" {{ request('seccion')=='2' ? 'selected' : '' }}>B</option>
                        <option value="3" {{ request('seccion')=='3' ? 'selected' : '' }}>C</option>
                        <option value="4" {{ request('seccion')=='4' ? 'selected' : '' }}>D</option>
                    </select>
                    @error('seccion')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>
        <div class="mt-3 relative overflow-x-auto shadow-md sm:rounded-lg">
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
                            Nivel
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Grado
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Sección
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Docente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($catedras as $catedra)
                    @foreach ($catedra->secciones as $seccion)
                    @foreach ($seccion->estudiantes_matriculados as $info_estudiante)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $info_estudiante->estudiante->codigo_estudiante }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $info_estudiante->estudiante->primer_nombre . ' ' . $info_estudiante->estudiante->otros_nombres .
                            ' ' .
                            $info_estudiante->estudiante->apellido_paterno . ' ' .
                            $info_estudiante->estudiante->apellido_materno }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $info_estudiante->seccion->grado->nivel->detalle }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $info_estudiante->seccion->grado->detalle }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $info_estudiante->seccion->detalle }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $catedra->docente->primer_nombre . ' ' . $catedra->docente->otros_nombres . ' ' . $catedra->docente->apellido_paterno . ' ' . $catedra->docente->apellido_materno }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-center">
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver Info</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection

@section('scripts')
    <script>
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
    </script>
@endsection
