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
            <h2 class="text-xl md:text-2xl lg:text-3xl font-bold">Lista de Estudiantes</h2>
            @can('estudiantes.control')
            <a href="{{ route('estudiantes.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Añadir
            </a>
            @endcan
        </div>
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
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
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
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
                        <th scope="col" class="px-6 py-3">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($estudiantes as $estudiante)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $estudiante->codigo_estudiante }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $estudiante->primer_nombre }} {{ $estudiante->otros_nombres }} {{
                            $estudiante->apellido_paterno }} {{
                            $estudiante->apellido_materno }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $estudiante->dni }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $estudiante->email }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-center">
                                <a href="{{ route('estudiantes.edit', $estudiante->codigo_estudiante) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                                <button type="button" onclick="confirmDelete('{{ $estudiante->id }}')"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline ml-4">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center">
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
    document.addEventListener('DOMContentLoaded', function () {
        var reporteSelect = document.getElementById('reporte');
        var generateReportButton = document.getElementById('reportButton');
        // var optionsAdded = false;

        // reporteSelect.addEventListener('focus', function () {
        //     if (!optionsAdded) {
        //         var excelOption = document.createElement('option');
        //         excelOption.value = '1';
        //         excelOption.text = 'EXCEL';

        //         var pdfOption = document.createElement('option');
        //         pdfOption.value = '0';
        //         pdfOption.text = 'PDF';

        //         reporteSelect.add(excelOption);
        //         reporteSelect.add(pdfOption);

        //         optionsAdded = true;
        //     }
        // });

        reporteSelect.addEventListener('change', function () {
            var selectedValue = this.value;
            if (selectedValue == '1') { // EXCEL
                generateReportButton.href = "{{ route('export') }}";
            } else if (selectedValue == '0') { // PDF
                generateReportButton.href = "{{ route('exportPdf') }}";
            }
        });
    });
</script>
@endsection
