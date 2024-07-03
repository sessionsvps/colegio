@extends('layouts.main')

@section('contenido')
<!-- Formulario para agregar nuevo estudiante -->
<div>
    <a href="{{ route('estudiantes.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
    </a>
    <h2 class="text-xl font-bold mb-4">Editar Estudiante</h2>

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

    {{-- <form method="POST" action='{{ route('estudiantes.update',$estudiante->codigo_estudiante) }}'
        class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Nombre
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="name" name="name" type="text" placeholder="Nombre" value="{{ old('name',$estudiante->name)}}">
            @error('name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                DNI
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="dni" name="dni" type="text" placeholder="DNI" value="{{ old('dni',$estudiante->dni)}}" maxlength="8"
                pattern="[0-9]{8}">
            @error('dni')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                Correo
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="email" name="email" type="email" placeholder="Correo" value="{{ old('email',$estudiante->email) }}">
            @error('email')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="level">
                Nivel
            </label>
            <select
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="level" name="level">
                <option value="">Seleccionar Nivel</option>
                <option value="Primaria" {{ old('level',$estudiante->level)=='Primaria' ? 'selected' : '' }}>Primaria</option>
                <option value="Secundaria" {{ old('level',$estudiante->level)=='Secundaria' ? 'selected' : '' }}>Secundaria</option>
            </select>
            @error('level')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="grade">
                Grado
            </label>
            <select
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="grade" name="grade">
                <option value="">Seleccionar Grado</option>
                <option value="1ro" {{ old('grade',$estudiante->grade)=='1ro' ? 'selected' : '' }}>1ro</option>
                <option value="2do" {{ old('grade',$estudiante->grade)=='2do' ? 'selected' : '' }}>2do</option>
                <option value="3ro" {{ old('grade',$estudiante->grade)=='3ro' ? 'selected' : '' }}>3ro</option>
                <option value="4to" {{ old('grade',$estudiante->grade)=='4to' ? 'selected' : '' }}>4to</option>
                <option value="5to" {{ old('grade',$estudiante->grade)=='5to' ? 'selected' : '' }}>5to</option>
                <option value="6to" {{ old('grade',$estudiante->grade)=='6to' ? 'selected' : '' }}>6to</option>
            </select>
            @error('grade')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="section">
                Sección
            </label>
            <select
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="section" name="section">
                <option value="">Seleccionar Sección</option>
                <option value="A" {{ old('section',$estudiante->section)=='A' ? 'selected' : '' }}>A</option>
                <option value="B" {{ old('section',$estudiante->section)=='B' ? 'selected' : '' }}>B</option>
                <option value="C" {{ old('section',$estudiante->section)=='C' ? 'selected' : '' }}>C</option>
                <option value="D" {{ old('section',$estudiante->section)=='D' ? 'selected' : '' }}>D</option>
            </select>
            @error('section')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center justify-between">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Actualizar
            </button>
        </div>
    </form> --}}
    <form method="POST" action='{{ route('estudiantes.update', $estudiante->codigo_estudiante) }}' class="bg-white
        shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="primer_nombre">
                        Primer Nombre
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="primer_nombre" name="primer_nombre" type="text" placeholder="Primer Nombre"
                        value="{{ old('primer_nombre', $estudiante->primer_nombre) }}">
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
                        value="{{ old('otros_nombres', $estudiante->otros_nombres) }}">
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
                        value="{{ old('apellido_paterno', $estudiante->apellido_paterno) }}">
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
                        value="{{ old('apellido_materno', $estudiante->apellido_materno) }}">
                    @error('apellido_materno')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
    
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                        DNI
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="dni" name="dni" type="text" placeholder="DNI" value="{{ old('dni', $estudiante->dni) }}"
                        maxlength="8" pattern="[0-9]{8}">
                    @error('dni')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email" name="email" type="email" placeholder="Correo"
                        value="{{ old('email', $estudiante->email) }}">
                    @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
    
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_nacimiento">
                        Fecha de Nacimiento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="Fecha de Nacimiento"
                        value="{{ old('fecha_nacimiento', \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('Y-m-d')) }}">
                    @error('fecha_nacimiento')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="sexo">
                        Sexo
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="sexo" name="sexo">
                        <option value="">Seleccionar Sexo</option>
                        <option value="1" {{ old('sexo', $estudiante->sexo) == '1' ? 'selected' : '' }}>Masculino</option>
                        <option value="0" {{ old('sexo', $estudiante->sexo) == '0' ? 'selected' : '' }}>Femenino</option>
                    </select>
                    @error('sexo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
    
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="año_ingreso">
                        Año de Ingreso
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="año_ingreso" name="año_ingreso" type="number" placeholder="Año de Ingreso"
                        value="{{ old('año_ingreso', $estudiante->año_ingreso) }}">
                    @error('año_ingreso')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="lengua_materna">
                        Lengua Materna
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="lengua_materna" name="lengua_materna" type="text" placeholder="Lengua Materna"
                        value="{{ old('lengua_materna', $estudiante->lengua_materna) }}">
                    @error('lengua_materna')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
    
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="colegio_procedencia">
                        Colegio de Procedencia
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="colegio_procedencia" name="colegio_procedencia" type="text" placeholder="Colegio de Procedencia"
                        value="{{ old('colegio_procedencia', $estudiante->colegio_procedencia) }}">
                    @error('colegio_procedencia')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
    
            <div class="flex items-center justify-between">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Actualizar
                </button>
            </div>
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