$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();
    var botones = new Botones();
    var select = new Select();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-servicios-sin-firma', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('#data-table-servicios-sin-firma tbody').on('click', 'tr', function () {
        var datosTabla = $('#data-table-servicios-sin-firma').DataTable().row(this).data();
        modalCampoFirma(datosTabla[1], datosTabla[0], '.errorFormularioSolucionCambioEquipo', 'respuestaAnterior', true, '4');
    });

    var modalCampoFirma = function () {
        var ticket = arguments[0];
        var servicio = arguments[1];

        var data = {servicio: servicio};

        evento.enviarEvento('Evento/DatosServicioSinFirma', data, '#panelServicioSinFirma', function (respuesta) {

            var html = '<div class="row" m-t-10">\n\
                        <div id="col-md-12 text-center">\n\
                            <div id="campoLapizTecnico"></div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row m-t-20">\n\
                        <div class="col-md-12 text-center">\n\
                            <br>\n\
                            <label>Firma del técnico</label><br>\n\
                        </div>\n\
                    </div>\n\
                    <br>';

            html += '<div class="row" m-t-10">\n\
                        <div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2">\n\
                            <div class="form-group">\n\
                                <label for="selectTI">Encargado TI *</label>\n\
                                <select id="selectTI" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                   <option value="">Seleccionar</option>\n\
                                </select>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
            evento.mostrarModal('Firma', servicios.modalCampoFirmaExtra(html, 'Firma'));
            $.each(respuesta.encargadosTI, function (key, valor) {
                $("#selectTI").append('<option value=' + valor.Id + '>' + valor.Nombre + '</option>');
            });

            select.crearSelect('#selectTI');

            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalConfirmar').off('click');
            servicios.validarTecnicoPoliza();
            servicios.validarCamposFirma(ticket, servicio, true, true, '4', );

        });

    }

});