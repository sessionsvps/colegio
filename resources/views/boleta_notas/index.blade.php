@extends('layouts.main')

@section('contenido')
    <p class="font-bold text-xl md:text-2xl lg:text-3xl">Boleta de Notas</p>
    <form action="{{ route('boleta_notas.index') }}" method="GET">
        <div class="mt-5 md:mt-10 grid grid-cols-1 lg:grid-cols-3">
            <div class="mr-5">
                <label for="codigo_estudiante" class="block text-sm font-medium text-gray-700">Código de estudiante</label>
                <input type="text" name="codigo_estudiante" id="codigo_estudiante"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    required maxlength="4" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
            <div class="lg:mr-5">
                <label for="año_escolar" class="block text-sm font-medium text-gray-700">Año Escolar</label>
                <select id="año_escolar" name="año_escolar"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="2024" {{ request('año_escolar')=='2024' ? 'selected' : '' }}>
                        2024
                    </option>
                    <option value="2023" {{ request('año_escolar')=='2023' ? 'selected' : '' }}>
                        2023
                    </option>
                </select>
            </div>
            <div class="mt-5 md:mt-0 col-span-3 lg:col-span-1" id="botonBuscar">
                <button type="submit"
                    class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                    Buscar
                </button>
            </div>
        </div>
    </form>
    
    @if($estudiante)
        <div class="mt-10 md:mt-20 flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $estudiante->estudiante->primer_nombre . ' ' .
                    $estudiante->estudiante->otros_nombres . ' ' . $estudiante->estudiante->apellido_paterno . ' ' .
                    $estudiante->estudiante->apellido_materno }}</h3>
                <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante
                    }}
                </p>
                <p class="text-gray-700 mt-1"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}</p>
                <p class="text-gray-700 mt-1"><span class="font-semibold">Aula:</span> {{
                    $estudiante->seccion->grado->detalle .
                    ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
            </div>
        </div>
        {{-- <div class="mt-5 md:mt-10 relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
                <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Código
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Curso
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Competencias
                        </th>
                        <th scope="col" class="px-6 py-3">
                            I BIMESTRE
                        </th>
                        <th scope="col" class="px-6 py-3">
                            II BIMESTRE
                        </th>
                        <th scope="col" class="px-6 py-3">
                            III BIMESTRE
                        </th>
                        <th scope="col" class="px-6 py-3">
                            IV BIMESTRE
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cursos as $curso)
                    @php
                    $competencias = $curso->curso->competencias
                    @endphp
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $curso->codigo_curso }}
                        </th>
                        <td class="px-6 py-4">
                            {{$curso->curso->descripcion}}
                        </td>
                        <td class="px-6 py-4">
                            @forelse ($competencias as $competencia)
                            <div class="bg-gray-100 p-2 rounded-lg mb-2 shadow-sm flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                                <span class="text-gray-800 text-start">{{ $competencia->descripcion }}</span>
                            </div>
                            @empty
                            <div class="bg-red-100 p-2 rounded-lg shadow-sm">
                                No hay competencias
                            </div>
                            @endforelse
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($notas->where('id_bimestre', 1)->where('codigo_curso',$curso->codigo_curso) as $nota)
                            <div class="bg-gray-100 p-2 rounded-lg mb-2 shadow-sm flex items-center justify-center">
                                <span
                                    class="text-gray-800 font-semibold {{ $nota->nivel_logro == 'A' ? 'text-green-500' : ($nota->nivel_logro == 'B' ? 'text-blue-800' : 'text-red-500') }}">
                                    {{ $nota->nivel_logro }}
                                </span>
                            </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($notas->where('id_bimestre', 2)->where('codigo_curso',$curso->codigo_curso) as $nota)
                            <div class="bg-gray-100 p-2 rounded-lg mb-2 shadow-sm">
                                <span
                                    class="text-gray-800 font-semibold {{ $nota->nivel_logro == 'A' ? 'text-green-500' : ($nota->nivel_logro == 'B' ? 'text-blue-800' : 'text-red-500') }}">
                                    {{ $nota->nivel_logro }}
                                </span>
                            </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($notas->where('id_bimestre', 3)->where('codigo_curso',$curso->codigo_curso) as $nota)
                            <div class="bg-gray-100 p-2 rounded-lg mb-2 shadow-sm">
                                <span
                                    class="text-gray-800 font-semibold {{ $nota->nivel_logro == 'A' ? 'text-green-500' : ($nota->nivel_logro == 'B' ? 'text-blue-800' : 'text-red-500') }}">
                                    {{ $nota->nivel_logro }}
                                </span>
                            </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($notas->where('id_bimestre', 4)->where('codigo_curso',$curso->codigo_curso) as $nota)
                            <div class="bg-gray-100 p-2 rounded-lg mb-2 shadow-sm">
                                <span
                                    class="text-gray-800 font-semibold {{ $nota->nivel_logro == 'A' ? 'text-green-500' : ($nota->nivel_logro == 'B' ? 'text-blue-800' : 'text-red-500') }}">
                                    {{ $nota->nivel_logro }}
                                </span>
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div> --}}
        @foreach ( $cursos as $curso )
        @php
        $competencias = $curso->curso->competencias
        @endphp
        <div class="mt-6 bg-gray-50 shadow-md rounded-lg p-6">
            <p class="text-lg font-bold text-indigo-600">{{$curso->curso->descripcion}} ({{$curso->codigo_curso}})</p>
            <div class="grid grid-cols-1 gap-4 mt-4">
                @foreach ( $competencias as $competencia )
                <div class="grid grid-cols-2 gap-4 items-center">
                    <div class="bg-white text-indigo-900 p-2 rounded-lg shadow-sm">
                        <div class="font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                            </svg>
                            {{$competencia->descripcion}}    
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                        @php
                        $nota=$notas->where('id_bimestre',$bimestre)->where('codigo_curso', $curso->codigo_curso)->where('orden',$competencia->orden)->first();
                        @endphp
                        <div class="text-center text-gray-700 border border-gray-300 rounded-lg p-2 shadow uppercase {{ $nota ? ($nota->nivel_logro == 'A' || $nota->nivel_logro == 'B' || $nota->nivel_logro == 'AD' ?  'bg-green-100' : ($nota->nivel_logro == 'C' ? 'bg-red-100' : 'bg-white')) : 'bg-white' }}">
                            {{ $nota ? $nota->nivel_logro : '' }}
                        </div>
                        @endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
@endsection

@section('scripts')
@endsection
