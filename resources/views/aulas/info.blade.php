@extends('layouts.main')

@section('contenido')
<!-- Información del Curso -->
<div class="mb-6 flex flex-col md:flex-row items-center md:justify-between bg-gray-50 shadow-lg rounded-lg p-6">
    <div class="flex-1">
        <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-800 mb-2">{{$aula->grado->detalle}} {{$aula->detalle}} de {{$aula->grado->nivel->detalle}}</h2>
        <p class="text-gray-600 sm:text-lg lg:text-xl mt-1"><span class="font-semibold">Año:</span> {{$año_escolar}}</p>
    </div>
    <div class="hidden md:block flex-shrink-0 mt-4 lg:mt-0">
        <img src="{{ asset("img/aulas.webp") }}" alt="Imagen del Curso"
        class="w-48 h-48 lg:w-64 lg:h-64 object-cover rounded-full shadow-md">
    </div>
</div>

<h3 class="text-xl font-semibold text-gray-800">Estudiantes</h3>
<div class="mt-6 relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
        <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Código
                </th>
                <th scope="col" class="px-6 py-3">
                    Nombre Completo
                </th>
                <th scope="col" class="px-6 py-3">
                    Correo
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($estudiantes as $estudiante)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $estudiante->codigo_estudiante}}
                </th>
                <td class="px-6 py-4">
                    {{ $estudiante->estudiante->primer_nombre . ' ' . $estudiante->estudiante->otros_nombres .
                    ' ' .
                    $estudiante->estudiante->apellido_paterno . ' ' .
                    $estudiante->estudiante->apellido_materno }}
                </td>
                <td class="px-6 py-4">
                    {{$estudiante->estudiante->email}}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4 text-center">
                    No hay estudiantes matriculados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')

@endsection
