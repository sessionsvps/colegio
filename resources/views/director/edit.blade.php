@extends('layouts.main')

@section('contenido')

<div>
    <a href="{{ route('director.index') }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4 inline-block">
        Volver
    </a>
    <h2 class="text-xl font-bold mb-4">Editar Director</h2>

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

    <form method="POST" action="{{ route('director.update',$director->codigo_director) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Datos del director -->
        <div class="bg-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-lg font-bold mb-4">Datos del director</h2>

            <div class="mb-6 grid md:grid-cols-2 gap-5 lg:grid-rows-5">
                <div class="md:col-span-2 lg:col-span-1">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="mb-4 md:mr-5 lg:mb-0">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="dni">
                                DNI
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="dni" name="dni" type="text" placeholder="DNI" value="{{ old('dni', $director->dni) }}"
                                maxlength="8" pattern="[0-9]{8}" readonly>
                            @error('dni')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="fecha_nacimiento">
                                Fecha de Nacimiento
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="Fecha de Nacimiento"
                                value="{{ old('fecha_nacimiento', $director->fecha_nacimiento) }}" readonly>
                            @error('fecha_nacimiento')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2 lg:col-span-1 lg:row-span-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                        Foto de Perfil
                    </label>
                    <div class="flex flex-col items-center border border-gray-300 rounded-lg p-4">
                        <img id="photoPreview" src="{{ $director->user->profile_photo_url }}"
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
                        value="{{ old('primer_nombre', $director->primer_nombre) }}" readonly>
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
                        value="{{ old('otros_nombres', $director->otros_nombres) }}" readonly>
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
                        value="{{ old('apellido_paterno', $director->apellido_paterno) }}" readonly>
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
                        value="{{ old('apellido_materno', $director->apellido_materno) }}" readonly>
                    @error('apellido_materno')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="sexo">
                        Sexo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="sexo_texto" name="sexo_texto" type="text"
                        value="{{ old('sexo', $director->sexo == 1 ? 'Masculino' : 'Femenino') }}" readonly>
                    <input id="sexo" name="sexo" type="hidden" value="{{ old('sexo', $director->sexo) }}">
                    @error('sexo')
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
                        readonly value="{{ old('fecha_ingreso', $director->fecha_ingreso) }}">
                    @error('fecha_ingreso')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Correo
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="email" name="email" type="email" placeholder="Correo" value="{{ old('email', $director->email) }}">
                    @error('email')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div> 
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefono_celular">
                        Teléfono Celular
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="telefono_celular" name="telefono_celular" type="text" placeholder="Teléfono Celular"
                        value="{{ old('telefono_celular', $director->telefono_celular) }}" maxlength="9">
                    @error('telefono_celular')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 px-3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_estado">
                        Estado Civil
                    </label>
                    <select
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="id_estado" name="id_estado">
                        @foreach($estados as $estado)
                        <option value="{{ $estado->id_estado }}" {{ old('id_estado', $director->id_estado) == $estado->id_estado ?
                            'selected' : '' }}>
                            {{ $estado->detalle }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_estado')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6 lg:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nacionalidad">
                        Nacionalidad
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="nacionalidad" id="nacionalidad" onchange="ocultar()">
                        <option value="Peruano(a)" {{ $director->nacionalidad == 'Peruano(a)' ? 'selected' : '' }} >Peruano(a)</option>
                        <option value="Extranjero(a)" {{ $director->nacionalidad == 'Extranjero(a)' ? 'selected' : '' }} >Extranjero(a)</option>
                    </select>
                    @error('nacionalidad')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div id="datos-geograficos" class="flex flex-wrap -mx-3 mb-6">
                <x-edit-ubicacion-select :entidad="$director" :departamento-name="'departamento'" :provincia-name="'provincia'" :distrito-name="'distrito'"/>
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
                        id="direccion" name="direccion" type="text" placeholder="Dirección" value="{{ old('direccion', $director->user->domicilio->direccion) }}">
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
                        value="{{ old('telefono_fijo', $director->user->domicilio->telefono_fijo) }}">
                    @error('telefono_fijo')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="_d">
                        Departamento
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="departamento_d" name="departamento_d" type="text" placeholder="Departamento"
                        value="{{ old('departamento_d', $director->user->domicilio->departamento) }}">
                    @error('departamento_d')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full md:w-1/3 mb-6 md:mb-0 px-3">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="provincia_d">
                        Provincia
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="provincia_d" name="provincia_d" type="text" placeholder="Provincia"
                        value="{{ old('provincia_d', $director->user->domicilio->provincia) }}">
                    @error('provincia_d')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full px-3 md:w-1/3 mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="distrito_d">
                        Distrito
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="distrito_d" name="distrito_d" type="text" placeholder="Distrito"
                        value="{{ old('distrito_d', $director->user->domicilio->distrito) }}">
                    @error('distrito_d')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div> --}}
            <div class="flex flex-wrap -mx-3 mb-6">
                <x-domicilio-ubicacion-select :entidad="$director" :departamento-name="'departamento_d'" :provincia-name="'provincia_d'" :distrito-name="'distrito_d'"/>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Actualizar
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function ocultar() {
        const div = document.getElementById('datos-geograficos');
        const select = document.getElementById('nacionalidad');
        console.log(select.value);
        if (select.value) {
            if (select.value == 'Extranjero(a)'){
                div.classList.add('smooth-hidden');
                div.classList.remove('smooth-visible');
                setTimeout(() => {
                    div.classList.add('hidden');
                }, 600);
            }
            else {
                div.classList.remove('hidden');
                setTimeout(() => {
                    div.classList.remove('smooth-hidden');
                    div.classList.add('smooth-visible');
                }, 400);
            }
        }
    }
</script>
<script>
    document.getElementById('dni').addEventListener('input', function (e) {
            var value = e.target.value;
            e.target.value = value.replace(/[^0-9]/g, '').slice(0, 8);
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
@endsection