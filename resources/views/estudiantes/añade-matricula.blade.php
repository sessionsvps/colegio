@extends('layouts.main')

@section('contenido')
<div>
    @if ($errors->any())
    <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Error</span>
        <div>
            <span class="font-medium">Error de matrícula:</span>
            <ul class="mt-1.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="flex justify-between">
        <a href="{{ route('estudiantes.info-matriculas', $estudiante->codigo_estudiante) }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
        </a>
    </div>

    <!-- Componente del Encabezado del Estudiante -->
    <div class="mt-10 flex items-center bg-gray-100 shadow-md rounded-lg p-6 space-x-4">
        <div class="flex-shrink-0">
            <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
            </svg>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $estudiante->primer_nombre . ' ' . $estudiante->otros_nombres . ' ' . $estudiante->apellido_paterno . ' ' . $estudiante->apellido_materno }}</h3>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">DNI:</span> {{ $estudiante->dni }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Correo:</span> {{ $estudiante->user->email }}</p>
        </div>
    </div>

    <div class="mt-10">
        <h1 class="font-bold text-3xl">Nueva Matrícula</h1>
        <form method="POST" action="{{ route('estudiantes.realizarMatricula', $estudiante->codigo_estudiante) }}" >
            @csrf
            <div class="my-5 md:my-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-0">
                <div class="mr-0">
                    <label for="nivel" class="block text-sm font-medium text-gray-700">Nivel</label>
                    <select id="nivel" name="nivel" onchange="updateGrados()" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" selected disabled>Seleccione un nivel</option>
                        @foreach($niveles as $nivel)
                        <option value="{{ $nivel->id_nivel }} " {{ request('nivel')==$nivel->id_nivel ? 'selected' : '' }} >
                            {{ $nivel->detalle }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mr-0 md:ml-5">
                    <label for="grado" class="block text-sm font-medium text-gray-700">Grado</label>
                    <select id="grado" name="grado" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @if(!isset($filtra_nivel))
                        <option value="" selected disabled>Seleccione un grado</option>
                        @else
                        @if($filtra_nivel == 1)
                        @foreach($grados_primaria as $grado_p)
                        <option value="{{ $grado_p->id_grado }}" {{ request('grado')==$grado_p->id_grado ? 'selected' : '' }}>
                            {{ $grado_p->detalle }}
                        </option>
                        @endforeach
                        @elseif($filtra_nivel == 2)
                        @foreach($grados_secundaria as $grado_s)
                        <option value="{{ $grado_s->id_grado }}" {{ request('grado')==$grado_s->id_grado ? 'selected' : '' }}>
                            {{ $grado_s->detalle }}
                        </option>
                        @endforeach
                        @endif
                        @endif
                    </select>
                </div>
                <div class="mr-0 lg:ml-5">
                    <label for="seccion" class="block text-sm font-medium text-gray-700">Sección</label>
                    <select id="seccion" name="seccion" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" selected disabled>Seleccione una sección</option>
                        <option value="1" {{ request('seccion')==1 ? 'selected' : '' }}>A</option>
                        <option value="2" {{ request('seccion')==2 ? 'selected' : '' }}>B</option>
                        <option value="3" {{ request('seccion')==3 ? 'selected' : '' }}>C</option>
                        <option value="4" {{ request('seccion')==4 ? 'selected' : '' }}>D</option>
                    </select>
                </div>
                <div class="md:ml-5 md:mt-0 lg:col-span-1">
                    <label for="año_escolar" class="block text-sm font-medium text-gray-700">Año Escolar</label>
                    <input name="año_escolar" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    type="number" readonly value="" id="input_año_escolar">
                </div>
                <div class="mt-6 mx-auto col-span-4">
                    <button type="submit"
                        class="bg-indigo-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
                        Matricular
                    </button>
                </div>
            </div>
        </form>
    </div>
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

    <script>
        function updateAñoEscolar() {
            var select = document.getElementById('año_actual');
            var valorSeleccionado = select.options[select.selectedIndex].value;
            var input = document.getElementById('input_año_escolar');
            input.value = valorSeleccionado;
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateAñoEscolar();
        });
    </script>
@endsection