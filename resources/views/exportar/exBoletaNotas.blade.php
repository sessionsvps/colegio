<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boleta de Notas</title>

    <style>
        table {
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <img src="{{ $base64 }}" alt="Logo" style="height: 50px;">
    <h1>INFORME DE PROGRESO DEL APRENDIZAJE DEL ESTUDIANTE - <h1>{{ $estudiante_seccion->año_escolar }}</h1></h1>
    <table>
        <tr>
            <th>DRE</th>
            <th>2345</th>
            <th>UGEL</th>
            <th>1</th>
        </tr>
        <tr>
            <th>NIVEL</th>
            <th>{{ $estudiante_seccion->seccion->grado->nivel->detalle }}</th>
            <th>CÓDIGO MODULAR</th>
            <th>1554526057</th>
        </tr>
        <tr>
            <th>INSTITUCIÓN EDUCATIVA</th>
            <th colspan="3">Sideral Carrión Jaramillo</th>
        </tr>
        <tr>
            <th>GRADO</th>
            <th>{{ $estudiante_seccion->seccion->grado->detalle }}</th>
            <th>SECCIÓN</th>
            <th>{{ $estudiante_seccion->seccion->detalle }}</th>
        </tr>
        <tr>
            <th>APELLIDOS Y NOMBRES</th>
            <th colspan="3">{{ $estudiante->apellido_paterno }} {{ $estudiante->apellido_materno }} {{ $estudiante->primer_nombre }}</th>
        </tr>
        <tr>
            <th>CÓDIGO DEL ESTUDIANTE</th>
            <th>{{ $estudiante->codigo_estudiante }}</th>
            <th>DNI</th>
            <th>{{ $estudiante->dni }}</th>
        </tr>
    </table>
    <table>
        {{-- @foreach ( $cursos as $curso )
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
        @endforeach --}}
    </table>
</body>
</html>
