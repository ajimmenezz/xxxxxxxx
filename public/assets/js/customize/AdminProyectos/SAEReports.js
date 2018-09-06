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

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-almacenes', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-almacenes tbody').on('click', 'tr', function () {
        var datos = $('#data-table-almacenes').DataTable().row(this).data();
        var data = {'almacen': datos[0]};
        evento.enviarEvento('SAEReports/getInventarioAlmacen', data, '#panelListaAlmacenes', function (respuesta) {
            tabla.limpiarTabla('#data-table-inventario');
            $.each(respuesta, function (key, item) {
                tabla.agregarFila('#data-table-inventario', [item.Clave, item.Producto, item.Linea, item.Unidad, item.Existencia,item.Costo]);
            });
            $("#nombreAlmacen").empty().append(datos[1]);
            $("#panelListaAlmacenes").addClass('hidden');
            $("#panelInventarioAlmacen").removeClass('hidden');
        });
    });

    $("#btnRegresarSeguimiento").on("click", function () {
        tabla.limpiarTabla('#data-table-inventario');
        $("#panelListaAlmacenes").removeClass('hidden');
        $("#panelInventarioAlmacen").addClass('hidden');
    });

    $("#btnExportarExcel").on("click", function () {
        var info = $('#data-table-inventario').DataTable().rows({search: 'applied'}).data();
        var realInfo = new Array();
        $.each(info, function(k,v){
            if(!isNaN(k)){
                realInfo.push(v);
            }
        });
        var data = {
            info: realInfo,
            almacen: $("#nombreAlmacen").html()
        };       
        evento.enviarEvento('SAEReports/exportaInventarioAlmacen', data, '#panelInventarioAlmacen', function (respuesta) {
            window.open(respuesta.ruta, '_blank');
        });
    });
});
