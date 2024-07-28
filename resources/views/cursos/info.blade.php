@extends('layouts.main')

@section('contenido')
<div class="container">
    <p class="font-semibold">{{ $curso->descripcion }}</p>
    <p><span class="font-semibold">Código:</span> {{ $curso->codigo_curso }}</p>
    <p><span class="font-semibold">Año de actualización:</span> {{ $curso->año_actualizacion }}</p>
    <p class="font-semibold mt-5">Competencias</p>
    <ul class="mt-5">
        @foreach ($competencias as $competencia)
        <li>{{ $competencia->descripcion }}</li>
        @endforeach
    </ul>
    <p class="font-semibold mt-5">Docentes</p>
    <ul class="mt-5">
        @foreach ($catedras as $catedra)
            <li>{{ $catedra->docente->primer_nombre }}</li>
        @endforeach
    </ul>
    <p class="font-semibold mt-5">Estudiantes Matriculados</p>
    <ul class="mt-5">
        @foreach ($catedras as $catedra)
            @foreach ($catedra->secciones as $seccion)
                @foreach ($seccion->estudiantes_matriculados as $estudiantes)
                    <li>{{ $estudiantes->estudiante->primer_nombre }}</li>
                @endforeach
            @endforeach
        @endforeach
    </ul>
</div>
@endsection