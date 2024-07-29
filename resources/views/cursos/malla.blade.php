@extends('layouts.main')

@section('contenido')
    <a href="{{ route('cursos.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
    </a>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                    Curso
                </th>
                <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                    Competencias
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($cursos as $curso)
                @php
                    $competencias = $curso->getCompetencias();
                    $firstCompetencia = $competencias->shift();
                @endphp
                @if($firstCompetencia != null && $competencias->count()>=0)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200" rowspan="{{ $competencias->count() + 1 }}">
                            {{$curso->descripcion}}
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            {{$firstCompetencia->descripcion}}
                        </td>
                    </tr>
                    @foreach($competencias as $competencia)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">
                                {{$competencia->descripcion}}
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
@endsection