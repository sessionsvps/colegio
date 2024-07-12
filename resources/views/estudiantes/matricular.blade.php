@extends('layouts.main')

@section('contenido')
<!-- Formulario para agregar nuevo alumno -->
<div>
    <a href="{{ route('estudiantes.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
    </a>
    @if ($errors->any())
    <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
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

    <form method="POST" action="{{ route('estudiantes.realizarMatricula') }}">
        @csrf
        <!-- Datos del Estudiante -->
        <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-bold mb-4">Datos del Estudiante</h2>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-2/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="codigo_estudiante">
                        Estudiante
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="codigo_estudiante" name="codigo_estudiante">
                        <option value="" selected disabled>Seleccione un estudiante</option>
                        @foreach($estudiantes as $estudiante)
                        <option value="{{ $estudiante->codigo_estudiante }}">
                            {{ $estudiante->primer_nombre }} 
                            {{ $estudiante->otros_nombres }} 
                            {{ $estudiante->apellido_paterno }} 
                            {{ $estudiante->apellido_materno }} 
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="año_ingreso">
                        Año de Matrícula
                    </label>
                    @php
                        use Carbon\Carbon;
                        $año = Carbon::now()->year;
                    @endphp
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="año_ingreso" name="año_ingreso" type="number" placeholder="Año de Ingreso"
                        value="{{ $año }}">
                    @error('año_ingreso')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nivel">
                        Nivel
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nivel" name="nivel" onchange="updateGrados()">
                        <option value="" selected disabled>Seleccione un nivel</option>
                        @foreach($niveles as $nivel)
                        <option value="{{ $nivel->id_nivel }}">
                            {{ $nivel->detalle }}
                        </option>
                        @endforeach
                    </select>
                    @error('nivel')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="grado">
                        Grado
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="grado" name="grado">
                        <option value="" selected disabled>Seleccione un grado</option>
                    </select>
                    @error('grado')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/3 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="seccion">
                        Sección
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="seccion" name="seccion">
                        <option value="" selected disabled>Seleccione una sección</option>
                        <option value="1">A</option>
                        <option value="2">B</option>
                        <option value="3">C</option>
                        <option value="4">D</option>
                    </select>
                    @error('seccion')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    
        <div class="flex items-center justify-center">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Agregar
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        function updateGrados() {
            var nivel = document.getElementById('nivel').value;
            var grados = @json(['primaria' => $grados_primaria, 'secundaria' => $grados_secundaria]);
            
            var gradoSelect = document.getElementById('grado');
            gradoSelect.innerHTML = '<option value="" selected disabled>Seleccione un grado</option>'; // Reset options
            
            if (nivel == 1) {
                grados.primaria.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado.id_grado;
                    option.text = grado.detalle;
                    gradoSelect.appendChild(option);
                });
            } else if (nivel == 2) {
                grados.secundaria.forEach(function(grado) {
                    var option = document.createElement('option');
                    option.value = grado.id_grado;
                    option.text = grado.detalle;
                    gradoSelect.appendChild(option);
                });
            }
        }
    </script>
@endsection