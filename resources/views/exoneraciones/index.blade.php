@extends('layouts.main')

@section('contenido')
    <div class="container mx-auto">
        @if (session('success'))
        <div id="success-message"
            class="flex items-center p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">¡Éxito!</span> {{ session('success') }}
            </div>
        </div>
        @endif
        <p class="font-bold text-xl md:text-2xl lg:text-3xl">Exoneraciones</p>
        <form action="{{ route('exoneraciones.index') }}" method="GET">
            <div class="mt-5 md:mt-10 grid grid-cols-1 lg:grid-cols-3">
                <div class="mr-5">
                    <label for="codigo_estudiante" class="block text-sm font-medium text-gray-700">Código de estudiante</label>
                    <input type="text" name="codigo_estudiante" id="codigo_estudiante"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                        required maxlength="4" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div class="lg:mr-5">
                    <label for="año_escolar" class="block text-sm font-medium text-gray-700">Año Escolar</label>
                    <select id="año_escolar" name="año_escolar"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="2024" {{ request('año_escolar')=='2024' ? 'selected' : '' }}>
                            2024
                        </option>
                        <option value="2023" {{ request('año_escolar')=='2023' ? 'selected' : '' }}>
                            2023
                        </option>
                    </select>
                </div>
                <div class="mt-5 md:mt-0 col-span-3 lg:col-span-1" id="botonBuscar">
                    <button type="submit"
                        class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                        Buscar
                    </button>
                </div>
            </div>
        </form>

        @if($estudiante)
            <div class="mt-10 md:mt-20 grid lg:grid-cols-2 gap-5">
                <div class="flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $estudiante->estudiante->primer_nombre . ' ' .
                            $estudiante->estudiante->otros_nombres . ' ' . $estudiante->estudiante->apellido_paterno . ' ' .
                            $estudiante->estudiante->apellido_materno }}</h3>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante }}
                        </p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}</p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Aula:</span> {{ $estudiante->seccion->grado->detalle .
                            ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
                    </div>
                </div>
                <div class=" bg-gray-50 shadow-md rounded-lg p-6 flex flex-col justify-center items-center">
                    @if ($estudiante->año_escolar == 2024)
                        <div class="flex w-full justify-end mb-4">
                            <a href="{{route('exoneraciones.edit',['codigo_estudiante' => $estudiante->codigo_estudiante, 'año_escolar' => $estudiante->año_escolar]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white
                                bg-blue-700 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Editar
                            </a>
                        </div>
                    @endif
                    <ul class="space-y-4 w-full">
                        @forelse ($exoneraciones as $exoneracion)
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                {{ $exoneracion->curso->descripcion }}
                            </div>
                        </li>
                        @empty
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-1 text-gray-800">
                                No hay exoneraciones registradas
                            </div>
                        </li>
                        @endforelse
                    </ul>
                </div>    
            </div>
        @endif

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(function() {
                            var successMessage = document.getElementById('success-message');
                            if (successMessage) {
                                successMessage.style.transition = 'opacity 0.5s ease';
                                successMessage.style.opacity = '0';
                                setTimeout(function() {
                                    successMessage.remove();
                                }, 500); // Espera el tiempo de la transición para eliminar el elemento
                            }
                        }, 3000); // 3 segundos antes de empezar a desvanecer
                    });
    </script>
@endsection
