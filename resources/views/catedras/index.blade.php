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
            <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Mantenimiento de Cátedras 
                @if(isset($aula))
                    {{ $aula->grado->detalle }} {{ $aula->detalle }} <span> de </span> {{ $aula->grado->nivel->detalle }}
                @endif</h2>
        </div>

        <form method="GET" action="{{ route('catedras.index') }}">
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
                <div class="md:ml-5 md:mt-0 lg:col-span-1" id="botonBuscar">
                    <button type="submit"
                        class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                        Buscar
                    </button>
                </div>
            </div>
            <input type="text" id="año_escolar" name="año_escolar" class="hidden" value="{{ request('año_escolar')}}" readonly>
        </form>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-md text-center text-gray-500 dark:text-gray-400">
                <thead class="text-md text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Código
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Descripción
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Docente
                        </th>
                        @can('Editar Catedras')
                            <th scope="col" class="px-6 py-3">
                                Acciones
                            </th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cursos as $curso)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $curso->codigo_curso }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $curso->descripcion}}
                        </td>
                        <td class="px-6 py-4">
                            @if($curso->catedras->isNotEmpty())
                            @foreach($curso->catedras as $catedra)
                            @if($catedra->docente)
                            {{ $catedra->docente->apellido_paterno}} {{ $catedra->docente->apellido_materno}}, {{
                            $catedra->docente->primer_nombre}}
                            @else
                            <span class="font-bold italic">Sin asignar</span>
                            @endif
                            @endforeach
                            @else
                            <span class="italic text-red-700">Sin asignar</span>
                            @endif
                        </td>
                        @can('Editar Catedras')
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-center">
                                    @if( $curso->catedras->isNotEmpty() && $curso->catedras->first()->docente )
                                    @can('Editar Catedras')
                                    <a href="{{ route('catedras.custom-edit', [$curso->codigo_curso, $aula->grado->nivel->id_nivel, $aula->grado->id_grado, $aula->id_seccion]) }}"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Modificar</a>
                                    @endcan
                                    @can('Eliminar Catedras')
                                    <button type="button"
                                        onclick="confirmDelete('{{ $curso->codigo_curso }}', '{{ $aula->grado->nivel->id_nivel }}', '{{ $aula->grado->id_grado }}', '{{ $aula->id_seccion }}')"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline ml-4">Eliminar</button>
                                    @endcan
                                    @else
                                    @can('Registrar Catedras')
                                    <a href="{{ route('catedras.custom-create', [$curso->codigo_curso, $aula->grado->nivel->id_nivel, $aula->grado->id_grado, $aula->id_seccion]) }}"
                                        class="font-medium text-green-600 dark:text-green-500 hover:underline">Asignar</a>
                                    @endcan
                                    @endif
                                </div>
                            </td>
                        @endcan
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

        {{-- <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Código</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Descripción</th>
                    <th
                    class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                    Docente</th>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if (count($cursos)<=0) <tr>
                    <td class="text-center py-2 px-4 border-b border-gray-200 italic" colspan="4">Indique el nivel, grado y sección</td>
                    </tr>
                    @else
                    @foreach ( $cursos as $curso )
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $curso->codigo_curso }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $curso->descripcion}}</td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            @if($curso->catedras->isNotEmpty())
                                @foreach($curso->catedras as $catedra)
                                    @if($catedra->docente)
                                    {{ $catedra->docente->apellido_paterno}} {{ $catedra->docente->apellido_materno}}, {{ $catedra->docente->primer_nombre}}
                                    @else
                                        <span class="font-bold italic">Sin asignar</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="italic text-red-700">Sin asignar</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b border-gray-200">
                            @if( $curso->catedras->isNotEmpty() && $curso->catedras->first()->docente )
                                <a href="{{ route('catedras.custom-edit', [$curso->codigo_curso, $aula->grado->nivel->id_nivel, $aula->grado->id_grado, $aula->id_seccion]) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Modificar</a>
                                <button type="button" onclick="confirmDelete('{{ $curso->codigo_curso }}', '{{ $aula->grado->nivel->id_nivel }}', '{{ $aula->grado->id_grado }}', '{{ $aula->id_seccion }}')"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Eliminar</button>
                            @else
                                <a href="{{ route('catedras.custom-create', [$curso->codigo_curso, $aula->grado->nivel->id_nivel, $aula->grado->id_grado, $aula->id_seccion]) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Asignar</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
            </tbody>
        </table> --}}
        <div class="mt-10">
            {{ $cursos->links() }}
        </div>
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
        function confirmDelete(codigo_curso, id_nivel, id_grado, id_seccion) {
            alertify.confirm("¿Seguro que quieres eliminar la cátedra asignada?", 
                function() {
                    // Crear formulario de eliminación
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/catedras/' + codigo_curso + '/' + id_nivel + '/' + id_grado + '/' + id_seccion;
    
                    // Incluir CSRF y método DELETE
                    let csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}'; // Laravel blade directive for CSRF token
                    
                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
    
                    // Agregar inputs al formulario
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
    
                    // Adjuntar y enviar formulario
                    document.body.appendChild(form);
                    form.submit();
                },
                function() {
                    alertify.error('Cancelado');
                }
            ).set('labels', {ok:'Sí', cancel:'No'}); // Opcional: Personalizar los botones
        }
    </script>
    

    <script>
        function updateGrados() {
            var nivel = document.getElementById('nivel').value;
            var grados = @json(['primaria' => $grados_primaria, 'secundaria' => $grados_secundaria]);
            
            var gradoSelect = document.getElementById('grado');
            gradoSelect.innerHTML = '<option value="" selected disabled >Seleccione un grado</option>'; // Reset options
            
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