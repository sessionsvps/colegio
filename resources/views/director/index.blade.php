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
    <div class="flex justify-between items-center mb-10">
        <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Director</h2>
        @can('Registrar Director')
        @if (!$director)
            <a href="{{ route('director.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Añadir
            </a>
        @endif
        @endcan
    </div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($director)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $director->codigo_director }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $director->primer_nombre }} {{ $director->otros_nombres }} {{ $director->apellido_paterno }} {{
                        $director->apellido_materno }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $director->dni }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $director->email }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-center">
                            @can('Editar Director')
                            <a href="{{ route('director.edit', $director->codigo_director) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                            @endcan
                            @can('Eliminar Director')
                            <button type="button" onclick="confirmDelete('{{ $director->codigo_director }}')"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline ml-4">Eliminar</button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @else
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        No hay registro
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
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
                alertify.confirm("¿Seguro que quieres eliminar al director?",
                function(){
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/director/' + id ;
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
