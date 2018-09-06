$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var file = new Upload();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de resumen minuta
    tabla.generaTablaPersonal('#data-table-facturas-tesoreria', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que permite actualizar el personal
    $('#data-table-facturas-tesoreria tbody').on('click', 'tr', function () {
        var datos = $('#data-table-facturas-tesoreria').DataTable().row(this).data();
        var ticket = datos[0];
        var estatus = datos[6];

        evento.enviarEvento('Facturacion/validarPuesto', [], '#panelFacturacionTesoreria', function (respuesta) {
            var perfil = respuesta;
            if (perfil === '36') {
                if (estatus === 'FALTA PAGO') {
                    referenciaPago(ticket);
                }
            } else {
                var data = {ticket: ticket};
                evento.enviarEvento('Facturacion/mostrarFormularioDocumentacionFacturacion', data, '#panelFacturacionTesoreria', function (respuesta) {
                    var pdf = respuesta.datos.DocumentoPDF;
                    var xml = respuesta.datos.DocumentoXML;

                    $('#listaFacturas').addClass('hidden');
                    $('#seccionProcesoFacturacion').removeClass('hidden').empty().append(respuesta.formulario);
                    cargarElementos(perfil, ticket, pdf, xml, estatus);

                    $('#btnValidarFacturacionTesoreria').on('click', function () {
                        validarDocumentacion(ticket);
                    });

                    $('#btnRegresarFacturacionTesoreria').on('click', function () {
                        location.reload();
                    });
                });
            }
        });
    });

    var cargarElementos = function () {
        var file1 = new Upload();
        var file2 = new Upload();
        var perfil = arguments[0];
        var ticket = arguments[1];
        var pdf = arguments[2];
        var xml = arguments[3];
        var estatus = arguments[4];

        var editarPDF = false;
        var editarXML = false;
        if (perfil === '46' || perfil === '39') {
            if (estatus === 'EN VALIDACIÓN' || estatus === 'RECHAZADO') {
                $('#btnValidarFacturacionTesoreria').removeClass('hidden');
            }
        }

        if (estatus !== 'FALTA DOCUMENTACIÓN') {
            if (estatus !== 'EN VALIDACIÓN') {
                editarPDF = true;
                editarXML = true;
            }
        }

        if (perfil !== '83') {
            editarPDF = true;
            editarXML = true;
        }

        file1.crearUpload('#inputPDFFacturacion',
                'Facturacion/guardarDocumentosFacturaAsociados',
                ['pdf'],
                editarPDF,
                pdf,
                null,
                null,
                true,
                '1',
                true,
                true,
                {ticket: ticket, input: 'PDFFacturacion'}
        );
        file2.crearUpload('#inputXMLFacturacion',
                'Facturacion/guardarDocumentosFacturaAsociados',
                ['xml'],
                editarXML,
                xml,
                null,
                null,
                true,
                '1',
                true,
                true,
                {ticket: ticket, input: 'XMLFacturacion'}
        );
    };

    var validarDocumentacion = function () {
        var ticket = arguments[0];
        var html = '<div id="formularioValidarFacturacionTesoreria">\n\
                        <div class="panel-body">\n\
                            <div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnValidarFactura" type="button" class="btn btn-md btn-success"><i class="fa fa-check"></i> Validar</button>\n\
                                    <button id="btnRechazarFactura" type="button" class="btn btn-md btn-danger"><i class="fa fa-times"></i> Rechazar</button>\n\
                                    <button id="btnRegresarValidarFactura" type="button" class="btn btn-md btn-primary"><i class="fa fa-reply"></i> Regresar</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';

        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('<h3>Validación</h3>', html);

        $('#btnValidarFactura').on('click', function () {
            $('#btnValidarFactura').attr('disabled', 'disabled');
            $('#btnRechazarFactura').attr('disabled', 'disabled');
            $('#btnRegresarValidarFactura').attr('disabled', 'disabled');
            var data = {ticket: ticket};
            evento.enviarEvento('Facturacion/colocarFechaValidacion', data, '#formularioValidarFacturacionTesoreria', function (respuesta) {
                if (respuesta instanceof Array) {
                    location.reload();
                }
            });
        });

        $('#btnRegresarValidarFactura').on('click', function () {
            evento.cerrarModal();
        });

        $('#btnRechazarFactura').on('click', function () {
            var formularioRechazoFactura = '';
            formularioRechazoFactura += '<div id="formularioRechazarFacturacionTesoreria">\n\
                                            <div class="panel-body">\n\
                                                <form id="formRechazarFactura" class="margin-bottom-0 " data-parsley-validate="true" enctype="multipart/form-data">\n\
                                                    <div class="row">\n\
                                                        <div  class="col-md-12 ">\n\
                                                            <label>Causa del Rechazo </label>\n\
                                                            <textarea id="textareaRechazarFactura" class="form-control" name="descricpcionRechazo" placeholder="Ingresa la causa del Rechazo... " rows="3" data-parsley-required="true" ></textarea>\n\
                                                        </div>\n\
                                                    </div>\n\
                                                    <div class="row m-t-20">\n\
                                                    <div  class="col-md-12 ">\n\
                                                        <div class="text-danger">\n\
                                                            <div class="mensajeErrorRechazar"></div>\n\
                                                        </div>\n\
                                                    </div>';
            formularioRechazoFactura += '           <div class="row m-t-20">\n\
                                                        <div class="col-md-12 text-center">\n\
                                                            <button id="btnGuardarRechazoFactura" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Guardar</button>\n\
                                                            <button id="btnCancelarRechazoFactura" type="button" class="btn btn-sm btn-default"><i class="fa fa-reply"></i> Cancelar</button>\n\
                                                        </div>\n\
                                                    </div>\n\
                                                </form>\n\
                                            </div>\n\
                                        </div>';
            evento.mostrarModal('<h3>Rechazar</h3>', formularioRechazoFactura);
            $('#btnModalConfirmar').empty().append('Aceptar').addClass('btn-danger');
            $('#btnModalAbortar').empty().append('Cancelar');

            $('#btnGuardarRechazoFactura').on('click', function () {
                var descripcionRechazo = $('#textareaRechazarFactura').val();
                if (descripcionRechazo !== '') {
                    var data = {ticket: ticket, descripcionRechazo: descripcionRechazo};
                    evento.enviarEvento('Facturacion/rechazarFacturaAsociado', data, '#formularioRechazarFacturacionTesoreria', function (respuesta) {
                        if (respuesta instanceof Array) {
                            evento.cerrarModal();
                            location.reload();
                        }
                    });
                } else {
                    evento.mostrarMensaje('.mensajeErrorRechazar', false, 'Debe llenar el campo de Causa del Rechazo', 3000);
                }
            });
            $('#btnCancelarRechazoFactura').on('click', function () {
                evento.cerrarModal();
            });
        });
    };

    var referenciaPago = function () {
        var ticket = arguments[0];
        var formularioReferciaPagoFactura = '';

        formularioReferciaPagoFactura += '<div id="formularioPagoFacturacionTesoreria">\n\
                                            <div class="panel-body">\n\
                                                <form id="formReferenciaPagoFactura" class="margin-bottom-0 " data-parsley-validate="true" enctype="multipart/form-data">\n\
                                                    <div class="row">\n\
                                                        <div  class="col-md-6 ">\n\
                                                            <label>Refencia * </label>\n\
                                                            <input type="text" class="form-control" id="inputReferenciaPago" placeholder="Referencia de pago" style="width: 100%" data-parsley-required="true"/>\n\
                                                        </div>\n\
                                                    </div>';

        formularioReferciaPagoFactura += '          <div class="row m-t-20">\n\
                                                        <div class="col-md-12">\n\
                                                            <div class="form-group">\n\
                                                                <label>Evidencia *</label>\n\
                                                                <input id="inputEvidenciaFacturacion" name="evidenciaFacturacion[]" type="file" multiple>\n\
                                                            </div>\n\
                                                        </div>\n\
                                                    </div>\n\
                                                    <div class="row m-t-20">\n\
                                                        <div  class="col-md-12 ">\n\
                                                            <div class="text-danger">\n\
                                                                <div class="mensajeErrorRechazar"></div>\n\
                                                            </div>\n\
                                                        </div>\n\
                                                    </div>';
        formularioReferciaPagoFactura += '          <div class="row m-t-20">\n\
                                                        <div class="col-md-12 text-center">\n\
                                                            <button id="btnGuardarReferenciaPagoFactura" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Guardar</button>\n\
                                                            <button id="btnRegresarReferenciaPagoFactura" type="button" class="btn btn-sm btn-default"><i class="fa fa-reply"></i> Cancelar</button>\n\
                                                        </div>\n\
                                                    </div>\n\
                                                </form>\n\
                                            </div>\n\
                                        </div>';
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        evento.mostrarModal('<h3>Referencia de Pago</h3>', formularioReferciaPagoFactura);
        file.crearUpload('#inputEvidenciaFacturacion', 'Facturacion/colocarReferenciaPago', ['doc', 'docx', 'pdf', 'jpg', 'jpeg', 'png']);

        $('#btnGuardarReferenciaPagoFactura').on('click', function () {
            var referenciaPago = $('#inputReferenciaPago').val();
            var evidenciaPago = $('#inputEvidenciaFacturacion').val();
            if (referenciaPago !== '') {
                if (evidenciaPago !== '') {
                    var data = {ticket: ticket, referenciaPago: referenciaPago};
                    file.enviarArchivos('#inputEvidenciaFacturacion', 'Facturacion/colocarReferenciaPago', '#formularioPagoFacturacionTesoreria', data, function (respuesta) {
                        if (respuesta instanceof Array) {
                            evento.cerrarModal();
                            recargandoTablaFacturasTesoreria(respuesta, 'Referencia de Pago agregado Correctamente', '.errorListaFacturacionTesoreria')
                        }
                    });
                } else {
                    evento.mostrarMensaje('.mensajeErrorRechazar', false, 'Debe llenar el campo de Evidencia', 3000);
                }
            } else {
                evento.mostrarMensaje('.mensajeErrorRechazar', false, 'Debe llenar el campo de Referencia', 3000);
            }
        });
        $('#btnRegresarReferenciaPagoFactura').on('click', function () {
            evento.cerrarModal();
        });
    };

    var recargandoTablaFacturasTesoreria = function () {
        var respuesta = arguments[0];
        var mensaje = arguments[1];
        var divError = arguments[2];

        tabla.limpiarTabla('#data-table-facturas-tesoreria');
        $.each(respuesta, function (key, valor) {
            tabla.agregarFila('#data-table-facturas-tesoreria', [valor.Ticket, valor.Ingeniero, valor.FechaDocumentacion, valor.FechaValidacionSup, valor.FechaValidacionCoord, valor.FechaPago, valor.Estatus], true);
        });
        evento.mostrarMensaje(divError, true, mensaje, 3000);
    };
});