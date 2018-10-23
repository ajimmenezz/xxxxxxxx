$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var calendario = new Fecha();
    var servicios = new Servicio();
    var botones = new Botones();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-ordenes-compra', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('#btnAgregarOrdenCompra').off('click');
    $('#btnAgregarOrdenCompra').on('click', function () {
        evento.enviarEvento('Compras/MostrarFormularioOrdenCompra', {}, '#panelOrdenesDeCompra', function (respuesta) {
            cargarSeccionOrdenCompra(respuesta);
            cargarObjetosFormulario();
            eventosFormulario();
        });
    });

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-compras tbody').on('click', 'tr', function () {

    });

    var cargarSeccionOrdenCompra = function () {
        var respuesta = arguments[0];
        $('#listaCompras').addClass('hidden');
        $('#seccionFormularioOrdenCompra').removeClass('hidden').empty().append(respuesta.formulario);
//        $('#btnRegresarFacturacionTesoreria').removeClass('hidden');

    }

    var cargarObjetosFormulario = function () {
        select.crearSelect('#selectOrdenOrdenCompra');
        select.crearSelect('#selectProveedorOrdenCompra');
        select.crearSelect('#selectEsquemaOrdenCompra');
        select.crearSelect('#selectAlmacenOrdenCompra');
        select.crearSelect('#selectMonedaOrdenCompra');
        select.crearSelect('#selectClienteOrdenCompra');
        select.crearSelect('#selectProyectoOrdenCompra');
        select.crearSelect('#selectSucursalOrdenCompra');
        select.crearSelect('#selectTipoServicioOrdenCompra');
        select.crearSelect('#selectBeneficiarioOrdenCompra');
        calendario.crearFecha('.calendario');
        $('[data-toggle="tooltip"]').tooltip();
//        tabla.generaTablaPersonal('#data-table-partidas-oc', null, null, true, true);
    }

    var eventosFormulario = function () {
        mostrarDireccionSelect('#selectProveedorOrdenCompra', '#iconoInformacionProveedor', 'Dirección del proveedor ...');
        mostrarDireccionSelect('#selectAlmacenOrdenCompra', '#iconoInformacionAlmacen', 'Dirección de almacen ...');

        $('#btnAgregarPartidaFila').on('click', function () {
            $("#data-table-partidas-oc").append(datosNuevaPartida());
        });

    }

    var recargandoTablaCompras = function (informacionServicio) {
//        tabla.limpiarTabla('#data-table-ordenes-compra');
//        $.each(informacionServicio.serviciosAsignados, function (key, item) {
//            tabla.agregarFila('#data-table-compras', [item.Id, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
//        });
    };

    var mostrarDireccionSelect = function () {
        var objeto = arguments[0];
        var iconoInformacion = arguments[1];
        var texto = arguments[2];
        $(objeto).on("change", function () {
            var direccion = $(objeto + ' option:selected').data('direccion');

            if (direccion !== undefined) {
                $(iconoInformacion).attr('data-original-title', direccion);
            } else {
                $(iconoInformacion).attr('data-original-title', texto);
            }
        });
    }
    var datosNuevaPartida = function () {
        var nuevaFila = "<tr>";
        nuevaFila += '<td>PD-PURGAMO4BU-CG</td>\n\
                        <td>Cable UTP Cat 6A Pares Azul</td>\n\
                        <td class="text-center">\n\
                            <input type="number" class="form-control cantidad-viaticos-outsourcing" value="0.0000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input type="number" class="form-control cantidad-viaticos-outsourcing" value="0.0000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input type="number" class="form-control cantidad-viaticos-outsourcing" value="0.0000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input type="number" class="form-control cantidad-viaticos-outsourcing" value="0.0000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input type="number" class="form-control cantidad-viaticos-outsourcing" value="0.0000" min="0"/>\n\
                        </td>';
        nuevaFila += "</tr>";
        return nuevaFila;
    }
});