document.getElementById("boton_verificar").addEventListener("click", traerDatos);

function mostrarMensaje(tipo, mensaje) {
    const contenedorMensaje = document.getElementById('mensaje_verificacion');
    contenedorMensaje.innerHTML = `
        <div class="flex p-4 mb-4 text-sm text-${tipo}-800 rounded-lg bg-${tipo}-50 dark:bg-gray-800 dark:text-${tipo}-400" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">${tipo === 'red' ? 'Error' : 'Éxito'}</span>
            <div>
                <span class="font-medium">${mensaje}</span>
            </div>
        </div>
    `;
}

function traerDatos() {
    let dni = document.getElementById("dni").value;
    fetch(`https://api.perudevs.com/api/v1/dni/complete?document=${dni}&key=cGVydWRldnMucHJvZHVjdGlvbi5maXRjb2RlcnMuNjY5OWYxMGJkNDFiOTQxMTE0OGI1OTAw`)
    .then((response) => response.json())
    .then((datos) => {
        if (datos.estado) {
            const resultado = datos.resultado;
            document.getElementById("primer_nombre").value = resultado.nombres.split(' ')[0];
            document.getElementById("otros_nombres").value = resultado.nombres.split(' ').slice(1).join(' ');
            document.getElementById("apellido_paterno").value = resultado.apellido_paterno;
            document.getElementById("apellido_materno").value = resultado.apellido_materno;
            const genero = resultado.genero === 'M' ? 'Masculino' : 'Femenino';
            document.getElementById("sexo_texto").value = genero;
            document.getElementById("sexo").value = resultado.genero === 'M' ? '1' : '0';
            document.getElementById("fecha_nacimiento").value = resultado.fecha_nacimiento.split('/').reverse().join('-'); // Convertir a formato YYYY-MM-DD
            mostrarMensaje('green', 'Verificación hecha correctamente');
        } else {
            limpiarCampos();
            mostrarMensaje('red', 'No se encontró a una persona con ese DNI');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        limpiarCampos();
        mostrarMensaje('red', 'Hubo un problema al buscar los datos. Inténtalo de nuevo más tarde.');
    });
}

function limpiarCampos() {
    document.getElementById("primer_nombre").value = '';
    document.getElementById("otros_nombres").value = '';
    document.getElementById("apellido_paterno").value = '';
    document.getElementById("apellido_materno").value = '';
    document.getElementById("sexo_texto").value = '';
    document.getElementById("sexo").value = '';
    document.getElementById("fecha_nacimiento").value = '';
}

