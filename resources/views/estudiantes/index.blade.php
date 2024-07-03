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
            <h2 class="text-xl font-bold">Lista de Alumnos</h2>
            @can('estudiantes.control')
                <a href="{{ route('estudiantes.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Añadir Estudiante
                </a>
            @endcan
        </div>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Código</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Nombre(s)</th>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Apellidos</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        DNI</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Correo</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Fecha Nacimiento</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Sexo</th>
                    <th
                        class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Año Ingreso</th>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Lengua Materna</th>
                    <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                        Colegio Procedencia</th>
                    @can('estudiantes.control')
                        <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                            Acciones</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @if (count($estudiantes)<=0)
                    <tr>
                        <td class="text-center py-2 px-4 border-b border-gray-200" colspan="11">No hay registros</td>
                    </tr>
                @else
                    @foreach ( $estudiantes as $estudiante )
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->codigo_estudiante }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->primer_nombre . ' ' . $estudiante->otros_nombres }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->apellido_paterno . ' ' . $estudiante->apellido_materno }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->dni }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->email }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($estudiante->fecha_nacimiento)->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->sexo }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->año_ingreso }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->lengua_materna }}</td>
                        <td class="py-2 px-4 border-b border-gray-200">{{ $estudiante->colegio_procedencia }}</td>
                        @can('estudiantes.control')
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{route('estudiantes.edit', $estudiante->codigo_estudiante)}}"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2">Editar</a>
                                <button type="button" onclick="confirmDelete('{{ $estudiante->codigo_estudiante }}')"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline">Borrar</button>
                            </td>
                        @endcan
                    </tr>
                    @endforeach
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
            alertify.confirm("¿Seguro que quieres eliminar al estudiante?",
            function(){
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/estudiantes/' + id;
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