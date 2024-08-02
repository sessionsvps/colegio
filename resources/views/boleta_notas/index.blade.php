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
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="reporte" class="block text-sm font-medium text-gray-700">Seleccione un Formato</label>
                <select id="reporte" name="reporte"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" selected disabled></option>
                    <option value="0">PDF</option>
                    <option value="1">EXCEL</option>
                </select>
            </div>
            <div>
                <a id="reportButton"
                    class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">
                    Generar Reporte
                </a>
            </div>
        </div>
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
                        <div class="text-center text-gray-700 border border-gray-300 rounded-lg p-2 shadow {{ $nota ? ($nota->nivel_logro == 'A' || $nota->nivel_logro == 'B' ? 'bg-green-100' : ($nota->nivel_logro == 'C' ? 'bg-red-100' : 'bg-white')) : 'bg-white' }}">
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var reporteSelect = document.getElementById('reporte');
            var generateReportButton = document.getElementById('reportButton');

            // Recuperar el código del estudiante del servidor y almacenarlo en una variable
            var codigoEstudiante = "{{ $estudiante ? $estudiante->codigo_estudiante : '' }}";

            reporteSelect.addEventListener('change', function () {
                var selectedValue = this.value;
                if (selectedValue == '1') { // EXCEL
                    generateReportButton.href = "{{ route('export') }}";
                } else if (selectedValue == '0') { // PDF
                    generateReportButton.href = "{{ route('exportPdfNotas', ['codigo_estudiante' => '__codigo_estudiante__']) }}".replace('__codigo_estudiante__', codigoEstudiante);
                }
            });
        });
    </script>
@endsection
