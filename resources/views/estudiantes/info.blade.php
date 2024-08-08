@extends('layouts.main')

@section('contenido')
<div>
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
    <div class="flex justify-between">
        <a href="{{ route('estudiantes.matricular') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
        </a>

        <a href="{{ route('estudiantes.añade-matriculas', $estudiante->codigo_estudiante) }}"
        class="bg-indigo-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Añadir
        </a>
    </div>

    <!-- Componente del Encabezado del Estudiante -->
    <div class="mt-10 flex items-center bg-gray-100 shadow-md rounded-lg p-6 space-x-4">
        <div class="flex-shrink-0">
            <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
            </svg>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $estudiante->primer_nombre . ' ' . $estudiante->otros_nombres . ' ' . $estudiante->apellido_paterno . ' ' . $estudiante->apellido_materno }}</h3>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Código:</span> {{ $estudiante->codigo_estudiante }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">DNI:</span> {{ $estudiante->dni }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Correo:</span> {{ $estudiante->user->email }}</p>
        </div>
    </div>

    @if(!$matriculas->isEmpty())
        <div class="mt-10">
            <h1 class="font-bold text-3xl">Matrículas</h1>
        </div>
    @else
        <div class="mt-10">
            <h1 class="font-bold text-3xl">Sin matrículas registradas</h1>
        </div>
    @endif
    <!-- Listado de Matrículas -->
    @foreach($matriculas as $matricula)
    <div class="mt-10 flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
        <div class="flex-shrink-0">
            <svg class="w-16 h-16 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
            </svg>
        </div>
        <div>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Año Escolar:</span> {{ $matricula->año_escolar }}</p>
            <p class="text-gray-700 mt-1"><span class="font-semibold">Aula:</span> {{ $matricula->seccion->grado->detalle . ' ' . $matricula->seccion->detalle . ' de ' . $matricula->seccion->grado->nivel->detalle }}</p>
            <a href="javascript:void(0);" onclick="confirmDelete('{{ $matricula->codigo_estudiante }}', '{{ $matricula->id_nivel }}', '{{ $matricula->id_grado }}', '{{ $matricula->id_seccion }}', '{{ $matricula->año_escolar }}')" class="font-medium text-red-600 dark:text-red-500 hover:underline">Eliminar</a>
        </div>
    </div>
    @endforeach
</div>
@endsection

<script>
    function confirmDelete(codigo_estudiante, id_nivel, id_grado, id_seccion, año_escolar) {
        alertify.confirm("¿Seguro que quieres eliminar la matrícula?", 
            function() {
                // Crear formulario de eliminación
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/matricula/' + codigo_estudiante + '/' + id_nivel + '/' + id_grado + '/' + id_seccion + '/' + año_escolar;

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
