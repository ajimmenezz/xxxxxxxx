$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var file = new Upload();
    var select = new Select();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    select.crearSelect('select');

    //Creando input de evidencias
    file.crearUpload('#inputEvidenciasArchivo', 'Archivos/Nuevo_Archivo', ['doc', 'docx', 'xls', 'xlsx', 'pdf'], null, null, null, null, true, 1);
    //Generar nuevo archivo
    $('#btnNuevoArchivo').on('click', function () {
        if (evento.validarFormulario('#formNuevoArchivo')) {
            var tipo = $('#selectTiposArchivos').val();
            var nombre = $('#inputNombreArchivo').val();
            var descripcion = $('#textareaDescripcionArchivo').val();
            var data = {tipo: tipo, nombre: nombre, descripcion: descripcion};
            file.enviarArchivos('#inputEvidenciasArchivo', 'Archivos/Nuevo_Archivo', '#panelNuevoArchivo', data, function (respuesta) {
                if (respuesta == 'existe') {
                    file.limpiar('#inputEvidenciasArchivo');
                    evento.mostrarMensaje('.errorNuevoArchivo', false, 'Ya existe el Nombre del Archivo, por lo que ya no puedes repetirlo.', 3000);
                } else if (respuesta == 'falta') {
                    evento.mostrarMensaje('.errorNuevoArchivo', false, 'Agrege un Archivo', 3000);
                } else {
                    evento.mostrarMensaje('.errorNuevoArchivo', true, 'Archivo insertados correctamente', 3000);
                    evento.limpiarFormulario('#formNuevoArchivo');
                }
            });
        }
    });

});