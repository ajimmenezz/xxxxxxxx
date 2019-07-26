$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var file = new Upload();
    var file1 = new Upload();
    var servicios = new Servicio();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de resumen minuta
    tabla.generaTablaPersonal('#data-table-facturas-tesoreria', null, null, true, true, [[0, 'asc']]);
    tabla.generaTablaPersonal('#data-table-facturas-poliza', null, null, true, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('#btnSubirFactura').off('click');
    $('#btnSubirFactura').on('click', function () {
        var data = {};
        evento.enviarEvento('Facturacion/mostrarFormularioSubirFactura', data, '#panelFacturacionTesoreria', function (respuesta) {
            cargarSeccionFacturacion(respuesta);
            cargarElementosFormularioSubirFactura();
            eventosFormularioSubirFactura();
            botonRegresarFacturacion();
        });
    });

    $('#btnCombinarFacturas').off('click');
    $('#btnCombinarFacturas').on('click', function () {
        var data = {};
        evento.enviarEvento('Facturacion/CombinarFacturasActivas', data, '#panelFacturacionTesoreria', function (respuesta) {
            console.log(respuesta);
        });
    });

    $('#data-table-facturas-poliza tbody').on('click', 'tr', function () {
        var datosTabla = $('#data-table-facturas-poliza').DataTable().row(this).data();

        if (datosTabla !== undefined) {
            var estatusVuelta = datosTabla[8];
            var idVuelta = datosTabla[0];
            if (estatusVuelta === "SIN AUTORIZACIÓN") {
                var data = {datosTabla: datosTabla};
                evento.enviarEvento('Facturacion/mostrarFormularioValidarVuelta', data, '#panelFacturacionTesoreria', function (respuesta) {
                    if (respuesta.datos.arregloUsuario.Permisos.indexOf("276") !== -1 || respuesta.datos.arregloUsuario.PermisosAdicionales.indexOf("276") !== -1) {
                        cargarSeccionFacturacion(respuesta);
                        eventosFormularioValidarVuelta(respuesta);
                        botonRegresarFacturacion();
                    }
                });
            } else if (estatusVuelta === 'PAGADO') {
                var data = {idVuelta: idVuelta};
                evento.enviarEvento('Facturacion/mostrarEvidenciaPagoFactura', data, '#panelFacturacionTesoreria', function (respuesta) {
                    window.open(respuesta, '_blank');
                });
            } else if (estatusVuelta === 'RECHAZADO') {
                observacionesRechazo(idVuelta);
            } else if (estatusVuelta === 'AUTORIZADO') {
                evento.enviarEvento('Facturacion/verificarReabrirVuelta', {}, '#panelFacturacionTesoreria', function (respuesta) {
                    if (respuesta) {
                        var modalMensaje = evento.mensajeValidar('¿Realmente quiere Reabrir la Vuelta?');
                        evento.mostrarModal('Advertencia', modalMensaje);
                        var idVuelta = datosTabla[0];
                        var data = {idVuelta: idVuelta, estatus: '8'};

                        $('#btnAceptarConfirmacion').on('click', function () {
                            evento.enviarEvento('Facturacion/reabrirVuelta', data, '#modal-dialogo', function (respuesta) {
                                if (respuesta) {
                                    evento.mensajeConfirmacion('Se reabrio la vuelta con exito.', 'Correcto');
                                }
                            });
                        });

                        $('#btnCancelarConfirmacion').on('click', function () {
                            evento.cerrarModal();
                        });
                    }
                });
            }
        }
    });

    $('#data-table-facturas-tesoreria tbody').on('click', 'tr', function () {
        var datosTablaTesoreria = $('#data-table-facturas-tesoreria').DataTable().row(this).data();

        if (datosTablaTesoreria !== undefined) {
            var data = {id: datosTablaTesoreria[0]};
            evento.enviarEvento('Facturacion/mostrarFormularioPago', data, '#panelFacturacionTesoreria', function (respuesta) {
                cargarSeccionFacturacion(respuesta);
                cargarElementosFormularioPago();
                eventosFormularioSubirPago(respuesta);
                botonRegresarFacturacion();
            });
        }
    });

    $('#btnSemanaAnterior').off('click');
    $('#btnSemanaAnterior').on('click', function () {
        $('#btnSemanaActual').removeClass('active');
        if ($('#data-table-facturas-tesoreria').DataTable().data()[0] !== undefined) {
            var fechaSemana = $('#data-table-facturas-tesoreria').DataTable().data()[0][3];
            var data = {fechaSemana: fechaSemana};
            evento.enviarEvento('Facturacion/MostrarFacturasSemanaAnterior', data, '#panelFacturacionTesoreria', function (respuesta) {
                recargandoTablaFacturasPago(respuesta.consulta);
                var textoFecha = 'Las facturas mostradas son del día <strong>' + respuesta.fechaSegunda + '</strong> al día <strong>' + respuesta.fechaPrimera + '</strong>.'
                $('#divFecha').empty().append(textoFecha);
            });
        } else {
            evento.enviarEvento('Facturacion/MostrarFacturasSemana', {}, '#panelFacturacionTesoreria', function (respuesta) {
                recargandoTablaFacturasPago(respuesta);
            });
        }
    });

    $('#btnSemanaSeguiente').off('click');
    $('#btnSemanaSeguiente').on('click', function () {
        $('#btnSemanaActual').removeClass('active');
        if ($('#data-table-facturas-tesoreria').DataTable().data()[0] !== undefined) {
            var fechaSemana = $('#data-table-facturas-tesoreria').DataTable().data()[0][3];
            var data = {fechaSemana: fechaSemana};
            evento.enviarEvento('Facturacion/MostrarFacturasSemanaSiguiente', data, '#panelFacturacionTesoreria', function (respuesta) {
                recargandoTablaFacturasPago(respuesta.consulta);
                var textoFecha = 'Las facturas mostradas son del día <strong>' + respuesta.fechaSegunda + '</strong> al día <strong>' + respuesta.fechaPrimera + '</strong>.'
                $('#divFecha').empty().append(textoFecha);
            });
        }
    });

    $('#btnSemanaActual').off('click');
    $('#btnSemanaActual').on('click', function () {
        evento.enviarEvento('Facturacion/MostrarFacturasSemana', {}, '#panelFacturacionTesoreria', function (respuesta) {
            recargandoTablaFacturasPago(respuesta);
        });
    });

    var cargarSeccionFacturacion = function () {
        var respuesta = arguments[0];
        $('#listaFacturas').addClass('hidden');
        $('#seccionProcesoFacturacion').removeClass('hidden').empty().append(respuesta.formulario);
        $('#btnRegresarFacturacionTesoreria').removeClass('hidden');

    }

    var cargarElementosFormularioSubirFactura = function () {
        tabla.generaTablaPersonal('#data-table-subir-facturas', null, null, true, true, [[0, 'desc']]);

        file1.crearUpload('#evidenciasFacturaTesoreria',
                'Facturacion/guardarFacturaAsociado',
                ['pdf', 'xml'],
                null,
                null,
                'Seguimiento/Eliminar_Evidencia',
                null,
                null,
                2,
                null,
                null,
                null,
                null,
                2
                );
    }

    var cargarElementosFormularioPago = function () {
        file.crearUpload('#evidenciasPago',
                'Facturacion/guardarEvidenciaPagoFactura'
                );
    }

    var eventosFormularioSubirFactura = function () {
        var listaTickets = [];
        var listaIds = [];

        $('#data-table-subir-facturas').on('change', 'input.editor-active', function () {
            var dataCheckbox = $(this).attr('data-checkbox');
            var dataId = $(this).attr('data-id');
            if ($(this).is(":checked")) {
                listaTickets.push(dataCheckbox);
                listaIds.push(dataId);
            } else {
                listaTickets.splice($.inArray(dataCheckbox, listaTickets), 1);
                listaIds.splice($.inArray(dataId, listaIds), 1);
            }

            var listaTicketsFiltrada = listaTickets.unique();
            var stringListaTickets = listaTicketsFiltrada.join(",");
            $('#inputTicketsFacturaTesoreria').val(stringListaTickets);
        });

        $('#btnGuardarSubirArchivos').off('click');
        $('#btnGuardarSubirArchivos').on('click', function () {
            var tickets = $('#inputTicketsFacturaTesoreria').val();
            var evidencias = $('#evidenciasFacturaTesoreria').val();
            if (tickets !== '') {
                if (evidencias !== '') {
                    var data = {tickets: tickets, listaIds: listaIds};
                    file1.enviarArchivos('#evidenciasFacturaTesoreria', 'Facturacion/guardarFacturaAsociado', '#panelFacturacionTesoreria', data, function (respuesta) {
                        if (respuesta instanceof Array || respuesta instanceof Object) {
                            recargandoTablaFacturasPoliza(respuesta);
                            $('#seccionProcesoFacturacion').addClass('hidden').empty();
                            $('#listaFacturas').removeClass('hidden');
                            $('#btnRegresarFacturacionTesoreria').addClass('hidden');
                            servicios.mensajeModal('Se subio la factura con exito', 'Correcto', true);
                        } else {
                            file.limpiar('#evidenciasFacturaTesoreria');
                            evento.mostrarMensaje('#errorFormularioSubirFacturas', false, respuesta, 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorFormularioSubirFacturas', false, 'Debe seleccionar los archivos de la factura.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorFormularioSubirFacturas', false, 'Debe seleccionar por lo menos un ticket.', 3000);
            }
        });
    }

    var eventosFormularioValidarVuelta = function () {
        var respuesta = arguments[0];
        var id = respuesta.datos.datosTablaVueltas[0];

        if (respuesta.datos.arregloUsuario.IdPerfil == '46') {
            $('#inputMontoValidarVuelta').removeAttr('disabled');
        } else {
            $("#inputViaticosValidarVuelta").focusout(function () {
                var _cantidad = $('#inputViaticosValidarVuelta').val();
                var max = respuesta.datos.viatico;

                if (parseFloat(_cantidad) > parseFloat(max)) {
                    $('#inputViaticosValidarVuelta').val(max);
                }

                if (parseFloat(_cantidad) < parseFloat(0)) {
                    $('#inputViaticosValidarVuelta').val(0);
                }

            }).bind(function () {
                var _cantidad = $('#inputViaticosValidarVuelta').val();
                var max = respuesta.datos.viatico;

                if (parseFloat(_cantidad) > parseFloat(max)) {
                    $('#inputViaticosValidarVuelta').val(max);
                }

                if (parseFloat(_cantidad) < parseFloat(0)) {
                    $('#inputViaticosValidarVuelta').val(0);
                }
            });
        }

        $('#modalArchivoValidarVuelta').off('click');
        $('#modalArchivoValidarVuelta').on('click', function () {
            window.open(respuesta.datos.archivo, '_blank');
        });

        $('#btnValidarVuelta').off('click');
        $('#btnValidarVuelta').on('click', function () {
            validarVuelta(id);
        });

        $('#btnRechazarVuelta').off('click');
        $('#btnRechazarVuelta').on('click', function () {
            rechazarVuelta(id);
        });

    }

    var eventosFormularioSubirPago = function () {
        var respuesta = arguments[0];
        var evidenciaPDF = respuesta.datos.datosFactura[0].PDF;
        var evidenciaXML = respuesta.datos.datosFactura[0].XML;

        $('#modalArchivoFacturaPDF').off('click');
        $('#modalArchivoFacturaPDF').on('click', function () {
            window.open(evidenciaPDF, '_blank');
        });

        $('#modalArchivoFacturaXML').off('click');
        $('#modalArchivoFacturaXML').on('click', function () {
            window.open(evidenciaXML, '_blank');
        });

        $('#btnSubirPago').off('click');
        $('#btnSubirPago').on('click', function () {
            var arrayPDF = evidenciaPDF.split("Tickets-", 2);
            var arrayTickets = arrayPDF[1].split("/", 2);
            var evidencias = $('#evidenciasPago').val();
            if (evidencias !== '') {
                var data = {tickets: arrayTickets[0], xml: evidenciaXML};
                file.enviarArchivos('#evidenciasPago', 'Facturacion/guardarEvidenciaPagoFactura', '#panelFacturacionTesoreria', data, function (respuesta) {
                    if (respuesta instanceof Array || respuesta instanceof Object) {
                        recargandoTablaFacturasPago(respuesta);
                        $('#seccionProcesoFacturacion').addClass('hidden').empty();
                        $('#listaFacturas').removeClass('hidden');
                        $('#btnRegresarFacturacionTesoreria').addClass('hidden');
                        servicios.mensajeModal('Se subio la evidencía con exito', 'Correcto', true);
                    } else {
                        file.limpiar('#evidenciasPago');
                        evento.mostrarMensaje('#errorPago', false, 'Hubo un problema contacte al área correspondiente', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('#errorPago', false, 'Debe seleccionar la eviencía de Pago.', 3000);
            }
        });

        $('#btnDetallesFactura').off('click');
        $('#btnDetallesFactura').on('click', function () {
            var data = {xml: evidenciaXML};
            evento.enviarEvento('Facturacion/mostrarDetallesFactura', data, '#panelFacturacionTesoreria', function (respuesta) {
                evento.mostrarModal('Detalles de Factura', respuesta.formulario);
                tabla.generaTablaPersonal('#data-table-detalles-factura', null, null, true, true);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').empty().append('Cerrar');
            });
        });
    }

    var validarVuelta = function () {
        var id = arguments[0];
        var monto = $('#inputMontoValidarVuelta').val();
        var viatico = $('#inputViaticosValidarVuelta').val();
        var observaciones = $('#inputObservacionesValidarVuelta').val();
        var data = {id: id, monto: monto, viatico: viatico, observaciones: observaciones};

        evento.enviarEvento('Facturacion/guardarValidacionVuelta', data, '#panelFacturacionTesoreria', function (respuesta) {
            if (respuesta === true) {
                evento.mensajeConfirmacion('Se valido con exito', 'Correcto');
            } else {
                evento.mostrarMensaje('#erroValidarVuelta', false, 'Hubo algún problema con la validación de la vuelta.', 3000);
            }
        });

    }

    var rechazarVuelta = function () {
        var id = arguments[0];
        var observaciones = $('#inputObservacionesValidarVuelta').val();

        if (observaciones !== '') {
            var html = evento.mensajeValidar('¿Estas seguro de querer rechazar la vuelta?');
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            evento.mostrarModal('Rechazar Vuelta', html);
            $('#btnModalConfirmar').empty().append('Eliminar');
            $('#btnModalConfirmar').off('click');

            $('#btnAceptarConfirmacion').off('click');
            $('#btnAceptarConfirmacion').on('click', function () {
                var data = {id: id, observaciones: observaciones};
                evento.enviarEvento('Facturacion/rechazarVuelta', data, '#panelFacturacionTesoreria', function (respuesta) {
                    if (respuesta === true) {
                        evento.mensajeConfirmacion('Se rechazo con exito', 'Correcto');
                    } else {
                        evento.mostrarMensaje('#erroValidarVuelta', false, 'Hubo algún problema con la validación de la vuelta.', 3000);
                    }
                });
            });
        } else {
            evento.mostrarMensaje('#erroValidarVuelta', false, 'El campo Observaciones esta vacío.', 3000);
        }

        $('#btnCancelarConfirmacion').off('click');
        $('#btnCancelarConfirmacion').on('click', function () {
            evento.cerrarModal();
        });
    }

    var observacionesRechazo = function () {
        var idVuelta = arguments[0];
        var data = {id: idVuelta};

        evento.enviarEvento('Facturacion/mostrarObservacionesFactura', data, '#panelFacturacionTesoreria', function (respuesta) {
            var html = '<div class="row text-center">\n\
                                <div class="col-md-12">\n\
                                    <h3>' + respuesta + '</h3>\n\
                                </div>\n\
                            </div>';

            evento.mostrarModal('Observaciones de Rechazo', html);
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').empty().append('Cerrar');
        });
    }

    var recargandoTablaFacturasPoliza = function () {
        var respuesta = arguments[0];

        tabla.limpiarTabla('#data-table-facturas-poliza');
        $.each(respuesta, function (key, valor) {
            tabla.agregarFila('#data-table-facturas-poliza', [valor.Id, valor.IdServicio, valor.Ticket, valor.Folio, valor.Vuelta, valor.Sucursal, valor.NombreAtiende, valor.Fecha, valor.Estatus, valor.EstatusServicio, valor.SupervisorAutorizado, valor.Monto, valor.Viatico, valor.Total], true);
        });
    };

    var recargandoTablaFacturasPago = function () {
        var respuesta = arguments[0];

        tabla.limpiarTabla('#data-table-facturas-tesoreria');
        if (respuesta !== undefined) {
            $.each(respuesta, function (key, valor) {
                tabla.agregarFila('#data-table-facturas-tesoreria', [valor.Id, valor.Tecnico, valor.Autoriza, valor.Fecha, valor.Estatus], true);
            });
        }
    };

    var botonRegresarFacturacion = function () {
        $('#btnRegresarFacturacionTesoreria').off('click');
        $('#btnRegresarFacturacionTesoreria').on('click', function () {
            $('#seccionProcesoFacturacion').addClass('hidden').empty();
            $('#listaFacturas').removeClass('hidden');
            $('#btnRegresarFacturacionTesoreria').addClass('hidden');
        });
    }

    Array.prototype.unique = function () {
        var a = [];
        var l = this.length;
        for (var i = 0; i < l; i++) {
            for (var j = i + 1; j < l; j++) {
                if (this[i] === this[j])
                    j = ++i;
            }
            a.push(this[i]);
        }
        return a;
    }
});