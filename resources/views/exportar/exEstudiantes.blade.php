    <table>
        <thead>
            <tr>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>COLEGIO</th>
                <td>Sideral Carrion</td>
            </tr>
            <tr>
                <th></th>
            </tr>
            <tr>
                <th></th>
            </tr>
            <tr>
                <th></th>
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
                    <td></td>
                    <td>{{$estudiante->codigo_estudiante}}</td>
                    <td>{{$estudiante->primer_nombre . ' ' . $estudiante->otros_nombres}}</td>
                    <td>{{$estudiante->apellido_paterno . ' ' . $estudiante->apellido_materno}}</td>
                    <td>{{$estudiante->dni}}</td>
                    <td>{{$estudiante->email}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
