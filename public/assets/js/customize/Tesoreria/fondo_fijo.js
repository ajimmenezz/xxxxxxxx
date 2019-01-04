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

    //Creando tabla de sistemas especiales
    tabla.generaTablaPersonal('#table-fondos-fijos', null, null, true);

    $('#table-fondos-fijos tbody').on('click', 'tr', function () {
        var datos = $('#table-fondos-fijos').DataTable().row(this).data();
        cargaDetallesFondoFijo(datos);
    });

    function cargaDetallesFondoFijo() {
        var datos = arguments[0];
        var panelLoading = arguments[1] || '#panelFondosFijos';
        var divToHide = arguments[2] || "#listaFondosFijos";
        var divToBack = arguments[3] || "";
        evento.enviarEvento('Fondo_Fijo/DetallesFondoFijoXUsuario', {'id': datos[0]}, panelLoading, function (respuesta) {
            $("#seccionDetallesFondoFijo").empty().append(respuesta.html);
            evento.cambiarDiv(divToHide, "#seccionDetallesFondoFijo", divToBack);
            tabla.generaTablaPersonal("#table-comprobaciones-fondo-fijo", null, null, true);

            $("#btnRegistrarDeposito").off("click");
            $("#btnRegistrarDeposito").on("click", function () {
                evento.enviarEvento('Fondo_Fijo/FormularioRegistrarDeposito', {'id': datos[0]}, '#panelDetallesFondoFijo', function (respuesta) {
                    $("#seccionRegistrarDeposito").empty().append(respuesta.html);
                    evento.cambiarDiv("#seccionDetallesFondoFijo", "#seccionRegistrarDeposito");
                    file.crearUpload('#fotosDeposito', 'Fondo_Fijo/RegistrarDeposito', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf']);
                    tabla.generaTablaPersonal('#table-comprobacion-sin-pago', null, null, true);

                    $(".btnRegistrarDeposito").off("click");
                    $(".btnRegistrarDeposito").on("click", function () {
                        $("#txtMonto").val('');
                        var _conceptoRegistro = $(this).attr("data-id-concepto-registro");
                        switch (_conceptoRegistro) {
                            case 1:
                            case '1':
                                $("#txtMonto").val($("#hiddenMontoOtros").val()).removeAttr("disabled");
                                $("#hiddenConceptoRegistro").val(1);
                                $("#txtConceptoRegistro").val("Fondo Fijo sin Factura");
                                tabla.filtrarColumna("#table-comprobacion-sin-pago", 1, 1);
                                break;
                            case 2:
                            case '2':
                                $("#txtMonto").val($("#hiddenMontoGasolina").val()).removeAttr("disabled");
                                $("#hiddenConceptoRegistro").val(2);
                                $("#txtConceptoRegistro").val("GASOLINA");
                                tabla.filtrarColumna("#table-comprobacion-sin-pago", 1, 2);
                                break;
                            case 3:
                            case '3':
                                $("#txtMonto").val($("#hiddenMontoSiccob").val()).attr("disabled", "disabled");
                                $("#hiddenConceptoRegistro").val(3);
                                $("#txtConceptoRegistro").val("Facturas SSO0101179Z7");
                                tabla.filtrarColumna("#table-comprobacion-sin-pago", 1, 3);
                                break;
                            case 4:
                            case '4':
                                $("#txtMonto").val($("#hiddenMontoResidig").val()).attr("disabled", "disabled");
                                $("#hiddenConceptoRegistro").val(4);
                                $("#txtConceptoRegistro").val("Facturas RSD130305DI7");
                                tabla.filtrarColumna("#table-comprobacion-sin-pago", 1, 4);
                                break;
                            default:
                                $("#txtMonto").val('');
                                $("#hiddenConceptoRegistro").val('');
                                $("#txtConceptoRegistro").val('');
                                break;
                        }
                    });

                    $("#btnGuardarDeposito").off("click");
                    $("#btnGuardarDeposito").on("click", function () {
                        if (evento.validarFormulario('#form-registrar-deposito')) {

                            var _comprobaciones = [];

                            $.each(tabla.getTableData("#table-comprobacion-sin-pago", true), function (k, v) {
                                if ($.isNumeric(k)) {
                                    _comprobaciones.push(v[0]);
                                }
                            });

                            var _datos = {
                                'id': datos[0],
                                'fecha': $("#txtDate").val(),
                                'monto': $.trim($("#txtMonto").val()),
                                'concepto': $("#hiddenConceptoRegistro").val(),
                                'observaciones': $.trim($("#textObservaciones").val()),
                                'evidencias': $("#fotosDeposito").val(),
                                'comprobaciones': _comprobaciones
                            }

                            console.log(_datos);

                            if (_datos.monto < 1) {
                                evento.mostrarMensaje("#errorMessageDeposito", false, "El monto depositado no puede ser menor a $1.00", 4000);
                                return false;
                            }
                            if (_datos.evidencias != "") {
                                file.enviarArchivos('#fotosDeposito', 'Fondo_Fijo/RegistrarDeposito', '#panelRegistrarDeposito', _datos, function (respuesta) {
                                    if (respuesta.code == 200) {
                                        cargaDetallesFondoFijo(datos, "#panelRegistrarDeposito", "#seccionRegistrarDeposito", "#listaFondosFijos");
                                    } else {
                                        evento.mostrarMensaje("#errorMessageDeposito", false, "Ocurrió un error al guardar el depósito. Por favor recargue su página y vuelva a intentarlo.", 4000);
                                    }
                                });
                            } else {
                                evento.mostrarMensaje("#errorMessageDeposito", false, "Las evidencias del depósito son obligatorias.", 4000);
                            }


                        }
                    });
                });

            });

            $('#table-comprobaciones-fondo-fijo tbody').on('click', 'tr', function () {
                var datos = $('#table-comprobaciones-fondo-fijo').DataTable().row(this).data();
                evento.enviarEvento('Fondo_Fijo/FormularioDetallesMovimiento', {'id': datos[0], 'rol': '1'}, '#panelDetallesFondoFijo', function (respuesta) {
                    evento.iniciarModal("#modalEdit", "Detalles del movimiento", respuesta.html);
                    $("#btnGuardarCambios").hide();
                });
            });

        });
    }


});


