@extends('layouts.main')

@section('contenido')
    <div class="mb-6 flex flex-col md:flex-row items-center md:justify-between bg-gray-50 shadow-lg rounded-lg p-6">
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl lg:text-4xl font-bold text-gray-800 mb-2">{{ $curso->descripcion }}</h2>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-3"><span class="font-semibold">Código:</span> {{ $curso->codigo_curso }}</p>
            <p class="text-gray-600 sm:text-lg lg:text-xl mt-3"><span class="font-semibold">Aula:</span> {{ $q_seccion->grado->detalle }} {{ $q_seccion->detalle }} de {{ $q_seccion->grado->nivel->detalle }}</p>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Código
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombres
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($estudiantes as $estudiante)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $estudiante->codigo_estudiante }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $estudiante->estudiante->primer_nombre . ' ' . $estudiante->estudiante->otros_nombres . ' ' .
                        $estudiante->estudiante->apellido_paterno . ' ' . $estudiante->estudiante->apellido_materno }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-center">
                            <a href="{{ route('boleta_notas.edit', ['codigo_estudiante' => $estudiante->codigo_estudiante, 'codigo_curso' => $curso->codigo_curso,'año_escolar' => $estudiante->año_escolar]) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Registrar Notas</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- <div class="mt-10 md:mt-20 grid lg:grid-cols-1 gap-5">
        <div class="flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4 w-full">
            <div class="w-full">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                                Código Estudiante
                            </th>
                            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                                Nombres
                            </th>
                            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estudiantes as $estudiante)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->codigo_estudiante }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                {{ $estudiante->estudiante->primer_nombre . ' ' . $estudiante->estudiante->otros_nombres . ' ' . $estudiante->estudiante->apellido_paterno . ' ' . $estudiante->estudiante->apellido_materno }}
                            </td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('boleta_notas.edit', ['codigo_estudiante' => $estudiante->codigo_estudiante, 'codigo_curso' => $curso->codigo_curso,'año_escolar' => $estudiante->año_escolar]) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                                    Registrar Notas
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-2 px-4 border-b border-gray-200 italic">No hay estudiantes registrados en esta sección</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
@endsection
