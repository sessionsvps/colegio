@extends('layouts.main')

@section('contenido')

<table class="min-w-full bg-white">
    <thead>
        <tr>
            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                Curso
            </th>
            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm leading-4 text-gray-600 uppercase">
                Competencias
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($cursos as $curso)
            @php
                $competencias = $curso->getCompetencias();
                $firstCompetencia = $competencias->shift();
            @endphp
            @if($firstCompetencia != null && $competencias->count()>=0)
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200" rowspan="{{ $competencias->count() + 1 }}">
                        {{$curso->descripcion}}
                    </td>
                    <td class="py-2 px-4 border-b border-gray-200">
                        {{$firstCompetencia->descripcion}}
                    </td>
                </tr>
                @foreach($competencias as $competencia)
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">
                            {{$competencia->descripcion}}
                        </td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
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