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
            <h2 class="text-xl font-bold">Lista de Cursos</h2>
            {{-- @can('docentes.control')
                <a href="{{ route('docentes.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Añadir
                </a>
            @endcan --}}
        </div>
        
        @if($user->hasRole('Admin'))
            <form method="GET" action="{{ route('cursos.index') }}" class="mb-6">
                <div class="mb-4">
                    <label for="nivel_educativo" class="block text-sm font-medium text-gray-700">Nivel Educativo:</label>
                    <select name="nivel_educativo" id="nivel_educativo" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="0">Todos</option>
                        @foreach($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }}"
                                @if ($nivel->id_nivel == $filtranivel)
                                    selected 
                                @endif
                            >
                                {{ $nivel->detalle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtrar
                </button>
            </form>
        @endif

        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Código</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Descripción</th>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Año de actualización</th>
                    {{-- @can('docentes.control')
                        <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                            Acciones</th>
                    @endcan --}}
                </tr>
            </thead>
            <tbody>
                @if (count($cursos)<=0) <tr>
                    <td class="text-center py-2 px-4 border-b border-gray-200" colspan="3">No hay registros</td>
                    </tr>
                    @else
                    @foreach ( $cursos as $curso )
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $curso->codigo_curso }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $curso->descripcion}}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $curso->año_actualizacion }}</td>
                        {{-- @can('docentes.control')
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('docentes.edit', $docente->codigo_docente) }}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Editar</a>
                                <button type="button" onclick="confirmDelete('{{ $docente->codigo_docente }}')"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Borrar</button>
                            </td>
                        @endcan --}}
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
@endsection