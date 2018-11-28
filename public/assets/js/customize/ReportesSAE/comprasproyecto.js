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
        var desde = $("#txtDesdeComprasSAE").val();
        var hasta = $("#txtHastaComprasSAE").val();
        var valor = $("#valorFiltroclaves").tagit("assignedTags");
        if (valor.length > 0) {
            var valorBuscar = valor.toString();
        } else {
            evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debes definir al menos una palabra clave.', 3000);
            return false;
        }
        if (desde !== '') {
            if (hasta !== '') {
                var data = {desde: desde, hasta: hasta, claves: valorBuscar};
                mostrarReporteCompras(data);
            } else {
                evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Hasta.', 3000);
            }
        } else {
            evento.mostrarMensaje('.errorMostrarReporteComprasSAE', false, 'Debe llenar el campo Desde.', 3000);
        }
    });

    var mostrarReporteCompras = function (data) {
        evento.enviarEvento('Compras/mostrarReporteComprasSAEProyecto', data, '#seccion-compras-SAE', function (respuesta) {
            $('#seccion-compras-SAE').addClass('hidden');
            $('#seccionReporteComprasSAE').removeClass('hidden').empty().append(respuesta.formulario);
            tabla.generaTablaPersonal('#data-table-compras-sae', null, null, true, true);
            tabla.limpiarTabla('#data-table-compras-sae');
            $.each(respuesta.datos.compras, function (key, valor) {
                tabla.agregarFila('#data-table-compras-sae', [valor.OC, valor.Proveedor, valor.Referencia, valor.FechaDocumento, valor.FechaCancelacion, valor.FechaElaboracion, valor.TotalCompra, valor.Impuesto, valor.Descuento, valor.Importe, valor.Proyecto, valor.CAMPLIB2, valor.NumeroPartida, valor.ClaveArticulo, valor.Articulo, valor.Cantidad, valor.PrecioUnitario, valor.Moneda, valor.TipoCambio, valor.TotalPartida], true);
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

                evento.enviarEvento('Compras/exportaReporteComprasSAEProyecto', data, '#seccionReporteComprasSAE', function (respuesta) {
                    window.open(respuesta.ruta, '_blank');
                });
            });
        });
    };

    fecha.rangoFechas('#desdeComprasSAE', '#hastaComprasSAE');
});
