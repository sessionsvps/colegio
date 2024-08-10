document.getElementById("boton_verificar2").addEventListener("click", function() {
    const dni = document.getElementById("dni_ap").value;
    
    // Primero, buscar en la base de datos
    fetch(`/apoderados/buscar?dni=${dni}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Si se encuentra en la base de datos, llenar los campos del formulario
                const apoderado = data.apoderado;
                const photoUrl = data.photo_url;
                document.getElementById("primer_nombre_ap").value = apoderado.primer_nombre;
                document.getElementById("otros_nombres_ap").value = apoderado.otros_nombres || '';
                document.getElementById("apellido_paterno_ap").value = apoderado.apellido_paterno;
                document.getElementById("apellido_materno_ap").value = apoderado.apellido_materno;
                document.getElementById("fecha_nacimiento_ap").value = apoderado.fecha_nacimiento.split(' ')[0];
                document.getElementById("sexo_texto_ap").value = apoderado.sexo ? 'Masculino' : 'Femenino';
                document.getElementById("sexo_ap").value = apoderado.sexo ? '1' : '0';
                document.getElementById("email_ap").value = apoderado.email;
                document.getElementById("telefono_celular_ap").value = apoderado.telefono_celular || '';
                document.getElementById("email_ap").setAttribute("readonly", true);
                document.getElementById("telefono_celular_ap").setAttribute("readonly", true);
                // Mostrar la foto
                if (photoUrl) {
                    document.getElementById("photoPreview2").src = photoUrl;
                    document.getElementById("lblSeleccionar").style.display = 'none';
                }
                mostrarMensaje_ap('green', 'El Apoderado ya se encuentra registrado');
            } else {
                // Si no se encuentra en la base de datos, proceder a consultar la API
                document.getElementById("email_ap").removeAttribute("readonly");
                document.getElementById("telefono_celular_ap").removeAttribute("readonly");
                limpiarCampos_ap();
                traerDatos_ap();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensaje_ap('red', 'Hubo un problema al buscar los datos en la base de datos.');
        });
});

function mostrarMensaje_ap(tipo, mensaje) {
    const contenedorMensaje = document.getElementById('mensaje_verificacion2');
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

function traerDatos_ap() {
    let dni = document.getElementById("dni_ap").value;
    fetch(`https://api.perudevs.com/api/v1/dni/complete?document=${dni}&key=cGVydWRldnMucHJvZHVjdGlvbi5maXRjb2RlcnMuNjY5OWYxMGJkNDFiOTQxMTE0OGI1OTAw`)
    .then((response) => response.json())
    .then((datos) => {
        if (datos.estado) {
            const resultado = datos.resultado;
            document.getElementById("primer_nombre_ap").value = resultado.nombres.split(' ')[0];
            document.getElementById("otros_nombres_ap").value = resultado.nombres.split(' ').slice(1).join(' ');
            document.getElementById("apellido_paterno_ap").value = resultado.apellido_paterno;
            document.getElementById("apellido_materno_ap").value = resultado.apellido_materno;
            const genero = resultado.genero === 'M' ? 'Masculino' : 'Femenino';
            document.getElementById("sexo_texto_ap").value = genero;
            document.getElementById("sexo_ap").value = resultado.genero === 'M' ? '1' : '0';
            document.getElementById("fecha_nacimiento_ap").value = resultado.fecha_nacimiento.split('/').reverse().join('-'); // Convertir a formato YYYY-MM-DD
            mostrarMensaje_ap('green', 'Verificación hecha correctamente');
        } else {
            limpiarCampos_ap();
            mostrarMensaje_ap('red', 'No se encontró a una persona con ese DNI');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        limpiarCampos_ap();
        mostrarMensaje_ap('red', 'Hubo un problema al buscar los datos. Inténtalo de nuevo más tarde.');
    });
}

function limpiarCampos_ap() {
    document.getElementById("primer_nombre_ap").value = '';
    document.getElementById("otros_nombres_ap").value = '';
    document.getElementById("apellido_paterno_ap").value = '';
    document.getElementById("apellido_materno_ap").value = '';
    document.getElementById("sexo_texto_ap").value = '';
    document.getElementById("sexo_ap").value = '';
    document.getElementById("fecha_nacimiento_ap").value = '';
    document.getElementById("email_ap").value = '';
    document.getElementById("telefono_celular_ap").value = '';
    document.getElementById("photoPreview2").removeAttribute("src");
    document.getElementById("lblSeleccionar").style.display = 'block';
}
