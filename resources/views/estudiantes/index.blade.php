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
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Lista de Estudiantes</h2>
        @can('Registrar Estudiantes')
        <a href="{{ route('estudiantes.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Añadir
        </a>
        @endcan
    </div>

    @if (Auth::user()->hasRole('Docente') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Secretaria'))
        <form method="GET" action="{{ route('estudiantes.index') }}">
            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label for="filtrar_por" class="block text-sm font-medium text-gray-700">Filtrar Por</label>
                    <select id="filtrar_por" name="filtrar_por"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" {{ request('filtrar_por')=='' ? 'selected' : '' }}>Todos</option>
                        <option value="matriculado" {{ request('filtrar_por')=='matriculado' ? 'selected' : '' }}>Matriculado
                        </option>
                        <option value="no_matriculado" {{ request('filtrar_por')=='no_matriculado' ? 'selected' : '' }}>No
                            Matriculado</option>
                    </select>
                </div>
                <div>
                    <label for="año_ingreso" class="block text-sm font-medium text-gray-700">Año de Ingreso</label>
                    <select id="año_ingreso" name="año_ingreso"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="" {{ request('año_ingreso')=='' ? 'selected' : '' }}>Todos</option>
                        <option value="2024" {{ request('año_ingreso')=='2024' ? 'selected' : '' }}>2024</option>
                        <option value="2023" {{ request('año_ingreso')=='2023' ? 'selected' : '' }}>2023</option>
                        <option value="2022" {{ request('año_ingreso')=='2022' ? 'selected' : '' }}>2022</option>
                        <option value="2021" {{ request('año_ingreso')=='2021' ? 'selected' : '' }}>2021</option>
                    </select>
                </div>
                <div>
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
                <div id="inputContainer">
                    <!-- Aquí se insertarán los inputs dinámicamente -->
                </div>
                <div class="col-span-2 lg:mt-6 lg:col-span-1" id="botonBuscar">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                        Buscar
                    </button>
                </div>
            </div>
        </form>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="reporte" class="block text-sm font-medium text-gray-700">Seleccione un Formato</label>
                <select id="reporte" name="reporte"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="" selected disabled></option>
                    <option value="0">PDF</option>
                    <option value="1">EXCEL</option>
                </select>
            </div>
            <div>
                <a id="reportButton"
                    class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                    Generar Reporte
                </a>
            </div>
        </div>
    @endif

    <div class="mt-10 relative overflow-x-auto shadow-md sm:rounded-lg">
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
                    @if (Auth::user()->hasRole('Apoderado'))
                        <th scope="col" class="px-6 py-3">
                            Aula
                        </th>
                    @endif
                    <th scope="col" class="px-6 py-3">
                        Correo
                    </th>
                    @if (!Auth::user()->hasRole('Apoderado'))
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($estudiantes as $estudiante)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $estudiante->codigo_estudiante }}
                    </th>
                    <td class="px-6 py-4">
                        @if (Auth::user()->hasRole('Apoderado'))
                            {{ $estudiante->estudiante->primer_nombre }} {{ $estudiante->estudiante->otros_nombres }} {{
                            $estudiante->estudiante->apellido_paterno }} {{
                            $estudiante->estudiante->apellido_materno }}
                        @else
                            {{ $estudiante->primer_nombre }} {{ $estudiante->otros_nombres }} {{
                            $estudiante->apellido_paterno }} {{
                            $estudiante->apellido_materno }}
                        @endif 
                    </td>
                    <td class="px-6 py-4">
                        @if (Auth::user()->hasRole('Apoderado'))
                            {{ $estudiante->estudiante->dni }}
                        @else
                            {{ $estudiante->dni }}
                        @endif
                    </td>
                    @if (Auth::user()->hasRole('Apoderado'))
                        <td class="px-6 py-4">
                            {{ $estudiante->seccion->grado->detalle }} {{ $estudiante->seccion->detalle }} de {{ $estudiante->seccion->grado->nivel->detalle }}
                        </td>
                    @endif
                    <td class="px-6 py-4">
                        @if (Auth::user()->hasRole('Apoderado'))
                            {{ $estudiante->estudiante->user->email }}
                        @else
                            {{ $estudiante->user->email }}
                        @endif
                    </td>
                    @if (!Auth::user()->hasRole('Apoderado'))
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-center">
                                @can('Editar Estudiantes')
                                <a href="{{ route('estudiantes.edit', $estudiante->codigo_estudiante) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                                @endcan
                                @can('Eliminar Estudiantes')
                                <button type="button" onclick="confirmDelete('{{ $estudiante->codigo_estudiante }}')"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline ml-4">Eliminar</button>
                                @endcan
                            </div>
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-10">
        {{ $estudiantes->links() }}
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
    document.addEventListener('DOMContentLoaded', function () {
        var reporteSelect = document.getElementById('reporte');
        var generateReportButton = document.getElementById('reportButton');

        reporteSelect.addEventListener('change', function () {
            var selectedValue = this.value;
            if (selectedValue == '1') { // EXCEL
                generateReportButton.href = "{{ route('export') }}";
            } else if (selectedValue == '0') { // PDF
                generateReportButton.href = "{{ route('exportPdfEstu') }}";
            }
        });
    });
</script>
@endsection
