$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();
    var botones = new Botones();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-mesaDeAyuda', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-mesaDeAyuda tbody').on('click', 'tr', function () {
        var datos = $('#data-table-mesaDeAyuda').DataTable().row(this).data();
        if (datos !== undefined) {
            var servicio = datos[0];
            var operacion = datos[7];

            if (operacion === '1') {
                var html = '<div id="confirmacionServicioMesaDeAyuda">\n\
                                <div class="row">\n\
                                    <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                        <h3>¿Quieres atender el servicio?</h3>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnIniciarServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarIniciarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('Iniciar Servicio', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');

                $('#btnIniciarServicio').on('click', function () {
                    $(this).addClass('disabled');
                    $('#btnCancelarIniciarServicio').addClass('disabled');
                    var data = {servicio: servicio, operacion: '1'};
                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        evento.cerrarModal();
                        data = {servicio: servicio, operacion: '2'};
                        cargarFormularioSeguimiento(data, datos, '#panelSeguimientoMesaDeAyuda');
                        recargandoTablaGeneral(respuesta.informacion.serviciosAsignados);
                    });
                });

                //Envento para concluir con la cancelacion
                $('#btnCancelarIniciarServicio').on('click', function () {
                    evento.cerrarModal();
                });

            } else if (operacion === '2' || operacion === '12') {
                var data = {servicio: servicio, operacion: '2'};
                cargarFormularioSeguimiento(data, datos, '#panelSeguimientoMesaDeAyuda');
            }
        }
    });

    var cargarFormularioSeguimiento = function () {
        var data = arguments[0];
        var datosTabla = arguments[1];
        var panel = arguments[2];

        evento.enviarEvento('Seguimiento/Servicio_Datos', data, panel, function (respuesta) {
            var datosDelServicio = respuesta.datosServicio;
            var formulario = respuesta.formulario;
            var archivo = respuesta.archivo;
            var avanceServicio = respuesta.avanceServicio;
            var datosSD = respuesta.datosSD;

            if (datosDelServicio.tieneSeguimiento === '0') {
                var idSucursal = respuesta.idSucursal[0].IdSucursal;
                servicios.ServicioSinClasificar(
                        formulario,
                        '#listaServicio',
                        '#seccionSeguimientoServicio',
                        datosTabla[0],
                        datosDelServicio,
                        'Seguimiento',
                        archivo,
                        '#panelSeguimientoMesaAyuda',
                        datosTabla[1],
                        avanceServicio,
                        idSucursal,
                        datosSD
                        );
            } else {
                $('#listaServicio').addClass('hidden');
                $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(formulario);
                botones.iniciarBotonesGenerales(datosTabla[0], datosDelServicio);
                switch (datosDelServicio.IdTipoServicio) {
                    case '10':
                        seguimientoUber(datosTabla, respuesta.datosSD);
                        break;
                }
            }
        });
    };

    var recargandoTablaGeneral = function (listaServiciosAsignados) {
        tabla.limpiarTabla('#data-table-mesaDeAyuda');
        $.each(listaServiciosAsignados, function (key, item) {
            tabla.agregarFila('#data-table-mesaDeAyuda', [item.Id, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
        });
    };

    var seguimientoUber = function () {
        var datosTabla = arguments[0];
        var datosSD = arguments[1];
        var servicio = datosTabla[0];
        var ticket = datosTabla[1];

        $('#fechaServicio').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            widgetPositioning: {
                horizontal: 'right',
                vertical: 'bottom'
            }
        });

        $("#btnGuardarGenerales").off("click");
        $("#btnGuardarGenerales").on("click", function () {
            if (evento.validarFormulario('#formGenerales')) {
                var data = {
                    servicio: servicio,
                    ticket: $("#txtTicket").val(),
                    personas: $("#txtPersonas").val(),
                    fecha: $("#txtFechaServicio").val(),
                    origen: $("#txtDireccionOrigen").val(),
                    destino: $("#txtDireccionDestino").val(),
                    proyecto: $("#txtMotivo").val(),
                    operacion: '5'
                };

                evento.enviarEvento('Seguimiento/Guardar_Generales_Uber', data, '#seccion-datos-seguimiento', function (respuesta) {
                    $('#seccionSeguimientoServicio').addClass('hidden').empty();
                    $('#listaServicio').removeClass('hidden');
                    recargandoTablaGeneral(respuesta.informacion.serviciosAsignados);
                });
            }
        });

        //evento para crear nueva solicitud
        servicios.initBotonNuevaSolicitud(ticket, '#seccion-datos-seguimiento');
        servicios.eventosFolio(datosTabla[2], '#seccion-datos-seguimiento', datosTabla[0]);
    }
});