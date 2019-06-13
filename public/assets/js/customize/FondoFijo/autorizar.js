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

    tabla.generaTablaPersonal('#table-movimientos', null, null, true, true, [[0, 'asc']]);

    $('#table-movimientos tbody').on('click', 'tr', function () {
        let _this = this;
        var datosTabla = $('#table-movimientos').DataTable().row(_this).data();
        var datos = {
            'id': datosTabla[0],
            'autorizaciones': 1
        };

        evento.enviarEvento('Autorizar/DetallesMovimiento', datos, '#panelAutorizaciones', function (respuesta) {
            evento.iniciarModal("#modalEdit", "Detalles del movimiento", respuesta.html);
            $("#btnGuardarCambios").hide();

            $("#btnRechazarMovimiento").off("click");
            $("#btnRechazarMovimiento").on("click", function () {
                var _observaciones = $.trim($("#txtObservacionesAutorizacion").val());
                if (_observaciones != '') {
                    evento.enviarEvento('Autorizar/RechazarMovimiento', { 'id': datos.id, 'observaciones': _observaciones }, '#modalEdit', function (respuesta) {
                        if (respuesta.code == 200) {
                            evento.terminarModal("#modalEdit");
                            $("#page-loader").removeClass("hide");
                            location.reload();
                        } else {
                            evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al rechazar el movimiento. Recargue su página e intente de nuevo.", 3000)
                        }
                    });
                } else {
                    evento.mostrarMensaje("#errorAutorizacion", false, "Las observaciones son obligatorias para el rechazo de una comprobación.", 3000);
                }
            });

            $("#btnAutorizarMovimiento").off("click");
            $("#btnAutorizarMovimiento").on("click", function () {
                var _observaciones = $.trim($("#txtObservacionesAutorizacion").val());
                evento.enviarEvento('Autorizar/AutorizarMovimiento', { 'id': datos.id, 'observaciones': _observaciones }, '#modalEdit', function (respuesta) {
                    if (respuesta.code == 200) {
                        evento.terminarModal("#modalEdit");
                        $("#page-loader").removeClass("hide");
                        location.reload();
                    } else {
                        evento.mostrarMensaje("#error-in-modal", false, "Ocurrió un error al autorizar el movimiento. Recargue su página e intente de nuevo.", 3000)
                    }
                });
            });
        });
    });
});



