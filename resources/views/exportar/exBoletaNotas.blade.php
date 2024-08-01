<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

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
            <th>9768764645</th>
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
</body>
</html>
