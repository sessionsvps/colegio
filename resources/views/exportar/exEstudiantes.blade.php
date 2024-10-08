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
            width: 100%;
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
    <table>
        <thead>
            <tr>
                <th colspan="5" style="text-align: center;">
                    <img src="{{ $base64 }}" alt="Logo" style="height: 50px;">
                    <h2>COLEGIO</h2>
                    <h4>Sideral Carrion</h4>
                    <h4>Año Académico 2023-2024</h4>
                </th>
            </tr>
            <tr>
                <th>CÓDIGO</th>
                <th>NOMBRE</th>
                <th>APELLIDOS</th>
                <th>DNI</th>
                <th>CORREO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estudiantes as $estudiante)
                <tr>
                    <td>{{ $estudiante->codigo_estudiante }}</td>
                    <td>{{ $estudiante->primer_nombre . ' ' . $estudiante->otros_nombres }}</td>
                    <td>{{ $estudiante->apellido_paterno . ' ' . $estudiante->apellido_materno }}</td>
                    <td>{{ $estudiante->dni }}</td>
                    <td>{{ $estudiante->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
