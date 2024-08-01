@extends('layouts.main')

@section('contenido')

    <a href="{{ route('estudiantes.filtrar-por-aula', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$estudiante->id_nivel, 'grado' => $estudiante->id_grado, 'seccion' => $estudiante->id_seccion ]) }}"
        class="bg-red-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
        Volver
    </a>
    <h2 class="text-xl lg:text-2xl font-bold my-10">Registro de Notas</h2>
    
    <form
        action="{{ route('boleta_notas.update', ['codigo_estudiante' => $estudiante->codigo_estudiante,'codigo_curso' => $curso->codigo_curso , 'año_escolar' => $estudiante->año_escolar]) }}"
        method="POST">
        @csrf
        @method('PUT')

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
    
        <div class="mt-6 bg-gray-50 shadow-md rounded-lg p-6">
            <p class="text-lg font-bold text-indigo-900">{{$curso->descripcion}} ({{$curso->codigo_curso}})</p>
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
                        @foreach ($bimestres as $bimestre)
                        @php
                        $nota=$notas->where('id_bimestre',$bimestre->id)->where('codigo_curso',$curso->codigo_curso)->where('orden',$competencia->orden)->first();
                        @endphp
                        <select {{ $bimestre->esActivo ? '' : 'disabled' }} name="notas[{{ $competencia->orden }}][{{ $bimestre->id }}]"
                            class="lg:flex items-center justify-center text-center text-gray-700 border border-gray-300 rounded-lg p-2 shadow
                                {{ $nota ? ($nota->nivel_logro == 'A' || $nota->nivel_logro == 'B' || $nota->nivel_logro == 'AD' ? 'bg-green-100' : ($nota->nivel_logro == 'C' ? 'bg-red-100' : 'bg-white')) : 'bg-white' }}"
                            onchange="updateSelectColor(this)">
                            <option value="" {{ $nota ? '' : 'selected' }}>--</option>
                            <option value="AD" {{ $nota && $nota->nivel_logro == 'AD' ? 'selected' : '' }}>AD</option>
                            <option value="A" {{ $nota && $nota->nivel_logro == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ $nota && $nota->nivel_logro == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ $nota && $nota->nivel_logro == 'C' ? 'selected' : '' }}>C</option>
                        </select>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    
        <div class="flex mt-10 items-center justify-center">
            <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                Actualizar
            </button>
        </div>
    </form>

@endsection

@section('scripts')
<script>
    function updateSelectColor(selectElement) {
        const value = selectElement.value;
        selectElement.classList.remove('bg-green-100', 'bg-red-100', 'bg-white');
        
        if (value === 'A' || value === 'B' || value === 'AD') {
            selectElement.classList.add('bg-green-100');
        } else if (value === 'C') {
            selectElement.classList.add('bg-red-100');
        } else {
            selectElement.classList.add('bg-white');
        }
    }

    // Inicializar colores en carga de página
    document.querySelectorAll('select').forEach(select => {
        updateSelectColor(select);
    });
</script>
@endsection