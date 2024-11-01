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
            <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Lista de Cursos</h2>
        </div>

        @if ($user->hasRole('Admin') || $user->hasRole('Secretaria') || $user->hasRole('Director'))
            <form method="GET" action="{{ route('cursos.index') }}" class="mb-6">
                <div class="mb-4">
                    <label for="nivel_educativo" class="block text-sm font-medium text-gray-700">Nivel Educativo:</label>
                    <select name="nivel_educativo" id="nivel_educativo"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="0">Todos</option>
                        @foreach ($niveles as $nivel)
                            <option value="{{ $nivel->id_nivel }}" @if ($nivel->id_nivel == $filtranivel) selected @endif>
                                {{ $nivel->detalle }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtrar
                </button>
                <a href="{{ route('cursos.malla') }}"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Malla Curricular
                </a>
            </form>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($cursos as $curso)
                <div class="relative flex flex-col mt-3 text-gray-700 bg-gray-50 shadow-md bg-clip-border rounded-xl">
                    <div
                        class="relative mx-4 mt-6 overflow-hidden text-white shadow-lg bg-clip-border rounded-xl bg-blue-gray-500 shadow-blue-gray-500/40">
                        <img src="{{ asset('img/cursos.jpg') }}" alt="card-image" />
                    </div>
                    <div class="p-6">
                        <h5
                            class="block mb-2 text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                            {{ $curso->descripcion }}
                        </h5>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="{{ route('cursos.info', ['id' => $curso->codigo_curso, 'año_escolar' => 'TEMP']) }}"
                            class="boton-ver-mas align-middle select-none font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-3 px-6 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                            type="button">
                            Ver más
                        </a>
                    </div>
                </div>
            @endforeach
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
        document.addEventListener("DOMContentLoaded", function() {
            var añoEscolar = document.getElementById("año_actual").value;
            var botonesVerMas = document.querySelectorAll(".boton-ver-mas");

            botonesVerMas.forEach(function(boton) {
                boton.addEventListener("click", function(event) {
                    var añoEscolar = document.getElementById("año_actual").value;
                    event.preventDefault();
                    var href = boton.getAttribute("href");
                    href = href.replace('TEMP', añoEscolar);
                    window.location.href = href;
                });
            });
        });
    </script>

    <script>
        function confirmDelete(id) {
            alertify.confirm("¿Seguro que quieres eliminar al docente?",
                function() {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/docentes/' + id;
                    form.innerHTML = '@csrf @method('DELETE')';
                    document.body.appendChild(form);
                    form.submit();
                },
                function() {
                    alertify.error('Cancelado');
                });
        }
    </script>
@endsection
