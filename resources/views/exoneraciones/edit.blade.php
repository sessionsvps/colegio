@extends('layouts.main')

@section('contenido')
<!-- Formulario para agregar nuevo docente -->
<div class="container mx-auto">
    <a href="{{ route('asistencias.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
        Volver
    </a>
    <h2 class="text-xl lg:text-2xl font-bold my-10">Registro de Inasistencias y Tardanzas</h2>

    @if ($errors->any())
    <div class="flex p-4 mb-10 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
        role="alert">
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

    <form method="POST" action="{{ route('asistencias.update',['codigo_estudiante' => $asistencia->codigo_estudiante, 'id_bimestre' => $asistencia->id_bimestre]) }}">
        @csrf
        @method('PUT')

        <div class="mt-10 grid lg:grid-cols-2 gap-5">
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
                    <p class="text-gray-700"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}</p>
                    <p class="text-gray-700"><span class="font-semibold">Aula:</span> {{ $estudiante->seccion->grado->detalle .
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

        

        {{-- <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-bold mb-4">Datos del Docente</h2>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                        DNI
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="dni" name="dni" type="text" placeholder="DNI" value="{{ old('dni', $docente->dni) }}" maxlength="8"
                        pattern="[0-9]{8}" readonly>
                    @error('dni')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_nacimiento">
                        Fecha de Nacimiento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="Fecha de Nacimiento"
                        value="{{ old('fecha_nacimiento', $docente->fecha_nacimiento) }}" readonly>
                    @error('fecha_nacimiento')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="primer_nombre">
                        Primer Nombre
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="primer_nombre" name="primer_nombre" type="text" placeholder="Primer Nombre"
                        value="{{ old('primer_nombre', $docente->primer_nombre) }}" readonly>
                    @error('primer_nombre')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="otros_nombres">
                        Otros Nombres
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="otros_nombres" name="otros_nombres" type="text" placeholder="Otros Nombres"
                        value="{{ old('otros_nombres', $docente->otros_nombres) }}" readonly>
                    @error('otros_nombres')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="apellido_paterno">
                        Apellido Paterno
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="apellido_paterno" name="apellido_paterno" type="text" placeholder="Apellido Paterno"
                        value="{{ old('apellido_paterno', $docente->apellido_paterno) }}" readonly>
                    @error('apellido_paterno')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="apellido_materno">
                        Apellido Materno
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="apellido_materno" name="apellido_materno" type="text" placeholder="Apellido Materno"
                        value="{{ old('apellido_materno', $docente->apellido_materno) }}" readonly>
                    @error('apellido_materno')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="sexo">
                        Sexo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="sexo_texto" name="sexo_texto" type="text"
                        value="{{ old('sexo', $docente->sexo == 1 ? 'Masculino' : 'Femenino') }}" readonly>
                    <input id="sexo" name="sexo" type="hidden" value="{{ old('sexo', $docente->sexo) }}">
                    @error('sexo')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_ingreso">
                        Fecha de Ingreso
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="fecha_ingreso" name="fecha_ingreso" type="date" placeholder="Fecha de Ingreso"
                        value="{{ old('fecha_ingreso', $docente->fecha_ingreso) }}">
                    @error('fecha_ingreso')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email" name="email" type="email" placeholder="Correo" value="{{ old('email', $docente->email) }}">
                    @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div> 
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono_celular">
                        Teléfono Celular
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono_celular" name="telefono_celular" type="text" placeholder="Teléfono Celular"
                        value="{{ old('telefono_celular', $docente->telefono_celular) }}" maxlength="9">
                    @error('telefono_celular')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_estado">
                        Estado Civil
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="id_estado" name="id_estado">
                        @foreach($estados as $estado)
                        <option value="{{ $estado->id_estado }}" {{ old('id_estado', $docente->id_estado) == $estado->id_estado ?
                            'selected' : '' }}>
                            {{ $estado->detalle }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_estado')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nacionalidad">
                        Nacionalidad
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nacionalidad" name="nacionalidad" type="text" placeholder="Nacionalidad"
                        value="{{ old('nacionalidad', $docente->nacionalidad) }}">
                    @error('nacionalidad')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="departamento">
                        Departamento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="departamento" name="departamento" type="text" placeholder="Departamento"
                        value="{{ old('departamento', $docente->departamento) }}">
                    @error('departamento')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="provincia">
                        Provincia
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="provincia" name="provincia" type="text" placeholder="Provincia" value="{{ old('provincia', $docente->provincia) }}">
                    @error('provincia')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/4 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="distrito">
                        Distrito
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="distrito" name="distrito" type="text" placeholder="Distrito" value="{{ old('distrito', $docente->distrito) }}">
                    @error('distrito')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div> --}}

        <div class="flex mt-10 items-center justify-center">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        document.getElementById('dni').addEventListener('input', function (e) {
                var value = e.target.value;
                e.target.value = value.replace(/[^0-9]/g, '').slice(0, 8);
            });
    </script>
@endsection