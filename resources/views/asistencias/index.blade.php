@extends('layouts.main')

@section('contenido')
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
    <p class="font-bold text-xl md:text-2xl lg:text-3xl">Inasistencias y Tardanzas</p>
    @if(Auth::user()->hasRole('Secretaria') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Director'))
        {{-- <form action="{{ route('asistencias.index') }}" method="GET">
            <div class="mt-5 md:mt-10 grid grid-cols-1 lg:grid-cols-3">
                <div class="mr-5">
                    <label for="codigo_estudiante" class="block text-sm font-medium text-gray-700">Código de estudiante</label>
                    <input type="text" name="codigo_estudiante" id="codigo_estudiante"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                        required maxlength="4" pattern="\d*" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div class="lg:mr-5">
                    <label for="bimestre" class="block text-sm font-medium text-gray-700">Bimestre</label>
                    <select id="bimestre" name="bimestre"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($bimestres as $bimestre)
                        <option value="{{ $bimestre->id }}" {{ request('bimestre')==$bimestre->id ? 'selected' : '' }}>
                            {{ $bimestre->descripcion }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5 md:mt-0 col-span-3 lg:col-span-1" id="botonBuscar">
                    <button type="submit"
                        class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                        Buscar
                    </button>
                </div>
            </div>
        </form> --}}
        <form method="GET" action="{{ route('asistencias.index') }}" class="mb-6">
            <div class="mt-5 md:mt-10 grid grid-cols-2 md:grid-cols-3 md:gap-4">
                <div class="mr-5 md:mr-0">
                    <label for="nivel" class="block text-sm font-medium text-gray-700">Nivel</label>
                    <select id="nivel" name="nivel"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" {{ request('nivel')=='' ? 'selected' : '' }}>Todos</option>
                        @foreach($niveles as $nivel)
                        <option value="{{ $nivel->id_nivel }}" {{ request('nivel')==$nivel->id_nivel ? 'selected' : '' }}>
                            {{ $nivel->detalle }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="grado" class="block text-sm font-medium text-gray-700">Grado</label>
                    <select id="grado" name="grado"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
        
                    </select>
                </div>
                <div class="mr-5 mt-1 md:mt-0 md:mr-0">
                    <label for="seccion" class="block text-sm font-medium text-gray-700">Sección</label>
                    <select id="seccion" name="seccion"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" {{ request('seccion')=='' ? 'selected' : '' }}>Todos</option>
                        <option value="1" {{ request('seccion')=='1' ? 'selected' : '' }}>A</option>
                        <option value="2" {{ request('seccion')=='2' ? 'selected' : '' }}>B</option>
                        <option value="3" {{ request('seccion')=='3' ? 'selected' : '' }}>C</option>
                        <option value="4" {{ request('seccion')=='4' ? 'selected' : '' }}>D</option>
                    </select>
                </div>
                <div class="mt-1">
                    <label for="bimestre" class="block text-sm font-medium text-gray-700">Bimestre</label>
                    <select id="bimestre" name="bimestre"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($bimestres as $bimestre)
                        <option value="{{ $bimestre->id }}" {{ request('bimestre')==$bimestre->id ? 'selected' : '' }}>
                            {{ $bimestre->descripcion }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-1 mr-5 md:mr-0">
                    <label for="buscar_por" class="block text-sm font-medium text-gray-700">Buscar Por</label>
                    <select id="buscar_por" name="buscar_por"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" {{ request('buscar_por')=='' ? 'selected' : '' }}>Todos</option>
                        <option value="codigo" {{ request('buscar_por')=='codigo' ? 'selected' : '' }}>Código</option>
                        <option value="nombre" {{ request('buscar_por')=='nombre' ? 'selected' : '' }}>Nombre</option>
                        <option value="dni" {{ request('buscar_por')=='dni' ? 'selected' : '' }}>DNI</option>
                        <option value="correo" {{ request('buscar_por')=='correo' ? 'selected' : '' }}>Correo</option>
                    </select>
                </div>
                <div id="inputContainer" class="mt-1">
                    <!-- Aquí se insertarán los inputs dinámicamente -->
                </div>
                <input type="text" id="año_escolar" name="año_escolar" class="hidden" value="{{ request('año_escolar')}}" readonly>
                <div class="mt-3 col-span-2 md:col-span-1" id="botonBuscar">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                        Buscar
                    </button>
                </div>
            </div>
        </form>
    @else
        <form method="GET" action="{{ route('asistencias.index') }}" class="mb-6">
            <div class="mt-5 md:mt-10 grid grid-cols-1 md:grid-cols-2">
                <div class="md:mr-5">
                    <label for="bimestre" class="block text-sm font-medium text-gray-700">Bimestre</label>
                    <select id="bimestre" name="bimestre"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach($bimestres as $bimestre)
                        <option value="{{ $bimestre->id }}" {{ request('bimestre')==$bimestre->id ? 'selected' : '' }}>
                            {{ $bimestre->descripcion }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <input type="text" id="año_escolar" name="año_escolar" class="hidden" value="{{ request('año_escolar')}}" readonly>
                <div class="mt-4 md:mt-6 col-span-2 md:col-span-1" id="botonBuscar">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                        Buscar
                    </button>
                </div>
            </div>
        </form>
    @endif
    
    @if(Auth::user()->hasRole('Estudiante_Matriculado'))
        @if ($estudiante)
            <div class="mt-10 md:mt-20 grid lg:grid-cols-2 gap-5">
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
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante
                            }}
                        </p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}</p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Correo:</span> {{ $estudiante->estudiante->email
                            }}</p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Aula:</span> {{
                            $estudiante->seccion->grado->detalle .
                            ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
                    </div>
                </div>
                @if($asistencia)
                <div class=" bg-gray-50 shadow-md rounded-lg p-6">
                    <div class="flex justify-end mb-4">
                        @can('Editar Asistencias')
                        @if ($asistencia->bimestre->esActivo)
                        <a
                            href="{{route('asistencias.edit',['codigo_estudiante' => $asistencia->codigo_estudiante, 'id_bimestre' => $asistencia->id_bimestre, 'año_escolar' => $asistencia->año_escolar]) }}">
                            <button
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-700 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Editar
                            </button>
                        </a>
                        @endif
                        @endcan
                    </div>
                    <ul class="space-y-4">
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Inasistencias Justificadas: {{ $asistencia->inasistencias_justificadas }}
                            </div>
                        </li>
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Inasistencias Injustificadas: {{ $asistencia->inasistencias_injustificadas }}
                            </div>
                        </li>
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Tardanzas Justificadas: {{ $asistencia->tardanzas_justificadas }}
                            </div>
                        </li>
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Tardanzas Injustificadas: {{ $asistencia->tardanzas_injustificadas }}
                            </div>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        @endif
    @else
        @if($estudiantes)
            @foreach ($estudiantes as $estudiante)
                @php
                $asistencia = $asistencias->where('codigo_estudiante', $estudiante->codigo_estudiante)->first();
                @endphp
            <div class="mt-10 md:mt-20 grid lg:grid-cols-2 gap-5">
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
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante
                            }}
                        </p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">DNI:</span> {{ $estudiante->estudiante->dni }}</p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Correo:</span> {{ $estudiante->estudiante->email
                            }}</p>
                        <p class="text-gray-700 mt-1"><span class="font-semibold">Aula:</span> {{
                            $estudiante->seccion->grado->detalle .
                            ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
                    </div>
                </div>
                @if($asistencia)
                <div class=" bg-gray-50 shadow-md rounded-lg p-6">
                    <div class="flex justify-end mb-4">
                        @can('Editar Asistencias')
                        <a
                            href="{{route('asistencias.edit',['codigo_estudiante' => $asistencia->codigo_estudiante, 'id_bimestre' => $asistencia->id_bimestre, 'año_escolar' => $asistencia->año_escolar]) }}">
                            <button
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-700 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Editar
                            </button>
                        </a>
                        @endcan
                    </div>
                    <ul class="space-y-4">
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Inasistencias Justificadas: {{ $asistencia->inasistencias_justificadas }}
                            </div>
                        </li>
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Inasistencias Injustificadas: {{ $asistencia->inasistencias_injustificadas }}
                            </div>
                        </li>
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Tardanzas Justificadas: {{ $asistencia->tardanzas_justificadas }}
                            </div>
                        </li>
                        <li class="p-4 bg-white rounded-lg shadow-sm flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-gray-800">
                                Tardanzas Injustificadas: {{ $asistencia->tardanzas_injustificadas }}
                            </div>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
            @endforeach
        @endif
    @endif
@endsection

@section('scripts')
    @if (!Auth::user()->hasRole('Estudiante_Matriculado') && !Auth::user()->hasRole('Apoderado'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                            const nivelSelect = document.getElementById('nivel');
                            const gradoSelect = document.getElementById('grado');
                            
                            const gradosPrimaria = @json($grados_primaria);
                            const gradosSecundaria = @json($grados_secundaria);
                            const selectedGrado = '{{ request('grado') }}';
                            
                            function actualizarGrados() {
                            const nivel = nivelSelect.value;
                            gradoSelect.innerHTML = '';
                            
                            let opciones = [];
                            if (nivel == 2) { // Secundaria
                            opciones = gradosSecundaria;
                            } else { // Primaria o Todos
                            opciones = gradosPrimaria;
                            }
                
                            // Agregar la opción "Todos" al principio
                            const optionTodos = document.createElement('option');
                            optionTodos.value = '';
                            optionTodos.textContent = 'Todos';
                            optionTodos.selected = (selectedGrado === '');
                            gradoSelect.appendChild(optionTodos);
                
                            if (opciones.length > 0) {
                            opciones.forEach(grado => {
                                const option = document.createElement('option');
                                option.value = grado.id_grado;
                                option.textContent = grado.detalle;
                                option.selected = (grado.id_grado == selectedGrado);
                                gradoSelect.appendChild(option);
                            });
                            } else {
                            const option = document.createElement('option');
                            option.value = '';
                            option.textContent = 'Todos';
                            gradoSelect.appendChild(option);
                            }
                            }
                            
                            nivelSelect.addEventListener('change', actualizarGrados);
                            
                            // Trigger change event on page load to populate the grades if a level is already selected or if "Todos" is selected
                            actualizarGrados();
                        });
        </script>
    @endif
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const buscarPorSelect = document.getElementById('buscar_por');
                    const inputContainer = document.getElementById('inputContainer');
            
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const añoSelect = document.getElementById('año_actual');
                const añoInput = document.getElementById('año_escolar');
        
                añoSelect.addEventListener('change', function() {
                    añoInput.value = añoSelect.value;
                });
        
                // Set initial value of the input
                añoInput.value = añoSelect.value;
            });
    </script>
@endsection
