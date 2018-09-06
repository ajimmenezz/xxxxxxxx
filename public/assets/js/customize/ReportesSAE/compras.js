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

    select.crearSelectMultiple("#listaColumnas", "Seleccionar");

    $("#checkboxColumnasDisponibles").click(function () {
        select.seleccionarTodos(this, $('#listaColumnas'));
    });

    $('#btnBuscarComprasSAE').off('click');
    $("#btnBuscarComprasSAE").on("click", function () {
        var producto = $("#inputBuscarProductoSAE").val();
        var dataProducto = {producto: producto};
        if (producto !== '') {
            evento.enviarEvento('Compras/buscarProductosCompras', dataProducto, '#seccion-compras-SAE', function (respuesta) {
                if (respuesta.length > 0) {
                    $('#selectFiltroProductosSAE').removeAttr('disabled');
                    $('#selectFiltroProductosSAE').empty();
                    $.each(respuesta, function (key, valor) {
                        $("#selectFiltroProductosSAE").append('<option value=' + valor.Clave + '>' + valor.Nombre + '</option>');
                    });
                    evento.mostrarMensaje('.errorProductoBuscar', true, 'Datos encotrados correctamente.', 3000);
                } else {
                    evento.mostrarMensaje('.errorProductoBuscar', false, 'No hay concidencias.', 3000);
                }
            });
        } else {
            evento.mostrarMensaje('.errorProductoBuscar', false, 'Debe llenar el campo Producto SAE.', 3000);
        }
    });

    $('#btnLimpiarComprasSAE').off('click');
    $("#btnLimpiarComprasSAE").on("click", function () {
        $("#inputBuscarProductoSAE").val('');
        select.eliminarOptionSeleccionar('#selectFiltroProductosSAE');
        $('#selectFiltroProductosSAE').attr('disabled', 'disabled');
    });

    $('#btnMostrarReporteComprasSAE').off('click');
    $("#btnMostrarReporteComprasSAE").on("click", function () {
        var desde = $("#txtDesdeComptrasSAE").val();
        var hasta = $("#txtHastaComprasSAE").val();
        var listaProductos = $("#selectFiltroProductosSAE").val();
        if (desde !== '') {
            if (hasta !== '') {
                if (listaProductos !== null) {
                    var data = {desde: desde, hasta: hasta, listaProductos};
                    mostrarReporteCompras(data);
                } else {
                    evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Productos Encontrados.', 3000);
                }
            } else {
                evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Hasta.', 3000);
            }
        } else {
            evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Desde.', 3000);
        }
    });

    var mostrarReporteCompras = function (data) {
        evento.enviarEvento('Compras/mostrarReporteComprasSAE', data, '#seccion-compras-SAE', function (respuesta) {
            $('#seccion-compras-SAE').addClass('hidden');
            $('#seccionReporteComprasSAE').removeClass('hidden').empty().append(respuesta.formulario);
            tabla.generaTablaPersonal('#data-table-compras-sae', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-existencias-sae', null, null, true, true);
            tabla.generaTablaPersonal('#data-table-movimientos-sae', null, null, true, true);
            tabla.limpiarTabla('#data-table-compras-sae');
            tabla.limpiarTabla('#data-table-existencias-sae');
            tabla.limpiarTabla('#data-table-movimientos-sae');
            $.each(respuesta.datos.compras, function (key, valor) {
                tabla.agregarFila('#data-table-compras-sae', [valor.Empresa, valor.Referencia, valor.Proyecto, valor.Observaciones, valor.OC, valor.Fecha, valor.Clave, valor.Articulo, valor.Linea, valor.Cantidad, valor.Precio, valor.Total], true);
            });
            $.each(respuesta.datos.existencias, function (key, valor) {
                tabla.agregarFila('#data-table-existencias-sae', [valor.Clave, valor.Articulo, valor.Almacen, valor.Existencias], true);
            });
            $.each(respuesta.datos.movimientos, function (key, valor) {
                tabla.agregarFila('#data-table-movimientos-sae', [valor.Numero_Movimiento, valor.Folio, valor.Clave_Producto, valor.Articulo, valor.Almacen, valor.Concepto, valor.Movimiento, valor.Referencia, valor.Cantidad, valor.Costo, valor.Costo_Promo_Inicial, valor.Costo_Promo_Final, valor.Unidad_Venta, valor.Existencia, valor.Fecha, valor.MOV_ENLAZADO], true);
            });

            $('#btnRegresarBusquerdaReporteComprasSAE').off('click');
            $("#btnRegresarBusquerdaReporteComprasSAE").on("click", function () {
                $("#seccionReporteComprasSAE").addClass("hidden");
                $("#seccion-compras-SAE").removeClass("hidden");
            });

            $('#btnGeneraPdfReportesComprasSAE').off('click');
            $("#btnGeneraPdfReportesComprasSAE").on("click", function () {
                var compras = $('#data-table-compras-sae').DataTable().rows({search: 'applied'}).data();
                var existencias = $('#data-table-existencias-sae').DataTable().rows({search: 'applied'}).data();
                var movimientos = $('#data-table-movimientos-sae').DataTable().rows({search: 'applied'}).data();
                var realCompras = new Array();
                var realExistencias = new Array();
                var realMovimientos = new Array();
                $.each(compras, function (k, v) {
                    if (!isNaN(k)) {
                        realCompras.push(v);
                    }
                });
                $.each(existencias, function (k, v) {
                    if (!isNaN(k)) {
                        realExistencias.push(v);
                    }
                });
                $.each(movimientos, function (k, v) {
                    if (!isNaN(k)) {
                        realMovimientos.push(v);
                    }
                });
                var data = {
                    compras: realCompras,
                    existencias: realExistencias,
                    movimientos: realMovimientos,
                };

                evento.enviarEvento('Compras/exportaReporteComprasSAE', data, '#seccionReporteComprasSAE', function (respuesta) {
                    window.open(respuesta.ruta, '_blank');
                });
            });
        });
    };

    fecha.rangoFechas('#desdeComprasSAE', '#hastaComprasSAE');
});