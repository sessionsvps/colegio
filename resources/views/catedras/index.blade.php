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
            <h2 class="text-xl font-bold">Mantenimiento de Cátedras 
                @if(isset($aula))
                    {{ $aula->grado->detalle }} {{ $aula->detalle }} <span> de </span> {{ $aula->grado->nivel->detalle }}
                @endif</h2>
        </div>

        <form method="GET" action="{{ route('catedras.index') }}">
            <div class="bg-gray-50 rounded px-8 py-1 mb-4">
                <div class="flex flex-wrap -mx-3 mb-3">
                    <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6 lg:mb-0">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nivel">
                            Nivel
                        </label>
                        <select
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="nivel" name="nivel" onchange="updateGrados()" required>
                            <option value="" selected disabled>Seleccione un nivel</option>
                            @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }} " {{ request('nivel')== $nivel->id_nivel ? 'selected' : '' }} >
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
                            id="grado" name="grado" required>
                            @if(!isset($filtra_nivel))
                                <option value="" selected disabled>Seleccione un grado</option>
                            @else
                                @if($filtra_nivel == 1)
                                    @foreach($grados_primaria as $grado_p)
                                        <option value="{{ $grado_p->id_grado }}" {{ request('grado') == $grado_p->id_grado ? 'selected' : '' }}>
                                            {{ $grado_p->detalle }}
                                        </option>
                                    @endforeach
                                @elseif($filtra_nivel == 2)
                                    @foreach($grados_secundaria as $grado_s)
                                        <option value="{{ $grado_s->id_grado }}" {{ request('grado') == $grado_s->id_grado ? 'selected' : '' }}>
                                            {{ $grado_s->detalle }}
                                        </option>
                                    @endforeach
                                @endif
                            @endif
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
                            id="seccion" name="seccion" required>
                            <option value="" selected disabled>Seleccione una sección</option>
                            <option value="1" {{ request('seccion')== 1 ? 'selected' : '' }}>A</option>
                            <option value="2" {{ request('seccion')== 2 ? 'selected' : '' }}>B</option>
                            <option value="3" {{ request('seccion')== 3 ? 'selected' : '' }}>C</option>
                            <option value="4" {{ request('seccion')== 4 ? 'selected' : '' }}>D</option>
                        </select>
                        @error('seccion')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtrar
                </button>
            </div>
        </form>

        <table class="min-w-full bg-white">
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
                            @else
                                <a href="{{ route('catedras.custom-create', [$curso->codigo_curso, $aula->grado->nivel->id_nivel, $aula->grado->id_grado, $aula->id_seccion]) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Asignar</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endif
            </tbody>
        </table>
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
@endsection