@extends('layouts.main')

@section('contenido')
<a href="{{ route('asistencias.index') }}"
    class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
    Volver
</a>
<h2 class="text-xl lg:text-2xl font-bold my-10">Registro de Inasistencias y Tardanzas</h2>

@if ($errors->any())
<div class="flex p-4 mb-10 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
    <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
        fill="currentColor" viewBox="0 0 20 20">
        <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
    </svg>
    <span class="sr-only">Error</span>
    <div>
        <span class="font-medium">Por favor, asegúrate de que estos requisitos se cumplan:</span>
        <ul class="mt-1.5 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<form method="POST"
    action="{{ route('asistencias.update',['codigo_estudiante' => $asistencia->codigo_estudiante, 'id_bimestre' => $asistencia->id_bimestre, 'año_escolar' => $asistencia->año_escolar]) }}">
    @csrf
    @method('PUT')

    <div class="mt-10 grid lg:grid-cols-2 gap-5">
        <div class="flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $estudiante->estudiante->primer_nombre . ' ' .
                    $estudiante->estudiante->otros_nombres . ' ' . $estudiante->estudiante->apellido_paterno . ' ' .
                    $estudiante->estudiante->apellido_materno }}</h3>
                <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{
                    $estudiante->codigo_estudiante }}
                </p>
                <p class="text-gray-700"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}</p>
                <p class="text-gray-700"><span class="font-semibold">Aula:</span> {{
                    $estudiante->seccion->grado->detalle .
                    ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
            </div>
        </div>
        <div class="bg-gray-50 shadow-md rounded-lg p-6">
            <div class="mb-4">
                <label for="inasistencias_justificadas" class="block text-sm font-medium text-gray-700">Inasistencias
                    Justificadas</label>
                <input type="text" name="inasistencias_justificadas" id="inasistencias_justificadas"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    value="{{ $asistencia->inasistencias_justificadas }}" required maxlength="3" pattern="\d*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
            <div class="mb-4">
                <label for="inasistencias_injustificadas" class="block text-sm font-medium text-gray-700">Inasistencias
                    Injustificadas</label>
                <input type="text" name="inasistencias_injustificadas" id="inasistencias_injustificadas"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    value="{{ $asistencia->inasistencias_injustificadas }}" required maxlength="3" pattern="\d*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
            <div class="mb-4">
                <label for="tardanzas_justificadas" class="block text-sm font-medium text-gray-700">Tardanzas
                    Justificadas</label>
                <input type="text" name="tardanzas_justificadas" id="tardanzas_justificadas"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    value="{{ $asistencia->tardanzas_justificadas }}" required maxlength="3" pattern="\d*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
            <div class="mb-4">
                <label for="tardanzas_injustificadas" class="block text-sm font-medium text-gray-700">Tardanzas
                    Injustificadas</label>
                <input type="text" name="tardanzas_injustificadas" id="tardanzas_injustificadas"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    value="{{ $asistencia->tardanzas_injustificadas }}" required maxlength="3" pattern="\d*"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
        </div>
    </div>

    <div class="flex mt-10 items-center justify-center">
        <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit">
            Actualizar
        </button>
    </div>
</form>
@endsection

@section('scripts')
@endsection