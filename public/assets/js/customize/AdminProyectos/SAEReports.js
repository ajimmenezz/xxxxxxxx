$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var fecha = new Fecha();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-almacenes', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-almacenes tbody').on('click', 'tr', function () {
        var datos = $('#data-table-almacenes').DataTable().row(this).data();
        var _almacen = datos[0];
        var data = {'almacen': datos[0]};
        evento.enviarEvento('SAEReports/getInventarioAlmacen', data, '#panelListaAlmacenes', function (respuesta) {
            tabla.limpiarTabla('#data-table-inventario');
            tabla.limpiarTabla('#data-table-movimientos-sae');
            $.each(respuesta.inventario, function (key, item) {
                tabla.agregarFila('#data-table-inventario', [item.Clave, item.Producto, item.Linea, item.Unidad, item.Existencia, item.Costo]);
            });
            $.each(respuesta.movimientos, function (key, valor) {
                tabla.agregarFila('#data-table-movimientos-sae', [valor.Numero_Movimiento, valor.Folio, valor.Clave_Producto, valor.Articulo, valor.Almacen, valor.Concepto, valor.Movimiento, valor.Referencia, valor.Cantidad, valor.Costo, valor.Costo_Promo_Inicial, valor.Costo_Promo_Final, valor.Unidad_Venta, valor.Existencia, valor.Fecha, valor.MOV_ENLAZADO], true);
            });
            $("#nombreAlmacen").empty().append(datos[1]);
            $("#panelListaAlmacenes").addClass('hidden');
            $("#panelInventarioAlmacen").removeClass('hidden');
            fecha.rangoFechas('#desdeMovimientos', '#hastaMovimientos');

            $('#btnFiltrarMovimientos').off('click');
            $("#btnFiltrarMovimientos").on("click", function () {
                var desde = $("#txtDesdeMovimientos").val();
                var hasta = $("#txtHastaMovimientos").val();
                if (desde !== '') {
                    if (hasta !== '') {
                        var data = {desde: desde, hasta: hasta, almacen: _almacen};
                        recargarReporteMovimientos(data);
                    } else {
                        evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Hasta.', 3000);
                    }
                } else {
                    evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Desde.', 3000);
                }
            });
        });
    });

    var recargarReporteMovimientos = function (data) {
        evento.enviarEvento('SAEReports/getInventarioAlmacen', data, '#panelInventarioAlmacen', function (respuesta) {
            tabla.limpiarTabla('#data-table-movimientos-sae');
            $.each(respuesta.movimientos, function (key, valor) {
                tabla.agregarFila('#data-table-movimientos-sae', [valor.Numero_Movimiento, valor.Folio, valor.Clave_Producto, valor.Articulo, valor.Almacen, valor.Concepto, valor.Movimiento, valor.Referencia, valor.Cantidad, valor.Costo, valor.Costo_Promo_Inicial, valor.Costo_Promo_Final, valor.Unidad_Venta, valor.Existencia, valor.Fecha, valor.MOV_ENLAZADO], true);
            });
        });
    };

    $("#btnRegresarSeguimiento").on("click", function () {
        tabla.limpiarTabla('#data-table-inventario');
        $("#panelListaAlmacenes").removeClass('hidden');
        $("#panelInventarioAlmacen").addClass('hidden');
    });

    $("#btnExportarExcel").on("click", function () {
        var info = $('#data-table-inventario').DataTable().rows({search: 'applied'}).data();
        var movimientos = $('#data-table-movimientos-sae').DataTable().rows({search: 'applied'}).data();
        var realInfo = new Array();
        var realMovimientos = new Array();
        $.each(info, function (k, v) {
            if (!isNaN(k)) {
                realInfo.push(v);
            }
        });
        $.each(movimientos, function (k, v) {
            if (!isNaN(k)) {
                realMovimientos.push(v);
            }
        });
        var data = {
            info: realInfo,
            movimientos: realMovimientos,
            almacen: $("#nombreAlmacen").html()
        };
        evento.enviarEvento('SAEReports/exportaInventarioAlmacen', data, '#panelInventarioAlmacen', function (respuesta) {
            window.open(respuesta.ruta, '_blank');
        });
    });
});
