$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    tabla.generaTablaPersonal("#data-table-reportes", null, null, true, true, [[1, 'asc']]);

    $('#data-table-reportes tbody').on('click', 'tr', function () {
        var datos = $('#data-table-reportes').DataTable().row(this).data();
        if (datos !== undefined) {
            var idReporte = datos[0];

            evento.enviarEvento('Reportes/GetReporteCimos', {id: idReporte}, '#panelReportesCimos', function (respuesta) {
                $("#divContenidoReporte").empty().append(respuesta.html);

                $("#divListaReportes").fadeOut(400, function () {
                    $("#divContenidoReporte").fadeIn(400);
                });

                $("#divContenidoReporte #btnRegresar").off("click");
                $("#divContenidoReporte #btnRegresar").on("click", function () {
                    $("#divContenidoReporte").fadeOut(400, function () {
                        $("#divListaReportes").fadeIn(400, function () {
                            $("#divContenidoReporte").empty();
                        });
                    });
                });

                initReporte(idReporte);
            });
        }
    });

    function initReporte() {
        var idReporte = arguments[0];
        tabla.generaTablaPersonal("#data-table-detalles", null, null, true, true, [[1, 'asc']]);

        if (idReporte == 1) {
            $('#data-table-detalles tbody').on('click', 'tr', function () {
                var datos = $('#data-table-detalles').DataTable().row(this).data();
                makeContractPdf(datos);
            });
        }



        $("#btnExportarExcel").off("click");
        $("#btnExportarExcel").on("click", function () {
            evento.enviarEvento('Reportes/GetReporteCimosExcel', {id: idReporte}, '#panelReporte', function (respuesta) {
                window.open(respuesta.ruta, '_blank');
            });
        });
    }

    function makeContractPdf() {
        var datos = arguments[0];
        evento.enviarEvento('Reportes/MakeContractPdf', {data: datos}, '#panelReporte', function (respuesta) {
            window.open(respuesta, '_blank');
        });
    }
});
