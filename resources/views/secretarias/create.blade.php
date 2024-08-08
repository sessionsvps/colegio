@extends('layouts.main')

@section('contenido')
<!-- Formulario para agregar nuevo alumno -->
<div>

    <a href="{{ route('secretarias.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
    </a>

    @if ($errors->any())
        <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Error</span>
            <div>
                <span class="font-medium">Por favor, asegúrate de que estos requisitos se cumplan:</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('secretarias.store') }}" enctype="multipart/form-data">
        @csrf
        <!-- Datos de la Secretaria -->
        <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-bold mb-4">Datos de la Secretaria</h2>
            
            <div class="w-full" id="mensaje_verificacion"></div>
            
            <!-- DNI y Verificar -->
            
            <div class="mb-6 grid md:grid-cols-2 gap-5 lg:grid-rows-5">
                <div class="md:col-span-2 lg:col-span-1">
                    <div class="flex flex-col lg:flex-row lg:justify-between">
                        <div class="w-full mb-4 lg:mb-0 lg:mr-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                                DNI
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="dni" name="dni" type="text" placeholder="DNI" value="{{ old('dni') }}" maxlength="8"
                                pattern="[0-9]{8}">
                            @error('dni')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="lg:mt-7">
                            <button
                                class="w-full lg:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="button" id="boton_verificar">
                                Verificar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2 lg:col-span-1 lg:row-span-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                        Foto de Perfil
                    </label>
                    <div class="flex flex-col items-center border border-gray-300 rounded-lg p-4">
                        <img id="photoPreview"
                            class="w-48 h-52 lg:w-80 lg:h-96 rounded-lg border-2 border-dashed border-gray-300 mb-4">
                        <label
                            class="w-full lg:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer text-center">
                            Seleccionar Archivo
                            <input class="hidden" id="photo" name="photo" type="file" accept="image/*"
                                onchange="previewImage(event)">
                        </label>
                        @error('photo')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="primer_nombre">
                        Primer Nombre
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="primer_nombre" name="primer_nombre" type="text" placeholder="Primer Nombre"
                        value="{{ old('primer_nombre') }}" readonly>
                    @error('primer_nombre')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="lg:row-start-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="otros_nombres">
                        Otros Nombres
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="otros_nombres" name="otros_nombres" type="text" placeholder="Otros Nombres"
                        value="{{ old('otros_nombres') }}" readonly>
                    @error('otros_nombres')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="lg:row-start-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="apellido_paterno">
                        Apellido Paterno
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="apellido_paterno" name="apellido_paterno" type="text" placeholder="Apellido Paterno"
                        value="{{ old('apellido_paterno') }}" readonly>
                    @error('apellido_paterno')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="lg:row-start-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="apellido_materno">
                        Apellido Materno
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="apellido_materno" name="apellido_materno" type="text" placeholder="Apellido Materno"
                        value="{{ old('apellido_materno') }}" readonly>
                    @error('apellido_materno')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_nacimiento">
                        Fecha de Nacimiento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="Fecha de Nacimiento"
                        value="{{ old('fecha_nacimiento') }}" readonly>
                    @error('fecha_nacimiento')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="sexo">
                        Sexo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="sexo_texto" name="sexo_texto" type="text" placeholder="Sexo" value="{{ old('sexo_texto') }}" readonly>
                    <input id="sexo" name="sexo" type="hidden" value="{{ old('sexo') }}">
                    @error('sexo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email" name="email" type="email" placeholder="Correo" value="{{ old('email') }}">
                    @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono_celular">
                        Teléfono Celular
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono_celular" name="telefono_celular" type="text" placeholder="Teléfono Celular"
                        value="{{ old('telefono_celular') }}" maxlength="9">
                    @error('telefono_celular')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_estado">
                        Estado Civil
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="id_estado" name="id_estado">
                        @foreach($estados as $estado)
                        <option value="{{ $estado->id_estado }}" {{ old('id_estado')==$estado->id_estado ? 'selected' : '' }}>
                            {{ $estado->detalle }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_estado')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_ingreso">
                        Fecha de Ingreso
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="fecha_ingreso" name="fecha_ingreso" type="date" placeholder="Fecha de Ingreso"
                        value="{{ old('fecha_ingreso') }}">
                    @error('fecha_ingreso')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nacionalidad">
                        Nacionalidad
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="nacionalidad" name="nacionalidad" type="text" placeholder="Nacionalidad"
                        value="{{ old('nacionalidad') }}">
                    @error('nacionalidad')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="departamento">
                        Departamento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="departamento" name="departamento" type="text" placeholder="Departamento"
                        value="{{ old('departamento') }}">
                    @error('departamento')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="provincia">
                        Provincia
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="provincia" name="provincia" type="text" placeholder="Provincia" value="{{ old('provincia') }}">
                    @error('provincia')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 lg:w-1/4 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="distrito">
                        Distrito
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="distrito" name="distrito" type="text" placeholder="Distrito" value="{{ old('distrito') }}">
                    @error('distrito')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    
        <!-- Datos del Domicilio -->
        <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-bold mb-4">Datos del Domicilio</h2>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-2/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="direccion">
                        Dirección
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="direccion" name="direccion" type="text" placeholder="Dirección" value="{{ old('direccion') }}">
                    @error('direccion')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono_fijo">
                        Teléfono Fijo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono_fijo" name="telefono_fijo" type="text" placeholder="Teléfono Fijo"
                        value="{{ old('telefono_fijo') }}">
                    @error('telefono_fijo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="departamento_d">
                        Departamento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="departamento_d" name="departamento_d" type="text" placeholder="Departamento"
                        value="{{ old('departamento_d') }}">
                    @error('departamento_d')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="provincia_d">
                        Provincia
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="provincia_d" name="provincia_d" type="text" placeholder="Provincia"
                        value="{{ old('provincia_d') }}">
                    @error('provincia_d')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="distrito_d">
                        Distrito
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="distrito_d" name="distrito_d" type="text" placeholder="Distrito" value="{{ old('distrito_d') }}">
                    @error('distrito_d')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    
        <div class="flex items-center justify-center">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Agregar
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        document.getElementById('dni').addEventListener('input', function (e) {
            var value = e.target.value;
            e.target.value = value.replace(/[^0-9]/g, '').slice(0, 8);
        });
        document.getElementById('telefono_celular').addEventListener('input', function (e) {
            var value = e.target.value;
            e.target.value = value.replace(/[^0-9]/g, '').slice(0, 9);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const añoSelect = document.getElementById('año_actual');
            const fechaIngreso = document.getElementById('fecha_ingreso');
    
            function updateFechaIngresoLimits() {
                const selectedYear = añoSelect.value;
                fechaIngreso.min = `${selectedYear}-01-01`;
                fechaIngreso.max = `${selectedYear}-12-31`;
                añoInput.value = selectedYear;
            }
    
            añoSelect.addEventListener('change', updateFechaIngresoLimits);
    
            // Set initial value of the input and limits
            updateFechaIngresoLimits();
        });
    </script>

    <script>
        function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('photoPreview');
                    output.src = reader.result;
                    output.style.display = 'block';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
    </script>

    <script src="{{asset('js/verificar_dni.js')}}"></script>
@endsection