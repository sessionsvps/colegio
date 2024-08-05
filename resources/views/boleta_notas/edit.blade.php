@extends('layouts.main')

@section('contenido')

    <a href="{{ route('estudiantes.filtrar-por-aula', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$aula->id_nivel, 'grado' => $aula->id_grado, 'seccion' => $aula->id_seccion ]) }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
        Volver
    </a>
    <h2 class="text-xl lg:text-2xl font-bold my-10">Registro de Notas</h2>

    <div class="mb-6 flex flex-col md:flex-row items-center md:justify-between bg-gray-50 shadow-lg rounded-lg p-6">
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-800 mb-2">{{ $curso->descripcion }}</h2>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-3"><span class="font-semibold">Código:</span> {{
                $curso->codigo_curso }}</p>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-1"><span class="font-semibold">Año de actualización:</span> {{
                $curso->año_actualizacion }}</p>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-1"><span class="font-semibold">Aula:</span> {{
                $aula->grado->detalle }} {{$aula->detalle}} de {{$aula->grado->nivel->detalle}}</p>
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
    
    <form
        action="{{ route('boleta_notas.update', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$aula->id_nivel, 'grado' => $aula->id_grado, 'seccion' => $aula->id_seccion ]) }}"
        method="POST">
        @csrf
        @method('PUT')
    
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
                        @foreach ($competencias as $competencia)
                            <th scope="col" class="px-6 py-3">
                                C{{$competencia->orden}}
                            </th>
                        @endforeach
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
                        @foreach ($competencias as $competencia)
                        @php
                        $nota = $notas->where('codigo_estudiante', $estudiante->codigo_estudiante)->where('orden',
                        $competencia->orden)->first();
                        @endphp
                        <td class="px-2 lg:px-0 py-2">
                            <input type="hidden"
                                name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][codigo_estudiante]"
                                value="{{ $estudiante->codigo_estudiante }}">
                            <input type="hidden" name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][año_escolar]"
                                value="{{$estudiante->año_escolar}}">
                            <input type="hidden" name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][user_id]"
                                value="{{$estudiante->user_id}}">
                            <input type="hidden" name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][id_bimestre]"
                                value="{{ $nota->id_bimestre }}">
                            <input type="hidden" name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][codigo_curso]"
                                value="{{ $curso->codigo_curso }}">
                            <input type="hidden" name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][orden]"
                                value="{{ $competencia->orden }}">
                            <select
                                class="py-3 rounded-lg {{ $nota ? ($nota->nivel_logro == 'A' || $nota->nivel_logro == 'B' || $nota->nivel_logro == 'AD' ? 'bg-green-100' : ($nota->nivel_logro == 'C' ? 'bg-red-100' : 'bg-gray-50')) : 'bg-gray-50' }}"
                                name="notas[{{ $estudiante->codigo_estudiante }}][{{ $competencia->orden }}][nivel_logro]" onchange="updateSelectColor(this)">
                                <option value="" {{ $nota ? '' : 'selected' }}>--</option>
                                <option value="AD" {{ $nota && $nota->nivel_logro == 'AD' ? 'selected' : '' }}>AD</option>
                                <option value="A" {{ $nota && $nota->nivel_logro == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ $nota && $nota->nivel_logro == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ $nota && $nota->nivel_logro == 'C' ? 'selected' : '' }}>C</option>
                            </select>
                        </td>
                        @endforeach
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
<script>
    function updateSelectColor(selectElement) {
        const value = selectElement.value;
        selectElement.classList.remove('bg-green-100', 'bg-red-100', 'bg-white');
        
        if (value === 'A' || value === 'B' || value === 'AD') {
            selectElement.classList.add('bg-green-100');
        } else if (value === 'C') {
            selectElement.classList.add('bg-red-100');
        } else {
            selectElement.classList.add('bg-gray-50');
        }
    }

    // Inicializar colores en carga de página
    document.querySelectorAll('select').forEach(select => {
        updateSelectColor(select);
    });
</script>
@endsection