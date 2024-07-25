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
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Mantenimiento de cátedras</h2>
            {{-- @can('docentes.control')
                <a href="{{ route('docentes.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Añadir
                </a>
            @endcan --}}
        </div>
        
        @if($user->hasRole('Admin'))
            <form method="GET" action="" class="mb-6">
                <div class="w-full mb-2">
                    <label for="docente" class="block text-sm font-medium text-gray-700">Docente</label>
                    <select name="docente" id="docente" class="shadow appearance-none border mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="0">Seleccionar</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->codigo_docente }}"
                                {{-- @if ($nivel->id_nivel == $filtranivel)
                                    selected 
                                @endif --}}
                            >
                                {{ $docente->apellido_paterno }} {{ $docente->apellido_materno }}, {{ $docente->primer_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full mb-2">
                    <label for="curso" class="block text-sm font-medium text-gray-700">Curso</label>
                    <select name="curso" id="curso" class="shadow appearance-none border mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="0">Seleccionar</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->codigo_curso }}"
                                {{-- @if ($nivel->id_nivel == $filtranivel)
                                    selected 
                                @endif --}}
                            >
                                {{ $curso->descripcion }}
                            </option>
                        @endforeach
                    </select>
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
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtrar
                </button>
                <a href="{{ route('cursos.malla') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Malla Curricular
                </a>
            </form>
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

    <script>
        function confirmDelete(id){
                alertify.confirm("¿Seguro que quieres eliminar al docente?",
                function(){
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/docentes/' + id ;
                    form.innerHTML = '@csrf @method("DELETE")';
                    document.body.appendChild(form);
                    form.submit();
                },
                function(){
                    alertify.error('Cancelado');
                });
            }
    </script>

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