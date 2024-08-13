<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Notas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .alumnos {
            width: 30%;
        }

        .competencias {
            width: 30%;
        }

        .bimestres {
            width: 40%;
        }

        .bimestres th {
            width: 25%;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th class="alumnos" rowspan="2">Alumnos</th>
                <th class="competencias" rowspan="2">Competencias</th>
                <th class="bimestres" colspan="4">Bimestre</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
            </tr>
        </thead>
        <tbody>
            @php
                $competencias=$curso->competencias;
                $cantidadCompetencias = count($competencias);
                $firstcompete=$competencias->shift();
                //dd($competencias,$cantidadCompetencias,$firstcompete);
            @endphp
            @foreach($estudiantes as $estudiante)
                <tr>
                    <td rowspan="{{ $cantidadCompetencias }}">{{ $estudiante->estudiante->apellido_paterno . ' ' . $estudiante->estudiante->apellido_materno . ' ' . $estudiante->estudiante->primer_nombre . ' ' . $estudiante->estudiante->otros_nombres }}</td>
                    <td>{{ $firstcompete ? $firstcompete->descripcion : '' }}</td>
                    {{-- <td></td>
                    <td></td>
                    <td></td>
                    <td></td> --}}
                    @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                        @php
                            $nota=$notas->where('id_bimestre',$bimestre)->where('codigo_estudiante',$estudiante->estudiante->codigo_estudiante)->where('codigo_curso', $curso->codigo_curso)->where('orden',$firstcompete->orden)->first();
                        @endphp
                        <td>{{ $nota ? $nota->nivel_logro : '' }}</td>
                    @endfor
                </tr>
                @foreach($competencias as $competencia)
                    <tr>
                        <td>{{ $competencia->descripcion }}</td>
                        {{-- <td></td>
                        <td></td>
                        <td></td>
                        <td></td> --}}
                        @for ($bimestre = 1; $bimestre <= 4; $bimestre++)
                            @php
                                $nota=$notas->where('id_bimestre',$bimestre)->where('codigo_estudiante',$estudiante->estudiante->codigo_estudiante)->where('codigo_curso', $curso->codigo_curso)->where('orden',$competencia->orden)->first();
                            @endphp
                            <td>{{ $nota ? $nota->nivel_logro : '' }}</td>
                        @endfor
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
