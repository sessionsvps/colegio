<div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="{{ $departamentoName }}">
        Departamento
    </label>
    <select
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $departamentoName }}"
        id="{{ $departamentoName }}" name="{{ $departamentoName }}">
        <option value="" hidden>Seleccione Departamento</option>
        @foreach($departamentos as $departamento)
            <option value="{{ $departamento->id }}" {{ old($departamentoName) == $departamento->id ? 'selected' : '' }}>{{ $departamento->nombre }}</option>
        @endforeach
    </select>
    @error($departamentoName)
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
<div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="{{ $provinciaName }}">
        Provincia
    </label>
    <select
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $provinciaName }}"
        id="{{ $provinciaName }}" name="{{ $provinciaName }}">
        <option value="">Seleccione Provincia</option>
    </select>
    @error($provinciaName)
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
<div class="w-full md:w-1/3 px-3 mb-0">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="Name }}">
        Distrito
    </label>
    <select
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $distritoName }}"
        id="{{ $distritoName }}" name="{{ $distritoName }}">
        <option value="">Seleccione Distrito</option>
    </select>
    @error($distritoName)
    <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    console.log('Comienzo');
    $(document).ready(function(){
        // Cargar provincias al cambiar el departamento
        console.log('Entrando a función');
        console.log(document.getElementById('{{ $departamentoName }}'));
        $('#{{ $departamentoName }}').on('change', function(){
            var departamento_id = $(this).val();
            console.log(document.getElementById('departamento_d'));
            $.ajax({
                type: 'GET',
                url: '/get-provincias/' + departamento_id,
                dataType: 'json',
                success: function(response) {
                    let options = '<option value="" hidden>Seleccione Provincia</option>';
                    let oldProvincia = '{{ old($provinciaName) }}';
                    $.each(response, function(index, value){
                        let selected = (oldProvincia == value.id) ? 'selected' : '';
                        options += '<option value="'+value.id+'" '+selected+'>'+value.nombre+'</option>';
                    });
                    $('#{{ $provinciaName }}').html(options);
                    if (oldProvincia) {
                        $('#{{ $provinciaName }}').trigger('change');
                    }
                    $('#{{ $distritoName }}').html('<option value="">Seleccione Distrito</option>');
                }
            });
        });

        // Cargar distritos al cambiar la provincia
        $('#{{ $provinciaName }}').on('change', function(){
            var provincia_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '/get-distritos/' + provincia_id,
                dataType: 'json',
                success: function(response) {
                    let options = '<option value="" hidden>Seleccione Distrito</option>';
                    let oldDistrito = '{{ old($distritoName) }}'
                    $.each(response, function(index, value){
                        let selected = (oldDistrito == value.id) ? 'selected' : '';
                        options += '<option value="'+value.id+'" '+selected+'>'+value.nombre+'</option>';
                    });
                    $('#{{ $distritoName }}').html(options);
                }
            });
        });
        console.log('Función del DOM');
        
        ocultar();

        var departamentoSelect = document.getElementById('{{ $departamentoName }}');

        if (departamentoSelect.value) {
            console.log('hay un valor de departamento! es: '+departamentoSelect.value);
            $('#{{ $departamentoName }}').trigger('change');
            console.log('trigger añadido');
        }
    });
</script>