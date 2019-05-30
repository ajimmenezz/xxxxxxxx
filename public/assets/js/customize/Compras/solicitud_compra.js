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
    tabla.generaTablaPersonal('#data-table-sae-products', null, null, true, true, [[1, 'asc']]);
    tabla.generaTablaPersonal('#data-table-productos-solicitados', null, null, true, true, [[1, 'asc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    file.crearUpload('#archivosSolicitud', 'Compras/SolicitarCompra', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'xml', 'msg']);

    $("#listClientes").on("change", function () {
        $("#listProyectos").empty().append('<option value="">Selecciona . . .</option>');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('/Gapsi/Gasto/ProyectosByCliente', datos, '#panelFormularioSolicitarCompra', function (respuesta) {
                $.each(respuesta.proyectos, function (k, v) {
                    $("#listProyectos").append('<option data-tipo="' + v.Tipo + '" value="' + v.ID + '">' + v.Tipo + ' - ' + v.Nombre + '</option>')
                });
                $("#listProyectos").removeAttr("disabled");
            });
            select.cambiarOpcion("#listProyectos", '');
        } else {
            $("#listProyectos").attr("disabled", "disabled");
            select.cambiarOpcion("#listProyectos", '');
        }
    });

    $("#listProyectos").on("change", function () {
        $("#listSucursales").empty().append('<option value="">Selecciona . . .</option>');
        if ($(this).val() !== '') {
            var datos = {
                'id': $(this).val()
            }
            evento.enviarEvento('/Gapsi/Gasto/SucursalesByProyecto', datos, '#panelFormularioSolicitarCompra', function (respuesta) {
                $.each(respuesta.sucursales, function (k, v) {
                    $("#listSucursales").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                });
                $("#listSucursales").removeAttr("disabled");
            });
            select.cambiarOpcion("#listSucursales", '');
            $("#listTipoBeneficiario").removeAttr("disabled");
            select.cambiarOpcion("#listTipoBeneficiario", '');
        } else {
            $("#listSucursales").attr("disabled", "disabled");
            select.cambiarOpcion("#listSucursales", '');
            $("#listTipoBeneficiario").attr("disabled", "disabled");
            select.cambiarOpcion("#listTipoBeneficiario", '');
        }
    });

    $('#data-table-sae-products tbody').on('click', 'tr', function () {
        var _fila = this;
        var datos = $('#data-table-sae-products').DataTable().row(this).data();
        var _html = `
        <div class="row">
            <div class="col-md-12">
                <p class="f-s-15 f-w-500"><strong>Clave</strong>: "`+ datos[0] + `"</p>
                <p class="f-s-15 f-w-500"><strong>Producto</strong>: "`+ datos[1] + `"</p>
                <p class="f-s-15 f-w-500"><strong>Cantidad</strong>: <input id="cantidadProducto" type="number" min=1 value="" /></p>
            </div>
        </div>`;

        evento.iniciarModal("#modalEdit", "Agregar producto", _html);

        setTimeout(function () { $("#cantidadProducto").blur().focus(); }, 600);

        $('#cantidadProducto').keyup(function (e) {
            if (e.keyCode == 13) {
                $("#btnGuardarCambios").trigger("click");
            }
        });

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            var cantidad = $.trim($("#cantidadProducto").val());
            if (isNaN(cantidad) || cantidad <= 0) {
                evento.mostrarMensaje("#errorModal", false, "La cantidad debe ser númerica y mayor a 0", 3000);
            } else {
                var datosFila = [datos[0], datos[1], cantidad];
                evento.terminarModal("#modalEdit");
                tabla.agregarFila("#data-table-productos-solicitados", datosFila);
                tabla.eliminarFila("#data-table-sae-products", _fila);
                tabla.reordenarTabla("#data-table-sae-products", [1, 'asc']);
            }
        });

    });

    $('#data-table-productos-solicitados tbody').on('click', 'tr', function () {
        var _fila = this;
        var datos = $('#data-table-productos-solicitados').DataTable().row(this).data();
        var _html = `
        <div class="row">
            <div class="col-md-12">
                <p class="f-s-15 f-w-500"><strong>Clave</strong>: "`+ datos[0] + `"</p>
                <p class="f-s-15 f-w-500"><strong>Producto</strong>: "`+ datos[1] + `"</p>
                <p class="f-s-15 f-w-500"><strong>Cantidad</strong>: <input id="cantidadProductoSolicitado" type="number" min=0 value="`+ datos[2] + `" /></p>
                <a class="btn btn-danger" id="btnQuitarProducto">Quitar producto</a>
            </div>
        </div>`;

        evento.iniciarModal("#modalEdit", "Producto solicitado", _html);

        setTimeout(function () { $("#cantidadProductoSolicitado").blur().focus().select(); }, 600);

        $('#cantidadProductoSolicitado').keyup(function (e) {
            if (e.keyCode == 13) {
                $("#btnGuardarCambios").trigger("click");
            }
        });

        $("#btnGuardarCambios").off("click");
        $("#btnGuardarCambios").on("click", function () {
            var cantidad = $.trim($("#cantidadProductoSolicitado").val());
            var datosFila = [datos[0], datos[1], cantidad];
            if (isNaN(cantidad)) {
                evento.mostrarMensaje("#errorModal", false, "La cantidad debe ser númerica", 3000);
            } else if (cantidad <= 0) {
                eliminarProductoSolicitado(_fila, [datos[0], datos[1]]);
            } else {
                evento.terminarModal("#modalEdit");
                tabla.eliminarFila("#data-table-productos-solicitados", _fila);
                tabla.agregarFila("#data-table-productos-solicitados", datosFila);
                tabla.reordenarTabla("#data-table-productos-solicitados", [1, 'asc']);
            }
        });

        $("#btnQuitarProducto").off("click");
        $("#btnQuitarProducto").on("click", function () {
            var datosFila = [datos[0], datos[1]];
            eliminarProductoSolicitado(_fila, datosFila);
        });

    });

    function eliminarProductoSolicitado(fila, datosFila) {
        evento.terminarModal("#modalEdit");
        tabla.eliminarFila("#data-table-productos-solicitados", fila);
        tabla.agregarFila("#data-table-sae-products", datosFila);
        tabla.reordenarTabla("#data-table-sae-products", [1, 'asc']);
    }

    $("#brnSolicitarCompra").off("click");
    $("#brnSolicitarCompra").on("click", function () {
        var datosCompra = {
            'idCliente': $("#listClientes").val(),
            'cliente': $("#listClientes option:selected").text(),
            'idProyecto': $("#listProyectos").val(),
            'proyecto': $("#listProyectos option:selected").text(),
            'idSucursal': $("#listSucursales").val(),
            'sucursal': $("#listSucursales option:selected").text(),
            'archivos': $("#archivosSolicitud")[0].files.length,
            'observaciones': $.trim($("#txtObservacionesSolicitud").val()),
            'partidas': ''
        };

        var dataTable = tabla.getTableData("#data-table-productos-solicitados");
        var partidas = '[';

        $.each(dataTable, function (k, v) {
            partidas += '{"cve":"' + v[0] + '"';
            partidas += ',"producto":"' + v[1] + '"';
            partidas += ',"cantidad":"' + v[2] + '"},';
        });
        if (partidas != '[') {
            partidas = partidas.slice(0, -1);
        }
        partidas += ']';

        datosCompra.partidas = partidas;

        if (datosCompra.idCliente == "") {
            evento.mostrarMensaje("#errorFormulario", false, "El Campo cliente es obligatorio", 4000);
            return false;
        }

        if (datosCompra.idProyecto == "") {
            evento.mostrarMensaje("#errorFormulario", false, "El Campo Proyecto es obligatorio", 4000);
            return false;
        }

        if (datosCompra.idSucursal == "") {
            evento.mostrarMensaje("#errorFormulario", false, "El Campo Sucursal es obligatorio", 4000);
            return false;
        }

        if (datosCompra.partidas == '[]') {
            evento.mostrarMensaje("#errorFormulario", false, "Debe seleccionar al menos un producto para solicitar la compra", 4000);
            return false;
        }

        if (datosCompra.observaciones == "") {
            evento.mostrarMensaje("#errorFormulario", false, "El Campo Observaciones es obligatorio", 4000);
            return false;
        }

        if (datosCompra.archivos <= 0) {
            evento.mostrarMensaje("#errorFormulario", false, "Debe adjuntar al menos un archivo.", 4000);
            return false;
        }

        file.enviarArchivos('#archivosSolicitud', 'Compras/SolicitarCompra', '#panelFormularioSolicitarCompra', datosCompra, function (respuesta) {
            if (respuesta.code == 200) {
                evento.mostrarMensaje("#errorFormulario", true, "Se está trabajando en la autorización de la compra. Puede dar seguimiento en la sección \"Compras\"->\"Mis Solicitudes\"", 6000);
                $("#listClientes").attr("disabled", "disabled");
                $("#listProyectos").attr("disabled", "disabled");
                $("#listSucursales").attr("disabled", "disabled");
                $("#txtObservacionesSolicitud").attr("disabled", "disabled");
                file.deshabilitar("#archivosSolicitud");
                setTimeout(function () {
                    location.reload();
                }, 5000);
            } else if (respuesta.code == 500) {
                evento.mostrarMensaje("#errorFormulario", false, respuesta.message, 4000);
                setTimeout(function () {
                    location.reload();
                }, 10000);
            } else {
                evento.mostrarMensaje("#errorFormulario", false, "Ocurrió un error al solicitar la compra. Por favor recargue su página y vuelva a intentarlo.", 4000);
            }
        });
    });

});
