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

                    $("#btnGuardarDeposito").off("click");
                    $("#btnGuardarDeposito").on("click", function () {
                        if (evento.validarFormulario('#form-registrar-deposito')) {
                            var _datos = {
                                'id': datos[0],
                                'fecha': $("#txtDate").val(),
                                'monto': $.trim($("#txtMonto").val()),
                                'concepto': $("input:radio[name ='optionsConcepto']:checked").val(),
                                'observaciones': $.trim($("#textObservaciones").val()),
                                'evidencias': $("#fotosDeposito").val()
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


