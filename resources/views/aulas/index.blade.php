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
            @if (Auth::user()->hasRole('Estudiante_Matriculado'))
                <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Aula</h2>
            @else
                <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Aulas</h2>                
            @endif
        </div>
    
        @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Secretaria') || Auth::user()->hasRole('Director'))
            <form method="GET" action="{{ route('aulas.index') }}" class="mb-6">
                <div class="mt-5 md:mt-10 grid grid-cols-2 md:grid-cols-4 md:gap-4">
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
                    <div class="mt-7 md:mt-0 col-span-1" id="botonBuscar">
                        <button type="submit"
                            class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full md:w-auto">
                            Buscar
                        </button>
                    </div>
                </div>
            </form>
        @endif

        <input class="hidden" type="text" id="año_escolar" name="año_escolar" value="{{ request('año_escolar')}}" readonly>

        @if(Auth::user()->hasRole('Estudiante_Matriculado'))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div class="relative flex flex-col mt-3 text-gray-700 bg-gray-50 shadow-md bg-clip-border rounded-xl">
                    <div
                        class="relative mx-4 mt-6 overflow-hidden text-white shadow-lg bg-clip-border rounded-xl bg-blue-gray-500 shadow-blue-gray-500/40">
                        <img src="{{ asset("img/aulas.webp") }}" alt="card-image" />
                    </div>
                    <div class="p-6">
                        <h5 class="block mb-2 text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                            {{ $aula->grado->detalle }} {{$aula->detalle}} de {{$aula->grado->nivel->detalle}}
                        </h5>
                    </div>
                    <div class="p-6 pt-0">
                        <a  id="aula-info-link"
                            href="{{ route('aulas.info', ['año_escolar' => 'TEMP', 'nivel' => $aula->id_nivel, 'grado' => $aula->id_grado, 'seccion' => $aula->id_seccion]) }}"
                            class="align-middle select-none font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                            type="button">
                            Ver Estudiantes
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($aulas as $aula)
                <div class="relative flex flex-col mt-3 text-gray-700 bg-gray-50 shadow-md bg-clip-border rounded-xl">
                    <div
                        class="relative mx-4 mt-6 overflow-hidden text-white shadow-lg bg-clip-border rounded-xl bg-blue-gray-500 shadow-blue-gray-500/40">
                        <img src="{{ asset("img/aulas.webp") }}" alt="card-image" />
                    </div>
                    <div class="p-6">
                        <h5 class="block mb-2 text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                            {{ $aula->grado->detalle }} {{$aula->detalle}} de {{$aula->grado->nivel->detalle}}
                        </h5>
                    </div>
                    <div class="p-6 pt-0">
                        <a  id="aula-info-link-{{ $aula->id_seccion }}"
                            href="{{ route('aulas.info', ['año_escolar' => 'TEMP', 'nivel' => $aula->id_nivel, 'grado' => $aula->id_grado, 'seccion' => $aula->id_seccion]) }}"
                            class="align-middle select-none font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                            type="button">
                            Ver Estudiantes
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-10">
                {{ $aulas->links() }}
            </div>
        @endif

    </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            var links = document.querySelectorAll("[id^='aula-info-link']");
            links.forEach(function(link) {
                link.addEventListener("click", function(event) {
                    var añoEscolar = document.getElementById("año_actual").value;
                    event.preventDefault();
                    var href = link.getAttribute("href");
                    href = href.replace('TEMP', añoEscolar);
                    window.location.href = href;
                });
            });
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