$(function() {

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


    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-movimientos-sae', null, null, true, true, [
        [0, 'desc']
    ]);

    fecha.rangoFechas('#desdeMovimientos', '#hastaMovimientos');

    $('#btnFiltrarMovimientos').off('click');
    $("#btnFiltrarMovimientos").on("click", function() {
        var desde = $("#txtDesdeMovimientos").val();
        var hasta = $("#txtHastaMovimientos").val();
        var texto = $.trim($("#txtArticulo").val());
        if (desde !== '') {
            if (hasta !== '') {
                var data = { desde: desde, hasta: hasta, texto: texto };
                recargarReporteMovimientos(data);
            } else {
                evento.mostrarMensaje('#errorMovimientosAlmacenes', false, 'Debe llenar el campo Hasta.', 3000);
            }
        } else {
            evento.mostrarMensaje('#errorMovimientosAlmacenes', false, 'Debe llenar el campo Desde.', 3000);
        }
    });

    var recargarReporteMovimientos = function(data) {
        evento.enviarEvento('Compras/getMovimientosAlmacenesSAE', data, '#seccion-movimientos-almacenes-SAE', function(respuesta) {
            tabla.limpiarTabla('#data-table-movimientos-sae');
            $.each(respuesta.movimientos, function(key, valor) {
                tabla.agregarFila('#data-table-movimientos-sae', [valor.Numero_Movimiento, valor.Folio, valor.Clave_Producto, valor.Articulo, valor.Almacen, valor.Concepto, valor.Movimiento, valor.Referencia, valor.Cantidad, valor.Series, valor.Costo, valor.Costo_Promo_Inicial, valor.Costo_Promo_Final, valor.Unidad_Venta, valor.Existencia, valor.Fecha, valor.MOV_ENLAZADO], true);
            });
        });
    };

    $("#btnExportarExcel").on("click", function() {
        var movimientos = $('#data-table-movimientos-sae').DataTable().rows({ search: 'applied' }).data();
        var realMovimientos = new Array();
        $.each(movimientos, function(k, v) {
            if (!isNaN(k)) {
                realMovimientos.push(v);
            }
        });
        var data = {
            movimientos: realMovimientos
        };
        evento.enviarEvento('Compras/exportaMovimientosAlmacenesSAE', data, '#seccion-movimientos-almacenes-SAE', function(respuesta) {
            window.open(respuesta.ruta, '_blank');
        });
    });

});