@extends('layouts.main')

@section('contenido')
    <p class="font-bold text-xl md:text-2xl lg:text-3xl">Boleta de Notas</p>

    <div class="mt-10 flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
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
        <p class="text-lg font-bold text-indigo-900">{{$curso->curso->descripcion}} ({{$curso->codigo_curso}})</p>
        <div class="grid grid-cols-1 gap-4 mt-4">
            @foreach ( $competencias as $competencia )
            <div class="grid grid-cols-2 gap-4 items-center">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    <div class="font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                        </svg>
                        {{$competencia->descripcion}}
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-2 lg:h-full">
                    @for ($bimestre = 1; $bimestre <= 4; $bimestre++) @php $nota=$notas->
                        where('id_bimestre',$bimestre)->where('codigo_curso',
                        $curso->codigo_curso)->where('orden',$competencia->orden)->first();
                        @endphp
                        <div
                            class="lg:flex items-center justify-center text-center text-gray-700 border border-gray-300 rounded-lg p-2 shadow uppercase {{ $nota ? ($nota->nivel_logro == 'A' || $nota->nivel_logro == 'B' || $nota->nivel_logro == 'AD' ?  'bg-green-100' : ($nota->nivel_logro == 'C' ? 'bg-red-100' : 'bg-white')) : 'bg-white' }}">
                            {{ $nota ? $nota->nivel_logro : '' }}
                        </div>
                        @endfor
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@endsection

@section('scripts')
@endsection
