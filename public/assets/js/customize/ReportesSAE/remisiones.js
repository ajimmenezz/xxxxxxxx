$(function () {

    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var fecha = new Fecha();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    $("#valorFiltroclaves").tagit({
        allowSpaces: true
    });

    $('#btnLimpiarComprasSAE').off('click');
    $("#btnLimpiarComprasSAE").on("click", function () {
        $("#inputBuscarProductoSAE").val('');
        select.eliminarOptionSeleccionar('#selectFiltroProductosSAE');
        $('#selectFiltroProductosSAE').attr('disabled', 'disabled');
    });

    $('#btnMostrarReporteComprasSAE').off('click');
    $("#btnMostrarReporteComprasSAE").on("click", function () {
        var desde = $("#txtDesdeComprasSAE").val();
        var hasta = $("#txtHastaComprasSAE").val();

        if (desde !== '') {
            if (hasta !== '') {
                var data = {desde: desde, hasta: hasta};
                mostrarReporteCompras(data);
            } else {
                evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Hasta.', 3000);
            }
        } else {
            evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Desde.', 3000);
        }
    });

    var mostrarReporteCompras = function (data) {
        evento.enviarEvento('Compras/mostrarReporteRemisiones', data, '#seccion-compras-SAE', function (respuesta) {
            $('#seccion-compras-SAE').addClass('hidden');
            $('#seccionReporteComprasSAE').removeClass('hidden').empty().append(respuesta.formulario);
            tabla.generaTablaPersonal('#data-table-compras-sae', null, null, true, true);
            tabla.limpiarTabla('#data-table-compras-sae');
            $.each(respuesta.datos.compras, function (key, valor) {
                tabla.agregarFila('#data-table-compras-sae', [valor.Remision, valor.FechaElaboracion, valor.TipoDocumentoAnterior, valor.DocumentoAnterior, valor.Producto, valor.Modelo, valor.Serie, valor.Observaciones_Partida, valor.Observaciones_Remision, valor.Pedido, valor.Req, valor.FECHA_DOC, valor.FECHA_ENT, valor.FECHA_VEN, valor.FECHA_CANCELA], true);
            });

            $('#btnRegresar').off('click');
            $("#btnRegresar").on("click", function () {
                $("#seccionReporteComprasSAE").addClass("hidden");
                $("#seccion-compras-SAE").removeClass("hidden");
            });

            $('#btnGeneraExcelReportesComprasSAE').off('click');
            $("#btnGeneraExcelReportesComprasSAE").on("click", function () {
                var compras = $('#data-table-compras-sae').DataTable().rows({search: 'applied'}).data();
                var realCompras = new Array();
                $.each(compras, function (k, v) {
                    if (!isNaN(k)) {
                        realCompras.push(v);
                    }
                });
                var data = {
                    compras: realCompras
                };

                evento.enviarEvento('Compras/exportaReporteRemisiones', data, '#seccionReporteComprasSAE', function (respuesta) {
                    window.open(respuesta.ruta, '_blank');
                });
            });
        });
    };

    fecha.rangoFechas('#desdeComprasSAE', '#hastaComprasSAE');
});
