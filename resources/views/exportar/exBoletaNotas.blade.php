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

        .notas{
            margin-left: auto;
            margin-right: auto;
            border-collapse: collapse;
            width: 90%;
            padding-top: 20px;
        }

        body{
            font-size: 10px;
        }

        .tablaConclusion{
            padding-top: 20px;
        }

        .informe{
            text-align: center;
            font-size: 15px;
        }

        .resumenTitle{
            text-align: center;
            font-size: 15px;
        }

        .imagenes{
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .lineaIzq{
            width: 150px;
            margin-left: 0;
        }

        .firmaDocente{
            margin-left: 8;
        }

        .lineaDer{
            width: 150px;
            margin-right: 0;
        }

        .firmaDirector{
            float: right;
            padding-right: 2;
        }

    </style>
</head>
<body>
    <div>
        <h1 class="informe">INFORME DE PROGRESO DEL APRENDIZAJE DEL ESTUDIANTE - <h1 class="informe">{{ $estudiante_seccion->año_escolar }}</h1></h1>
    </div>
    <div class="imagenes">
        <img src="{{ $base642 }}" alt="Logo Minedu" style="width: 170px;">
        <img src="{{ $base64 }}" alt="Logo Sideral" style="height: 50px;">
    </div>
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
            <th colspan="3">Sideral Carrión</th>
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

    <table class="notas">
        <tr class="header">
            <th rowspan="2">ÁREA CURRICULAR</th>
            <th rowspan="2">COMPETENCIAS</th>
            <th colspan="4">CALIFICATIVO POR PERIODO</th>
            <th rowspan="2">Calificación final del área</th>
        </tr>
        <tr class="header">
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
        @foreach ($cursos as $curso)
            @if ($curso->codigo_curso!='8889')
                @php
                    $competencias = $curso->curso->competencias;
                    $cantidadCompetencias = count($competencias);
                    $firstcompete=$competencias->shift();
                @endphp
                <tr>
                    <td rowspan="{{ $cantidadCompetencias + 1 }}">{{$curso->curso->descripcion}}</td>
                    <td>{{ $firstcompete ? $firstcompete->descripcion : '' }}</td>
                    @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                        @php
                            $nota=$notas->where('id_bimestre',$bimestre)->where('codigo_curso', $curso->codigo_curso)->where('orden',$firstcompete->orden)->first();
                        @endphp
                        <td>{{ $nota ? $nota->nivel_logro : '' }}</td>
                    @endfor
                    <td rowspan="{{ $cantidadCompetencias + 1 }}">{{ $curso->promedio_general }}</td>
                </tr>
                @foreach ( $competencias as $competencia )
                    <tr>
                        <td>{{ $competencia->descripcion }}</td>
                        @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                            @php
                                $nota=$notas->where('id_bimestre',$bimestre)->where('codigo_curso', $curso->codigo_curso)->where('orden',$competencia->orden)->first();
                            @endphp
                            <td>{{ $nota ? $nota->nivel_logro : '' }}</td>
                        @endfor
                    </tr>
                @endforeach
                <tr class="header">
                    <td>CALIFICATIVO DE ÁREA</td>
                    @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                        <td>{{ $curso->{'promedio_bimestre_'.$bimestre} }}</td>
                    @endfor
                </tr>
            @endif
        @endforeach
    </table>

    <table class="tablaConclusion">
        <tr>
            <th>Periodo</th>
            <th>Conclusión descriptiva por periodo</th>
        </tr>
        <tr>
            <td>1</td>
            <td></td>
        </tr>
        <tr>
            <td>2</td>
            <td></td>
        </tr>
        <tr>
            <td>3</td>
            <td></td>
        </tr>
        <tr>
            <td>4</td>
            <td></td>
        </tr>
    </table>

    <div>
        <h1 class="resumenTitle">Resumen de asistencia del estudiante</h1>
        <table>
            <thead>
                <tr class="header">
                    <th rowspan="2">Periodo</th>
                    <th colspan="2">Inasistencias</th>
                    <th colspan="2">Tardanzas</th>
                </tr>
                <tr class="header">
                    <th>Justificadas</th>
                    <th>Injustificadas</th>
                    <th>Justificadas</th>
                    <th>Injustificadas</th>
                </tr>
            </thead>
            <tbody>
                @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                    @php
                        $asistenciaBimestre = $asistencias->where('id_bimestre', $bimestre)->first();
                    @endphp
                    <tr>
                        <td>{{ $bimestre }}</td>
                        <td>{{ $asistenciaBimestre ? $asistenciaBimestre->inasistencias_justificadas : 0 }}</td>
                        <td>{{ $asistenciaBimestre ? $asistenciaBimestre->inasistencias_injustificadas : 0 }}</td>
                        <td>{{ $asistenciaBimestre ? $asistenciaBimestre->tardanzas_justificadas : 0 }}</td>
                        <td>{{ $asistenciaBimestre ? $asistenciaBimestre->tardanzas_injustificadas : 0 }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div>
        <div class="firmas">
            <hr class="lineaIzq">
            <p class="firmaDocente">Firma y sello del Docente</p>
        </div>
        <div class="firmaDirector">
            <hr class="lineaDer">
            <div>
                <p>Firma y sello del Director</p>
            </div>
        </div>
        {{-- @php
            dd($asistenciaBimestre);
        @endphp --}}
    </div>
</body>
</html>
