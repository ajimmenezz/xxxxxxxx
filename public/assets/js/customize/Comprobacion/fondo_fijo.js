$(function () {
//Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();
    var file = new Upload();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();
    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());
    //Evento para cerra la session
    evento.cerrarSesion();
    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    //Inicializa funciones de la plantilla
    App.init();

    tabla.generaTablaPersonal("#table-comprobaciones-fondo-fijo", null, null, true);

    $("#btnRegistrarComprobante").off("click");
    $("#btnRegistrarComprobante").on("click", function () {
        evento.enviarEvento('Fondo_Fijo/FormularioRegistrarComprobante', {}, '#panelDetallesFondoFijo', function (respuesta) {
            $("#seccionRegistrarDeposito").empty().append(respuesta.html);
            evento.cambiarDiv("#seccionDetallesFondoFijo", "#seccionRegistrarDeposito");
            select.crearSelect("select");
            file.crearUpload('#fotosDeposito', 'Fondo_Fijo/RegistrarComprobante');

            $("#listConceptos").on("change", function () {
                var _tiposComprobante = ($("#listConceptos option:selected").attr("data-comprobante")).split(",");
                var _extensiones = [];
                file.destruir("#fotosDeposito");
                if ($(this).val() == "") {
                    file.crearUpload('#fotosDeposito', 'Fondo_Fijo/RegistrarComprobante');
                } else {
                    $.each(_tiposComprobante, function (k, v) {
                        switch (v) {
                            case "1":
                                _extensiones = _extensiones.concat(['xml', 'pdf']);
                                break;
                            case "2":
                            case "3":
                                _extensiones = _extensiones.concat(['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'msg']);
                                break;
                        }
                    });
                    file.crearUpload('#fotosDeposito', 'Fondo_Fijo/RegistrarComprobante', _extensiones);
                }

                cargaMontoMaximo();
            });

            $("#txtMonto").on("change", function () {
                var _monto = $(this).val();
                var _maximo = $(this).attr("data-monto");
                if ($.isNumeric(_monto)) {
                    if (parseFloat(_monto) <= parseFloat(_maximo)) {
                        $("#warningMontos").addClass("hidden");
                        $(this).attr("data-presupuesto", 1);
                    } else {
                        $("#warningMontos").removeClass("hidden");
                        $("#warningMontoMaximo").empty().append("$" + _maximo);
                        $(this).attr("data-presupuesto", 0);
                    }
                } else {
                    $("#warningMontos").addClass("hidden");
                    $("#txtMonto").val("");
                    $(this).attr("data-presupuesto", 0);
                }
            });

            $("#listTickets").on("change", function () {
                var _ticket = $(this).val();
                evento.enviarEvento('Fondo_Fijo/CargaServiciosTicket', {'ticket': _ticket}, '#panelRegistrarComprobante', function (respuesta) {
                    $("#listServicios").empty().append('<option value="">Seleccionar . . .</option>');
                    select.cambiarOpcion("#listServicios", "");
                    $("#listServicios").attr("disabled", "disabled");
                    if (respuesta.length > 0) {
                        $.each(respuesta, function (k, v) {
                            $("#listServicios").append('<option value="' + v.Id + '">' + v.Id + ' - ' + v.Tipo + ' - ' + v.Descripcion + '</option>');
                        });
                        $("#listServicios").removeAttr("disabled");
                    }
                });
            });

            $("#listOrigenes").on("change", function () {
                var _origen = $(this).val();
                if (_origen == 'o') {
                    $("#divOtroOrigen").removeClass("hidden");
                } else {
                    $("#divOtroOrigen").addClass("hidden");
                    $("#textOrigen").val("");
                }
            });

            $("#listDestinos").on("change", function () {
                var _destino = $(this).val();
                if (_destino == 'o') {
                    $("#divOtroDestino").removeClass("hidden");
                } else {
                    $("#divOtroDestino").addClass("hidden");
                    $("#textDestino").val("");
                }
                cargaMontoMaximo();
            });

            $("#btnGuardarComprobante").off("click");
            $("#btnGuardarComprobante").on("click", function () {
                if (evento.validarFormulario('#form-registrar-comprobante')) {
                    var _datos = {
                        'fecha': $("#txtDate").val(),
                        'monto': "-" + $.trim($("#txtMonto").val()),
                        'montoMaximo': $.trim($("#txtMontoMaximo").val()),
                        'enPresupuesto': $("#txtMonto").attr("data-presupuesto"),
                        'concepto': $("#listConceptos").val(),
                        'tiposComprobante': ($("#listConceptos option:selected").attr("data-comprobante")).split(","),
                        'ticket': $("#listTickets").val(),
                        'servicio': $("#listServicios").val(),
                        'origen': $("#listOrigenes").val(),
                        'stringOrigen': $.trim($("#txtOrigen").val()),
                        'destino': $("#listDestinos").val(),
                        'stringDestino': $.trim($("#txtDestino").val()),
                        'observaciones': $.trim($("#textObservaciones").val()),
                        'evidencias': $("#fotosDeposito").val()
                    }

                    var pasa = true;

                    if (_datos.ticket != "") {
                        if (_datos.servicio == "") {
                            pasa = false;
                            evento.mostrarMensaje("#errorMessageComprobante", false, "Es necesario que seleccione el servicio de la lista.", 4000);
                        } else if (_datos.origen == "") {
                            pasa = false;
                            evento.mostrarMensaje("#errorMessageComprobante", false, "El campo 'Origen' es obligatorio cuando selecciona un ticket.", 4000);
                        } else if (_datos.origen == "o" && _datos.stringOrigen == "") {
                            pasa = false;
                            evento.mostrarMensaje("#errorMessageComprobante", false, "Es necesario que se describa el origen.", 4000);
                        } else if (_datos.destino == "") {
                            pasa = false;
                            evento.mostrarMensaje("#errorMessageComprobante", false, "El campo 'Destino' es obligatorio cuando selecciona un ticket.", 4000);
                        } else if (_datos.destino == "o" && _datos.stringDestino == "") {
                            pasa = false;
                            evento.mostrarMensaje("#errorMessageComprobante", false, "Es necesario que se describa el destino.", 4000);
                        }
                    }

                    if (_datos.origen == "o" && _datos.stringOrigen == "") {
                        pasa = false;
                        evento.mostrarMensaje("#errorMessageComprobante", false, "Es necesario que se describa el origen.", 4000);
                    }

                    if (_datos.destino == "o" && _datos.stringDestino == "") {
                        pasa = false;
                        evento.mostrarMensaje("#errorMessageComprobante", false, "Es necesario que se describa el destino.", 4000);
                    }

                    if (!_datos.tiposComprobante.includes("3")) {
                        if (_datos.evidencias == "") {
                            pasa = false;
                            evento.mostrarMensaje("#errorMessageComprobante", false, "Los archivos del comprobante son obligatorios.", 4000);
                        }
                    }

                    if (pasa) {
                        file.enviarArchivos('#fotosDeposito', 'Fondo_Fijo/RegistrarComprobante', '#panelRegistrarComprobante', _datos, function (respuesta) {
                            if (respuesta.code == 200) {
                                $("#page-loader").removeClass("hide");
                                location.reload();
                            } else {
                                file.limpiar("#fotosDeposito");
                                evento.mostrarMensaje("#errorMessageComprobante", false, "Ocurrió el siguiente error al guardar el comprobante. " + respuesta.errorBack, 4000);
                            }
                        });
                    }
                }
            });
        });

    });

    function cargaMontoMaximo() {
        var _concepto = $("#listConceptos").val();
        if (_concepto == "") {
            $("#txtMonto").val("");
            $("#txtMonto").removeAttr("data-monto");
            $("#txtMonto").attr("disabled", "disabled");
            $("#txtMonto").trigger("change");
            $("#txtMontoMaximo").val('0');
        } else {
            $("#txtMonto").removeAttr("disabled");
            var datos = {
                'concepto': _concepto,
                'destino': $("#listDestinos").val()
            }
            evento.enviarEvento('Fondo_Fijo/CargaMontoMaximoConcepto', datos, '#panelRegistrarComprobante', function (respuesta) {
                $("#txtMonto").attr("data-monto", respuesta.monto);
                $("#txtMonto").trigger("change");
                $("#txtMontoMaximo").val(respuesta.monto);
            });
        }
    }

    $('#table-comprobaciones-fondo-fijo tbody').on('click', 'tr', function () {
        var datos = $('#table-comprobaciones-fondo-fijo').DataTable().row(this).data();
        evento.enviarEvento('Fondo_Fijo/FormularioDetallesMovimiento', {'id': datos[0]}, '#panelDetallesFondoFijo', function (respuesta) {
            evento.iniciarModal("#modalEdit", "Detalles del movimiento", respuesta.html);
            $("#btnGuardarCambios").hide();

            $("#btnCancelarMovimiento").off("click");
            $("#btnCancelarMovimiento").on("click", function () {
                evento.enviarEvento('Fondo_Fijo/CancelarMovimiento', {'id': datos[0]}, '#modalEdit', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.terminarModal("#modalEdit");
                        $("#page-loader").removeClass("hide");
                        location.reload();
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al cancelar el movimiento. Recargue su página e intente de nuevo.")
                    }
                });
            });
        });
    });
});


