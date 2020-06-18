//Constructor del la clase Nota
function Nota() {

}

Nota.prototype.initButtons = function () {
    var _this = this;
    var servicio = arguments[0].servicio;
    var nombreControlador = arguments[1];
    $("#btnAgregarNota").off("click");
    $("#btnAgregarNota").on("click", function () {
        $(this).addClass('hidden');
        $("#divFormAgregarNota").removeClass('hidden');
    });

    $("#btnCancelarAgregarNota").off("click");
    $("#btnCancelarAgregarNota").on("click", function () {
        $("#divFormAgregarNota").addClass('hidden');
        $("#btnAgregarNota").removeClass('hidden');
        Nota.prototype.fileUpload.limpiar('#archivosAgregarNotas');
        Nota.prototype.evento.limpiarFormulario("#formAgregarNotas");
    });

    $('#btnConfirmarAgregarNota').off('click');
    $('#btnConfirmarAgregarNota').on('click', function () {
        var observaciones = $('#txtAgregarNotas').val();
        var evidencias = $('#archivosAgregarNotas').val();
        var data = {servicio: servicio, observaciones: observaciones};
        if (observaciones !== '' || evidencias !== '') {
            Nota.prototype.fileUpload.enviarArchivos('#archivosAgregarNotas', nombreControlador + '/Guardar_Nota_Servicio', '#divDetallesServicio', data, function (respuesta) {
                if (respuesta) {
                    Nota.prototype.fileUpload.limpiar('#archivosAgregarNotas');
                    Nota.prototype.evento.limpiarFormulario("#formAgregarNotas");
                    Nota.prototype.evento.mostrarMensaje('#errorAgregarCorrectoNota', true, 'Su nota o archivos se agregaron correctamente.', 5000);
                    Nota.prototype.evento.enviarEvento(nombreControlador + '/ActualizaNotas', data, '#divDetallesServicio', function (respuesta) {
                        $("#divFormAgregarNota").addClass('hidden');
                        $("#btnAgregarNota").removeClass('hidden');
                        $("#divNotasServicio").slimScroll({height: '400px'});
                        $("#ulListaNotas").empty().append(respuesta.html);
                    });
                } else {
                    Nota.prototype.evento.mostrarMensaje('#errorAgregarNotaServicio', false, 'No se pudo agregar la nota. Intente de nuevo por favor.', 3000);
                }
            });
        } else {
            Nota.prototype.evento.mostrarMensaje('#errorAgregarNotaServicio', false, 'Debe capturar una nota o agregar al menos un archivo para guardarlo.', 3000);
        }
    });

    Nota.prototype.fileUpload.crearUpload(
            '#archivosAgregarNotas',
            nombreControlador + '/Guardar_Nota_Servicio'
            );
}

Nota.prototype.evento = new Base();
Nota.prototype.fileUpload = new Upload();