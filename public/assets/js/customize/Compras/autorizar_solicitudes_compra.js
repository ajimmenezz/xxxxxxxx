$(function () {

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

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-mis-solitudes-compra', null, null, true, true, [[4, 'asc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('#data-table-mis-solitudes-compra tbody').on('click', 'tr', function () {
        var rowData = $('#data-table-mis-solitudes-compra').DataTable().row(this).data();

        evento.enviarEvento('Compras/FormularioAutorizarSolicitudCompra', { id: rowData[0] }, '#panelAutorizarSolicitudesCompra', function (respuesta) {
            $("#divFormularioSolicitarCompra").empty().append(respuesta.html);
            evento.cambiarDiv("#divMisSolicitudesCompra", "#divFormularioSolicitarCompra");
            initFormularioAutorizaciones(rowData[0]);
        });

        function initFormularioAutorizaciones(idSolicitud) {

            tabla.generaTablaPersonal('#data-table-sae-products', null, null, true, true, [[1, 'asc']]);
            tabla.generaTablaPersonal('#data-table-productos-solicitados', null, null, true, true, [[1, 'asc']]);

            $("#btnAutorizarSolicitud").off("click");
            $("#btnAutorizarSolicitud").on("click", function () {
                evento.enviarEvento('Compras/AutorizarSolicitudCompra', { id: idSolicitud }, '#panelFormularioSolicitudCompra', function (respuesta) {
                    if (respuesta.code == 200) {
                        window.location.reload();
                    } else {
                        evento.mostrarMensaje("#errorFormulario", false, "Ocurrió un error al intentar autorizar la compra. Intente de nuevo o contacte al administrador.", 5000);
                    }
                });
            });

            $("#btnRechazarSolicitud").off("click");
            $("#btnRechazarSolicitud").on("click", function () {
                var _html = `
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Motivos de Rechazo *</label>
                                <textarea id="txtMotivosRechazoSolicitud" class="form-control" placeholder="Ingresa los motivos del rechazo de la solicitud" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                `;

                evento.iniciarModal("#modalEdit", "Rechazar Solicitud", _html);

                $("#btnGuardarCambios").off("click");
                $("#btnGuardarCambios").on("click", function () {
                    var motivos = $.trim($("#txtMotivosRechazoSolicitud").val());
                    evento.enviarEvento('Compras/RechazarSolicitudCompra', { 'id': idSolicitud, 'motivos': motivos }, '#modalEdit', function (respuesta) {
                        if (respuesta.code == 200) {
                            evento.terminarModal();
                            window.location.reload();
                        } else {
                            evento.mostrarMensaje("#errorFormulario", false, "Ocurrió un error al intentar rechazar la solicitud. Intente de nuevo o contacte al administrador.", 5000);
                        }
                    });
                });

            });
        }
    });


});
