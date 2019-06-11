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

    tabla.generaTablaPersonal('#table-usuarios', null, null, true, true, [[1, 'asc']]);

    $('#table-usuarios tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-usuarios').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0],
            'nombre': datosTabla[1]
        };

        evento.enviarEvento('Depositar/FormularioDepositar', datos, '#panelDepositar', function (respuesta) {
            if (respuesta.code == 200) {
                $("#formularioDepositar").empty().append(respuesta.formulario);
                evento.cambiarDiv("#listaUsuariosFondoFijo", "#formularioDepositar");
                initDepositar(datos);
            } else {
                evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
            }
        });
    });

    function initDepositar(datos) {
        file.crearUpload('#fotosDeposito', 'Depositar/RegistrarDeposito', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf']);
        tabla.generaTablaPersonal('#tabla-depositos', null, null, true, true, [[2, 'desc']]);

        datos.tipoCuenta = '';
        $("#listTiposCuenta").on("change", function () {
            var tipoCuenta = $(this).val();
            datos.tipoCuenta = tipoCuenta;
            if (tipoCuenta == "") {
                $("#montoMaximoAutorizado, #saldoActual, #montoSugerido").empty().append("$");
                $("#txtMontoDepositar").val("").attr("disabled", "disabled");
            } else {
                evento.enviarEvento('Depositar/MontosDepositar', datos, '#panelDepositar', function (respuesta) {
                    if (respuesta.code == 200) {
                        $("#montoMaximoAutorizado").empty().append('$' + respuesta.montos.montoMaximo);
                        $("#saldoActual").empty().append('$' + respuesta.montos.saldo);
                        $("#montoSugerido").empty().append('$' + respuesta.montos.sugerido);
                        $("#txtMontoDepositar").val(respuesta.montos.sugerido.replace(",", "")).removeAttr("disabled");
                    } else {
                        evento.mostrarMensaje("#errorMessage", false, respuesta.error, 4000);
                    }
                });
            }
        });

        $("#btnGuardarDeposito").off("click");
        $("#btnGuardarDeposito").on("click", function () {

            datos.depositar = parseFloat($.trim($("#txtMontoDepositar").val()));

            if (datos.tipoCuenta == "") {
                evento.mostrarMensaje("#errorMessage", false, "Debe seleccionar un tipo de cuenta", 4000);
                return false;
            }

            if (isNaN(datos.depositar) || datos.depositar == 0) {
                evento.mostrarMensaje("#errorMessage", false, "El monto a depositar ó ajustar debe ser diferente de 0 (cero)", 4000);
                return false;
            }

            if ($("#fotosDeposito").val() == '') {
                evento.mostrarMensaje("#errorMessage", false, "La evidencia del deposito es obligatoria.", 4000);
                return false;
            }

            file.enviarArchivos('#fotosDeposito', 'Fondo_Fijo/RegistrarDeposito', '#panelDepositar', datos, function (respuesta) {
                if (respuesta.code == 200) {
                    $("#btnRegresar").trigger("click");
                    $("#btnRegresar").click();
                    evento.mostrarMensaje("#errorMessage", true, "El depósito fué registrado correctamente.", 4000);
                } else {
                    evento.mostrarMensaje("#errorMessage", false, "Ocurrió un error al guardar el depósito. Por favor recargue su página y vuelva a intentarlo.", 4000);
                }
            });
        });

        $('#tabla-depositos tbody').on('click', 'tr', function () {
            var datos = $('#tabla-depositos').DataTable().row(this).data();
            evento.enviarEvento('MiFondo/DetallesMovimiento', { 'id': datos[0] }, '#panelDepositar', function (respuesta) {
                evento.iniciarModal("#modalEdit", "Detalles del depósito / ajuste", respuesta.html);
                $("#btnGuardarCambios").hide();

                $("#btnCancelarMovimiento").off("click");
                $("#btnCancelarMovimiento").on("click", function () {
                    evento.enviarEvento('MiFondo/CancelarMovimiento', { 'id': datos[0] }, '#modalEdit', function (respuesta) {
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
});



