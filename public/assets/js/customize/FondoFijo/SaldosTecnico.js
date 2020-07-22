
$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
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

    tabla.generaTablaPersonal('#table-cuentas', null, null, true, true, [[1, 'asc']]);

    $('#table-cuentas tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-cuentas').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0]

        };
//            console.log(datos);
//            console.log(datosTabla);
        evento.enviarEvento('MiFondo/MovimientosTecnico', datos, '#panelCuentas', function (respuesta) {
            let aux = "";
//            console.log(respuesta);
            for (i = 0; i < respuesta.consulta.length; i++)
            {
                aux += "<tr onclick=(getDatos(" + respuesta.consulta[i].Id + ")); >";
                aux += "<td>" + respuesta.consulta[i].IdMovimiento + "</td>";
                aux += "<td>" + respuesta.consulta[i].Cuenta + "</span></td>";
                aux += "<td> $" + respuesta.consulta[i].Monto + "</td>";
                aux += "<td>" + respuesta.consulta[i].Concepto + "</td>";
                aux += "<td>" + respuesta.consulta[i].Estatus + "</td>";
                aux += "<td>" + respuesta.consulta[i].FechaRegistro + "</td>";
                if (respuesta.consulta[i].FechaAutorizacion == null || respuesta.consulta[i].FechaAutorizacion == "undefined")
                {
                    aux += "<td></td>";
                } else
                {
                    aux += "<td>" + respuesta.consulta[i].FechaAutorizacion + "</td>";
                }
                aux += "</tr>";
            }
            if (respuesta.code == 200) {
//                console.log("if");
                $("#saldoTecnico").empty().append(respuesta.formulario);
//               evento.cambiarDiv("#listaUsuariosFondoFijo", "#seccionDetalleMovimientos");
                initDetallesCuenta(datos);
                $("#table_datos").html(aux);
                $("#usuarioNombreTable").html("<strong>Nombre: </strong>");
                $("#saldo1Table").html("<strong>Efectivo Residing: </strong>");
                $("#saldo2Table").html("<strong>Efectivale Mensual: </strong>");
                $("#saldo3Table").html("<strong>Efectivale FF: </strong>");

                $('#usuarioNombre').html(datosTabla[1]);
                $('#saldo1').html(datosTabla[2]);
                $('#saldo2').html(datosTabla[3]);
                $('#saldo3').html(datosTabla[4]);

                tabla.generaTablaPersonal('#tabla-movimientos', null, null, true, true, [[1, 'asc']]);
                tabla.reordenarTabla('Fecha', 'asc');

            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);

            }

        });
    });

    function initDetallesCuenta(datos, filaCuenta) {
        tabla.generaTablaPersonal('#table-movimientos', null, null, true, true, [[0, 'desc']]);
        select.crearSelect("select");
        file.crearUpload('#fotosDeposito', 'MiFondo/RegistrarComprobante');

        $("#listConceptos").on("change", function () {
            var _tiposComprobante = ($("#listConceptos option:selected").attr("data-comprobante")).split(",");
            var _extensiones = [];
            file.destruir("#fotosDeposito");
            if ($(this).val() == "") {
                file.crearUpload('#fotosDeposito', 'MiFondo/RegistrarComprobante');
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
                file.crearUpload('#fotosDeposito', 'MiFondo/RegistrarComprobante', _extensiones);
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
            evento.enviarEvento('MiFondo/CargaServiciosTicket', {'ticket': _ticket}, '#panelDetalleCuenta', function (respuesta) {
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
                    'tipoCuenta': datos.tipoCuenta,
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
                    file.enviarArchivos('#fotosDeposito', 'MiFondo/RegistrarComprobante', '#panelDetalleCuenta', _datos, function (respuesta) {
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

        $('#table-movimientos tbody').on('click', 'tr', function () {
            var datos = $('#table-movimientos').DataTable().row(this).data();
            evento.enviarEvento('MiFondo/DetallesMovimiento', {'id': datos[0]}, '#panelDetalleCuenta', function (respuesta) {
                evento.iniciarModal("#modalEdit", "Detalles del movimiento", respuesta.html);
                $("#btnGuardarCambios").hide();

                $("#btnCancelarMovimiento").off("click");
                $("#btnCancelarMovimiento").on("click", function () {
                    evento.enviarEvento('MiFondo/CancelarMovimiento', {'id': datos[0]}, '#modalEdit', function (respuesta) {
                        if (respuesta.code == 200) {
                            evento.terminarModal("#modalEdit");
                            filaCuenta.click();
                        } else {
                            evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al cancelar el movimiento. Recargue su página e intente de nuevo.")
                        }
                    });
                });
            });
        });
    }

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
            evento.enviarEvento('MiFondo/CargaMontoMaximoConcepto', datos, '#panelDetalleCuenta', function (respuesta) {
                $("#txtMonto").attr("data-monto", respuesta.monto);
                $("#txtMonto").trigger("change");
                $("#txtMontoMaximo").val(respuesta.monto);
            });
        }
    }

});
function getDatos(id, concepto)
{
    //console.log(concepto);
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
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
//        console.log(id);
    var datos = {
        'IdMovimiento': id
    };

    evento.enviarEvento('MiFondo/DetallesMovimientos', datos, '#panelCuentas', function (respuesta) {
        evento.iniciarModal("#modalEdit", "Detalles del depósito / ajuste", respuesta.html);
        $("#btnGuardarCambios").hide();
        $("#btnCancelarMovimiento").off("click");
        $('#userReg').html(respuesta.generales[0].Autoriza);
        $('#userMov').html(respuesta.generales[0].TipoMovimiento);
        $('#userConce').html(respuesta.generales[0].Nombre);
        $('#userMont').html("$" + respuesta.generales[0].Monto);
        $('#userSaldA').html("$" + respuesta.generales[0].SaldoPrevio);
        $('#userSald').html("$" + respuesta.generales[0].SaldoNuevo);
        $('#userStatus').html(respuesta.generales[0].Estatus);
        $('#fechaMov').html(respuesta.generales[0].FechaMovimiento);
        $('#fechaAut').html(respuesta.generales[0].FechaAutorizacion);
        $('#userAut').html(respuesta.generales[0].Autoriza);
        $('#userExt').html(respuesta.generales[0].Extraordinario);
        $('#userPres').html(respuesta.generales[0].EnPresupuesto);
        let DatosComplementarios = "";
        if (respuesta.generales[0].Ticket == null || respuesta.generales[0].Ticket == "" || respuesta.generales[0].Ticket == "undefined")
        {
        } else
        {
            DatosComplementarios += "<div class='col-md-6 col-sm-6 col-xs-12'>";
            DatosComplementarios += "    <div class='form-group'>";
            DatosComplementarios += "        <label class='f-s-13 f-w-600'>Ticket</label>";
            DatosComplementarios += "        <label class='form-control'>";
            DatosComplementarios += respuesta.generales[0].Ticket + "</label>";
            DatosComplementarios += "    </div>";
            DatosComplementarios += "</div>";
        }

        if (respuesta.generales[0].Origen == null || respuesta.generales[0].Origen == "" || respuesta.generales[0].Origen == "undefined")
        {
        } else
        {
            DatosComplementarios += "<div class='col-md-12 col-sm-12 col-xs-12'>";
            DatosComplementarios += "<div class='form-group'>";
            DatosComplementarios += "<label class='f-s-13 f-w-600'>Origen</label>";
            DatosComplementarios += "<label class='form-control'>";
            DatosComplementarios += respuesta.generales[0].Origen + "</label>";
            DatosComplementarios += "</div>";
            DatosComplementarios += "</div>";
        }
        if (respuesta.generales[0].Destino == null || respuesta.generales[0].Destino == "" || respuesta.generales[0].Destino == "undefined")
        {
        } else
        {
            DatosComplementarios += "<div class='col-md-12 col-sm-12 col-xs-12'>";
            DatosComplementarios += "<div class='form-group'>";
            DatosComplementarios += "<label class='f-s-13 f-w-600'>Destino</label>";
            DatosComplementarios += "<label class='form-control'>";
            DatosComplementarios += respuesta.generales[0].Destino + "</label>";
            DatosComplementarios += "</div>";
            DatosComplementarios += "</div>";
        }
        if (respuesta.generales[0].Observaciones == null || respuesta.generales[0].Observaciones == "" || respuesta.generales[0].Observaciones == "undefined")
        {
        } else
        {
            DatosComplementarios += "<div class='col-md-12 col-sm-12 col-xs-12'>";
            DatosComplementarios += "<div class='form-group'>";
            DatosComplementarios += "<label class='f-s-13 f-w-600'>Observaciones</label>";
            DatosComplementarios += "<label class='form-control'>";
            DatosComplementarios += respuesta.generales[0].Observaciones + "</label>";
            DatosComplementarios += "</div>";
            DatosComplementarios += "</div>";
        }
        if (respuesta.generales[0].TipoComprobante == null || respuesta.generales[0].TipoComprobante == "" || respuesta.generales[0].TipoComprobante == "undefined")
        {
        } else
        {
            DatosComplementarios += "<div class='col-md-12 col-sm-12 col-xs-12'>";
            DatosComplementarios += "<div class='form-group'>";
            DatosComplementarios += "<label class='f-s-13 f-w-600'>TipoComprobante</label>";
            DatosComplementarios += "<label class='form-control'>";
            DatosComplementarios += respuesta.generales[0].TipoComprobante + "</label>";
            DatosComplementarios += "</div>";
            DatosComplementarios += "</div>";
        }

        if (respuesta.generales[0].XML == null || respuesta.generales[0].XML == "" || respuesta.generales[0].XML == "undefined")
        {
        } else
        {
            DatosComplementarios += "<div class='col-md-6 col-sm-6 col-xs-12 text-center'>";
            DatosComplementarios += "   <div class='form-group'>";
            DatosComplementarios += "       <label class='f-s-13 f-w-600'>Archivo XML</label>";
            DatosComplementarios += "       <div class='thumbnail-pic m-l-5 m-r-5 m-b-5 p-5'>";
            DatosComplementarios += "            <a class='imagenesSolicitud' target='_blank'";
            DatosComplementarios += "               href='" + respuesta.generales[0].XML + "'><img src='/assets/img/Iconos/xml_icon.png' class='img-responsive img-thumbnail' style='max-height:130px !important;' alt='XML' /></a>'";
            DatosComplementarios += "       </div>";
            DatosComplementarios += "   </div>";
            DatosComplementarios += " </div>";
            DatosComplementarios += " <div class='col-md-6 col-sm-6 col-xs-12 text-center'>";
            DatosComplementarios += "     <div class='form-group'>";
            DatosComplementarios += "        <label class='f-s-13 f-w-600'>Archivo PDF</label>";
            DatosComplementarios += "        <div class='thumbnail-pic m-l-5 m-r-5 m-b-5 p-5'>";
            DatosComplementarios += "             <a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].PDF + "'><img src='/assets/img/Iconos/pdf_icon.png' class='img-responsive img-thumbnail' style='max-height:130px !important;' alt='XML' /></a>';";
            DatosComplementarios += "         </div>";
            DatosComplementarios += "     </div>";
            DatosComplementarios += " </div>";
        }
        if (respuesta.generales[0].Archivos == null || respuesta.generales[0].Archivos == "" || respuesta.generales[0].Archivos == "undefined")
        {
        } else
        {
            var ruta = respuesta.generales[0].Archivos;
            var extension = ruta.split(".");
            DatosComplementarios += "<div class='col-md-12 col-sm-12 col-xs-12'>";
            switch (extension[1]) {
                case 'png':
                    DatosComplementarios += "'<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='/assets/img/Iconos/no-thumbnail.jpg' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
                case 'jpeg':
                    DatosComplementarios += "'<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='/assets/img/Iconos/no-thumbnail.jpg' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
                case 'jpg':
                    DatosComplementarios += "'<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='/assets/img/Iconos/no-thumbnail.jpg' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
                case 'gif':
                    DatosComplementarios += "<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='" + respuesta.generales[0].Archivos + "' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>";
                    break;
                case 'xls':
                case 'xlsx':
                    DatosComplementarios += "<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + " '><img src='/assets/img/Iconos/excel_icon.png' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
                case 'doc':
                case 'docx':
                    DatosComplementarios += "'<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='/assets/img/Iconos/word_icon.png' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
                case 'pdf':
                    DatosComplementarios += "'<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='/assets/img/Iconos/pdf_icon.png' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
                default:
                    DatosComplementarios += "'<a class='imagenesSolicitud' target='_blank' href='" + respuesta.generales[0].Archivos + "'><img src='/assets/img/Iconos/no-thumbnail.jpg' class='img-responsive img-thumbnail' style='max-height:100px !important;' alt='Evidencia' /></a>'";
                    break;
            }
            DatosComplementarios += "</div>";
        }
        $("#datosComplementarios").html(DatosComplementarios);

    });

}