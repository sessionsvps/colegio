@extends('layouts.main')

@section('contenido')
<!-- Formulario para agregar nuevo alumno -->
<div>
    {{-- <a href="{{ route('estudiantes.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
    </a> --}}
    <h1 class="font-bold text-3xl mb-6">Matrículas</h1>
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

    <form method="GET" action="{{ route('estudiantes.matricular') }}">
        <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
            <div>
                <label for="buscar_por" class="block text-sm font-medium text-gray-700">Buscar Por</label>
                <select id="buscar_por_mat" name="buscar_por_mat"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" {{ request('buscar_por_mat')=='' ? 'selected' : '' }}>Todos</option>
                    <option value="codigo" {{ request('buscar_por_mat')=='codigo' ? 'selected' : '' }}>Código</option>
                    <option value="nombre" {{ request('buscar_por_mat')=='nombre' ? 'selected' : '' }}>Nombre</option>
                    <option value="dni" {{ request('buscar_por_mat')=='dni' ? 'selected' : '' }}>DNI</option>
                    <option value="correo" {{ request('buscar_por_mat')=='correo' ? 'selected' : '' }}>Correo</option>
                </select>
            </div>
            <div id="inputContainer_mat">
                <!-- Aquí se insertarán los inputs dinámicamente -->
            </div>
            <div class="col-span-2 lg:mt-6 lg:col-span-1" id="botonBuscar">
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                    Buscar
                </button>
            </div>
        </div>
    </form>

    <div class="mt-10 relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
            <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Código
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        DNI
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Correo
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Matrículas
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
                        {{ $estudiante->primer_nombre }} {{ $estudiante->otros_nombres }} {{
                        $estudiante->apellido_paterno }} {{
                        $estudiante->apellido_materno }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $estudiante->dni }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $estudiante->user->email }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-center">
                            <a href="{{ route('estudiantes.info-matriculas', $estudiante->codigo_estudiante) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver matrículas</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-10">
        {{ $estudiantes->links() }}
    </div>

    {{-- <form method="POST" action="{{ route('estudiantes.realizarMatricula') }}">
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
                    @error('codigo_estudiante')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="año_escolar">
                        Año Escolar
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="input_año_escolar" name="año_escolar" type="number" placeholder=""
                        value="" readonly>
                    @error('año_escolar')
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
    </form> --}}
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const buscarPorSelect = document.getElementById('buscar_por_mat');
                const inputContainer = document.getElementById('inputContainer_mat');

                function updateInputContainer() {
                    inputContainer.innerHTML = ''; // Clear the container

                    if (buscarPorSelect.value === 'codigo') {
                        inputContainer.innerHTML = `
                            <label for="codigo" class="block text-sm font-medium text-gray-700">Código</label>
                            <input type="text" name="codigo" id="codigo"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                maxlength="5" oninput="this.value=this.value.replace(/[^0-9]/g, '').slice(0,this.maxLength)"
                                required value="{{ request('codigo') }}">
                        `;
                    } else if (buscarPorSelect.value === 'nombre') {
                        inputContainer.innerHTML = `
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                required maxlength="120" value="{{ request('nombre') }}">
                        `;
                    } else if (buscarPorSelect.value === 'dni') {
                        inputContainer.innerHTML = `
                            <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                            <input type="text" name="dni" id="dni"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                maxlength="8" oninput="this.value=this.value.replace(/[^0-9]/g, '').slice(0,this.maxLength)"
                                required value="{{ request('dni') }}">
                        `;
                    } else if (buscarPorSelect.value === 'correo') {
                        inputContainer.innerHTML = `
                            <label for="correo" class="block text-sm font-medium text-gray-700">Correo</label>
                            <input type="email" name="correo" id="correo"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                required maxlength="100" value="{{ request('correo') }}">
                        `;
                    } else {
                        inputContainer.innerHTML = `
                            <label for="placeholder" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" id="placeholder"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                disabled value="Todos los registros">
                        `;
                    }
                }

                // Add event listener for change
                buscarPorSelect.addEventListener('change', updateInputContainer);

                // Initial call to set the correct input on page load
                updateInputContainer();
            });
    </script>
@endsection