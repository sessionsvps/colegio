@extends('layouts.main')

@section('contenido')
    <p class="font-bold text-xl md:text-2xl lg:text-3xl">Boleta de Notas</p>
    @if(Auth::user()->hasRole('Secretaria') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Director'))
        <form method="GET" action="{{ route('boleta_notas.index') }}" class="mb-6">
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
                <div id="inputContainer" class="mt-1 mr-5 md:mr-0">
                    <!-- Aquí se insertarán los inputs dinámicamente -->
                </div>
                <div class="mt-7" id="botonBuscar">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-1/2">
                        Buscar
                    </button>
                </div>
            </div>
            <input type="text" id="año_escolar" name="año_escolar" class="hidden" value="{{ request('año_escolar')}}" readonly>
        </form>
    @endif

    @if(Auth::user()->hasRole('Secretaria') || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Director'))
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">
                    Generar Reporte
                </a>
            </div>
        </div>
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
                        <th scope="col" class="px-6 py-3">
                            Correo
                        </th>
                        @can('Ver Notas')
                        <th scope="col" class="px-6 py-3">
                            Notas
                        </th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($estudiantes as $estudiante)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $estudiante->codigo_estudiante }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $estudiante->estudiante->primer_nombre }} {{ $estudiante->estudiante->otros_nombres }} {{
                            $estudiante->estudiante->apellido_paterno }} {{
                            $estudiante->estudiante->apellido_materno }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $estudiante->estudiante->dni }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $estudiante->estudiante->user->email }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-center">
                                @can('Ver Notas')
                                <a href="{{ route('boleta_notas.info', ['codigo_estudiante'=>$estudiante->codigo_estudiante,'año_escolar'=>$estudiante->año_escolar]) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Ver Notas</a>
                                @endcan
                            </div>
                        </td>
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
    @else
        <div class="mt-10 flex items-center bg-gray-50 shadow-md rounded-lg p-6 space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-16 h-16 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
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
                <p class="text-gray-700 mt-1"><span class="font-semibold">Aula:</span> {{
                    $estudiante->seccion->grado->detalle .
                    ' ' . $estudiante->seccion->detalle . ' de ' . $estudiante->seccion->grado->nivel->detalle}}</p>
            </div>
        </div>
        @foreach ( $cursos as $curso )
        @php
        $competencias = $curso->curso->competencias
        @endphp
        <div class="mt-6 bg-gray-50 shadow-md rounded-lg p-6">
            <p class="text-lg font-bold text-indigo-900">{{$curso->curso->descripcion}} ({{$curso->codigo_curso}})</p>
            <div class="grid grid-cols-1 gap-4 mt-4">
                @foreach ( $competencias as $competencia )
                <div class="grid grid-cols-2 gap-4 items-center">
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        <div class="font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 10.5l-1.5-1.5-1.415 1.415L8 13.414l6-6-1.415-1.414L8 10.5z" />
                            </svg>
                            {{$competencia->descripcion}}
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2 lg:h-full">
                        @for ($bimestre = 1; $bimestre <= 4; $bimestre++) @php $nota=$notas->
                            where('id_bimestre',$bimestre)->where('codigo_curso',
                            $curso->codigo_curso)->where('orden',$competencia->orden)->first();
                            @endphp
                            <div
                                class="lg:flex items-center justify-center text-center text-gray-700 border border-gray-300 rounded-lg p-2 shadow uppercase {{ $nota ? ($nota->nivel_logro == 'A' || $nota->nivel_logro == 'B' || $nota->nivel_logro == 'AD' ?  'bg-green-100' : ($nota->nivel_logro == 'C' ? 'bg-red-100' : 'bg-white')) : 'bg-white' }}">
                                {{ $nota ? $nota->nivel_logro : '' }}
                            </div>
                            @endfor
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
@endsection

@section('scripts')
    @if (!Auth::user()->hasRole('Estudiante_Matriculado'))
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
        document.addEventListener('DOMContentLoaded', function () {
            var reporteSelect = document.getElementById('reporte');
            var generateReportButton = document.getElementById('reportButton');

            // Recuperar el código del estudiante del servidor y almacenarlo en una variable
            var codigoEstudiante = "{{ $estudiante ? $estudiante->codigo_estudiante : '' }}";

            reporteSelect.addEventListener('change', function () {
                var selectedValue = this.value;
                if (selectedValue == '1') { // EXCEL
                    generateReportButton.href = "{{ route('export') }}";
                } else if (selectedValue == '0') { // PDF
                    generateReportButton.href = "{{ route('exportPdfNotas', ['codigo_estudiante' => '__codigo_estudiante__']) }}".replace('__codigo_estudiante__', codigoEstudiante);
                }
            });
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
