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
            eventosFormulario(respuesta);
        });
    });

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-compras tbody').on('click', 'tr', function () {

    });

    var cargarSeccionOrdenCompra = function () {
        var respuesta = arguments[0];
        $('#listaCompras').addClass('hidden');
        $('#seccionFormularioOrdenCompra').removeClass('hidden').empty().append(respuesta.formulario);
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
        select.crearSelect('#selectProductoPartida0');
        select.crearSelect('#selectUnidadPartida0');
        calendario.crearFecha('.calendario');
        $('[data-toggle="tooltip"]').tooltip();
        tabla.generaTablaPersonal(
                '#data-table-partidas-oc',
                null,
                null,
                true,
                false,
                null,
                null,
                null,
                false);
        select.cambiarOpcion('#selectOrdenOrdenCompra', 'Directa');
    }

    var eventosFormulario = function () {
        var respuesta = arguments[0];
        var productos = respuesta.datos.productos;
        var timer;

        mostrarDireccionSelect('#selectProveedorOrdenCompra', '#iconoInformacionProveedor', 'Dirección del proveedor ...');
        mostrarDireccionSelect('#selectAlmacenOrdenCompra', '#iconoInformacionAlmacen', 'Dirección de almacen ...');

        $('#btnAgregarPartidaFila').on('click', function () {
            var datosTablaPartidasOC = $('#data-table-partidas-oc').DataTable().rows().data();
            var numeroFila = datosTablaPartidasOC.length;

            tabla.agregarFilaHtml('#data-table-partidas-oc', datosNuevaPartida(productos, numeroFila));
            select.crearSelect('#selectProductoPartida' + numeroFila);
            select.crearSelect('#selectUnidadPartida' + numeroFila);
            $('#mensajeEliminarFila').removeClass('hidden');
            eventosTablaPartida(numeroFila);
        });

        eventosTablaPartida('0');

        $("#selectClienteOrdenCompra").on("change", function () {
            $("#selectProyectoOrdenCompra").empty().append('<option value="">Seleccionar...</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('/Gapsi/Gasto/ProyectosByCliente', datos, '#panelFormularioOrdenesDeCompra', function (respuesta) {
                    $.each(respuesta.proyectos, function (k, v) {
                        $("#selectProyectoOrdenCompra").append('<option data-tipo="' + v.Tipo + '" value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#selectProyectoOrdenCompra").removeAttr("disabled");
                });
                select.cambiarOpcion("#selectProyectoOrdenCompra", '');
            } else {
                $("#selectProyectoOrdenCompra").attr("disabled", "disabled");
                select.cambiarOpcion("#selectProyectoOrdenCompra", '');
            }
        });

        $("#selectProyectoOrdenCompra").on("change", function () {
            $("#selectSucursalOrdenCompra").empty().append('<option value="">Seleccionar...</option>');
            if ($(this).val() !== '') {
                var datos = {
                    'id': $(this).val()
                }
                evento.enviarEvento('Compras/MostrarDatosSucursalesBeneficiarios', datos, '#panelFormularioOrdenesDeCompra', function (respuesta) {
                    $.each(respuesta.sucursales.sucursales, function (k, v) {
                        $("#selectSucursalOrdenCompra").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#selectSucursalOrdenCompra").removeAttr("disabled");
                    $.each(respuesta.beneficiarios.beneficiarios, function (k, v) {
                        $("#selectBeneficiarioOrdenCompra").append('<option value="' + v.ID + '">' + v.Nombre + '</option>')
                    });
                    $("#selectBeneficiarioOrdenCompra").removeAttr("disabled");
                });
                select.cambiarOpcion("#selectSucursalOrdenCompra", '');
                select.cambiarOpcion("#selectBeneficiarioOrdenCompra", '');
            } else {
                $("#selectSucursalOrdenCompra").attr("disabled", "disabled");
                select.cambiarOpcion("#selectSucursalOrdenCompra", '');
                $("#selectBeneficiarioOrdenCompra").attr("disabled", "disabled");
                select.cambiarOpcion("#selectBeneficiarioOrdenCompra", '');
            }
        });

        $('#data-table-partidas-oc').on("mousedown", "tr", function () {
            var _this = this;
            timer = setTimeout(function () {
                var datosTablaPartidasOC = $('#data-table-partidas-oc').DataTable().rows().data();
                var numeroFila = datosTablaPartidasOC.length;
                if (numeroFila > 1) {
                    if (tabla.validarClickRenglon('#data-table-partidas-oc')) {
                        tabla.eliminarFila('#data-table-partidas-oc', _this);
                        var datosTablaPartidasOC2 = $('#data-table-partidas-oc').DataTable().rows().data();
                        var numeroFila2 = datosTablaPartidasOC2.length;

                        if (numeroFila2 === 1) {
                            $('#mensajeEliminarFila').addClass('hidden');
                        }
                    }
                } else {
                    evento.mostrarMensaje('.errorTablaPartida', false, 'Debe de haber por lo menos una fila.', 4000);
                }
            }, 2 * 1000);
        }).on("mouseup mouseleave", function () {
            clearTimeout(timer);
        });

        $('#btnGuardarOC').on('click', function () {
            var camposFormularioValidados = evento.validarCamposObjetos(arrayCamposFormulario(), '#errorGuardarOC');
            var camposTablaValidados = evento.validarCamposObjetos(arrayCamposTablaPartidas(), '#errorGuardarOC');

            if (camposTablaValidados && camposFormularioValidados) {
                var data = valorCamposFormulario(respuesta.datos.claveNuevaDocumentacion);
                var fecha = $('#inputFechaOrdenCompra').val();
                var fechaRec = $('#inputFechaRecOrdenCompra').val();
                if (fecha <= fechaRec) {
                    evento.enviarEvento('Compras/GuardarOrdenCompra', data, '#panelFormularioOrdenesDeCompra', function (respuesta) {
                        if (respuesta) {
                            evento.mensajeConfirmacion('Se genero correctamente la Orden de Compra', 'Correcto');
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorGuardarOC', false, 'El campo fecha de recolección debe ser mayor o igual al campo fecha.', 5000);
                }
            }
        });

        $('#selectMonedaOrdenCompra').on("change", function () {
            var tipoCambio = $('#selectMonedaOrdenCompra option:selected').data('tipo-cambio');
            $('#inputTipoCambioOrdenCompra').val(tipoCambio);
        });

        $('#btnRegresarOrdenesCompra').on('click', function () {
            mostrarResumenOrdenesCompra();
        });
    }

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
        var productos = arguments[0];
        var numeroFila = arguments[1];
        var nuevaFila = '<tr role="row">';
        nuevaFila += '<td><div id="partidaClave' + numeroFila + '"</td>\n\
                        <td>\n\
                            <select id="selectProductoPartida' + numeroFila + '" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>';
        $.each(productos, function (index, value) {
            nuevaFila += '<option data-costo-unidad="' + value.COSTO_UNIDAD + '" data-unidad1="' + value.UNI_MED + '" data-unidad2="' + value.UNI_ALT + '" value="' + value.CVE_ART + '">' + value.DESCR + '</option>';
        });
        nuevaFila += '</select>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <select id="selectUnidadPartida' + numeroFila + '" class="form-control" style="width: 100%" data-parsley-required="true">\n\
                                <option value="">Seleccionar...</option>\n\
                            </select>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input id="cantidad' + numeroFila + '" type="number" class="form-control" value="0.0000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input id="descuento' + numeroFila + '" type="number" class="form-control" value="0.0000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input id="costoUnidad' + numeroFila + '" type="number" class="form-control" value="0.000000" min="0"/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <input id="subtotalPartida' + numeroFila + '" type="number" class="form-control" value="0.00" min="0" disabled/>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <textarea id="textAreaObservacionesPartida' + numeroFila + '" class="form-control"  rows="3" ></textarea>\n\
                        </td>\n\
                        <td class="text-center">\n\
                            <div class="alert alert-warning fade in m-b-15">\n\
                               Para guardar las Observaciones de la fila debe estar el campo visible.\n\
                            </div>\n\
                        </td>';
        nuevaFila += "</tr>";
        return nuevaFila;
    }

    var eventosTablaPartida = function () {
        var numeroFila = arguments[0];

        $('#selectProductoPartida' + numeroFila).on("change", function () {
            var clave = $(this).val();
            eventoProductoPartida(numeroFila, clave);
        });

        $('#cantidad' + numeroFila).on("change", function () {
            var cantidad = $(this).val();
            eventoCantidadPartida(numeroFila, cantidad);
        });

        $('#costoUnidad' + numeroFila).on("change", function () {
            var costoUnidad = $(this).val();
            eventoCostoUnidadPartida(numeroFila, costoUnidad);
        });

    }

    var eventoProductoPartida = function () {
        var numeroFila = arguments[0];
        var clave = arguments[1];

        if (clave !== '') {
            var costoUnidad = $('#selectProductoPartida' + numeroFila + ' option:selected').data('costo-unidad');
            var unidad1 = $('#selectProductoPartida' + numeroFila + ' option:selected').data('unidad1');
            var unidad2 = $('#selectProductoPartida' + numeroFila + ' option:selected').data('unidad2');
            var cantidad = $('#inputDescuentoOrdenCompra').val();

            $('#partidaClave' + numeroFila).empty().html(clave);
            $('#selectUnidadPartida' + numeroFila).empty().append('<option value="">Seleccionar...</option>');
            select.cambiarOpcion('#selectUnidadPartida' + numeroFila, '0');
            $("#selectUnidadPartida" + numeroFila).append('<option value="' + unidad1 + '">' + unidad1 + '</option>');
            $("#selectUnidadPartida" + numeroFila).append('<option value="' + unidad1 + '">' + unidad2 + '</option>');
            $('#cantidad' + numeroFila).val(1);
            $('#costoUnidad' + numeroFila).val(costoUnidad);
            $('#subtotalPartida' + numeroFila).val(costoUnidad);
            $('#descuento' + numeroFila).val(cantidad);
        } else {
            $('#partidaClave' + numeroFila).empty().html('');
            $('#selectUnidadPartida' + numeroFila).empty().append('<option value="">Seleccionar...</option>');
            select.cambiarOpcion('#selectUnidadPartida' + numeroFila, '0');
            $('#cantidad' + numeroFila).val(0);
            $('#costoUnidad' + numeroFila).val(0.000000);
            $('#subtotalPartida' + numeroFila).val(0.00);
            $('#descuento' + numeroFila).val(0.0000);
        }
    }

    var eventoCantidadPartida = function () {
        var numeroFila = arguments[0];
        var cantidad = arguments[1];
        var costoUnidadAnterior = $('#selectProductoPartida' + numeroFila + ' option:selected').data('costo-unidad');
        var costoUnidad = (cantidad * costoUnidadAnterior);

        $('#costoUnidad' + numeroFila).val(costoUnidadAnterior);
        $('#subtotalPartida' + numeroFila).val(costoUnidad);
    }

    var eventoCostoUnidadPartida = function () {
        var numeroFila = arguments[0];
        var costoUnidad = arguments[1];
        var cantidad = $('#cantidad' + numeroFila).val();
        var subtotal = (costoUnidad * cantidad);

        $('#subtotalPartida' + numeroFila).val(subtotal);
    }

    var arrayCamposFormulario = function () {
        var arrayCampos = [
            {'objeto': '#selectOrdenOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Orden.'},
            {'objeto': '#inputFechaOrdenCompra', 'mensajeError': 'Falta seleccionarel campo Fecha.'},
            {'objeto': '#inputFechaRecOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Fecha Recolección.'},
            {'objeto': '#selectProveedorOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Proveedor.'},
            {'objeto': '#inputReferenciaOrdenCompra', 'mensajeError': 'Falta escribir el campo Referencia Proveedor.'},
            {'objeto': '#selectEsquemaOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Esquema.'},
            {'objeto': '#inputDescuentoOrdenCompra', 'mensajeError': 'Falta escribir el campo Descuento.'},
            {'objeto': '#inputDescuentoFinancieroOrdenCompra', 'mensajeError': 'Falta escribir el campo Descuento Financiero.'},
            {'objeto': '#inputEntregaAOrdenCompra', 'mensajeError': 'Falta escribir el campo Entrega a.'},
            {'objeto': '#inputDireccionEntregaOrdenCompra', 'mensajeError': 'Falta escribir el campo Dirección de entrega.'},
            {'objeto': '#selectAlmacenOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Almacén.'},
            {'objeto': '#selectMonedaOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Moneda.'},
            {'objeto': '#inputTipoCambioOrdenCompra', 'mensajeError': 'Falta escribir el campo Tipo de cambio.'},
            {'objeto': '#textAreaObservacionesOrdenCompra', 'mensajeError': 'Falta escribir el campo Observaciones del Documento.'},
            {'objeto': '#selectClienteOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Cliente'},
            {'objeto': '#selectProyectoOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Proyecto.'},
            {'objeto': '#selectSucursalOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Sucursal.'},
            {'objeto': '#selectTipoServicioOrdenCompra', 'mensajeError': 'Falta seleccionar el campo Tipo de Servicio.'},
            {'objeto': '#selectBeneficiarioOrdenCompra', 'mensajeError': 'Falta seleccionarel campo Beneficiario.'}
        ];

        return arrayCampos;
    }

    var arrayCamposTablaPartidas = function () {
        var datosTablaPartidasOC = $('#data-table-partidas-oc').DataTable().rows().data();
        var arrayCamposTablaPartidas = [];

        $.each(datosTablaPartidasOC, function (k, v) {
            arrayCamposTablaPartidas.push({'objeto': '#selectProductoPartida' + k, 'mensajeError': 'Falta seleccionar el campo Producto de la Tabla Partidas.'});
            arrayCamposTablaPartidas.push({'objeto': '#selectUnidadPartida' + k, 'mensajeError': 'Falta seleccionar el campo Unidad de la Tabla Partidas.'});
            arrayCamposTablaPartidas.push({'objeto': '#cantidad' + k, 'mensajeError': 'Falta colocar la Unidad de la Tabla Partidas.'});
        });

        return arrayCamposTablaPartidas;
    }

    var valorCamposFormulario = function () {
        var orden = $('#selectOrdenOrdenCompra').val();
        var fecha = $('#inputFechaOrdenCompra').val();
        var fechaRec = $('#inputFechaRecOrdenCompra').val();
        var proveedor = $('#selectProveedorOrdenCompra').val();
        var referencia = $('#inputReferenciaOrdenCompra').val();
        var esquema = $('#selectEsquemaOrdenCompra').val();
        var descuento = $('#inputDescuentoOrdenCompra').val();
        var descuentoFinanciero = $('#inputDescuentoFinancieroOrdenCompra').val();
        var entregaA = $('#inputEntregaAOrdenCompra').val();
        var direccionEntrega = $('#inputDireccionEntregaOrdenCompra').val();
        var almacen = $('#selectAlmacenOrdenCompra').val();
        var moneda = $('#selectMonedaOrdenCompra').val();
        var tipoCambio = $('#inputTipoCambioOrdenCompra').val();
        var observaciones = $('#textAreaObservacionesOrdenCompra').val();
        var cliente = $('#selectClienteOrdenCompra').val();
        var proyecto = $('#selectProyectoOrdenCompra').val();
        var sucursal = $('#selectSucursalOrdenCompra').val();
        var tipoServicio = $('#selectTipoServicioOrdenCompra').val();
        var beneficiario = $('#selectBeneficiarioOrdenCompra').val();
        var claveOrdenCompra = $('#inputClaveOrdenCompra').val();
        var datosTablaPartidasOC = arrayValoresCamposTabla();
        var claveNuevaDocumentacion = arguments[0];
        var folio = $('#inputClaveOrdenCompra').data('ultimo-documento');
        var textoProyectoGapsi = $('#selectProyectoOrdenCompra option:selected').text();
        var textoBeneficiario = $('#selectBeneficiarioOrdenCompra option:selected').text();
        var textoTipoServicio = $("#selectTipoServicioOrdenCompra option:selected").text();
        var tipo = $("#selectProyectoOrdenCompra option:selected").attr("data-tipo");

        var data = {
            'claveNuevaDocumentacion': claveNuevaDocumentacion,
            'orden': orden,
            'fecha': fecha,
            'fechaRec': fechaRec,
            'proveedor': proveedor,
            'referencia': referencia,
            'esquema': esquema,
            'descuento': descuento,
            'descuentoFinanciero': descuentoFinanciero,
            'entregaA': entregaA,
            'direccionEntrega': direccionEntrega,
            'almacen': almacen,
            'moneda': moneda,
            'tipoCambio': tipoCambio,
            'observaciones': observaciones,
            'cliente': cliente,
            'proyecto': proyecto,
            'sucursal': sucursal,
            'tipoServicio': tipoServicio,
            'beneficiario': beneficiario,
            'claveOrdenCompra': claveOrdenCompra,
            'folio': folio,
            'datosTabla': datosTablaPartidasOC,
            'textoProyectoGapsi': textoProyectoGapsi,
            'textoBeneficiario': textoBeneficiario,
            'tipo': tipo,
            'textoTipoServicio': textoTipoServicio
        }

        return data;
    }

    var arrayValoresCamposTabla = function () {
        var datosTablaPartidasOC = $('#data-table-partidas-oc').DataTable().rows().data();
        var arrayValoresCamposTabla = [];

        $.each(datosTablaPartidasOC, function (k, v) {
            arrayValoresCamposTabla[k] = ({
                'producto': $('#selectProductoPartida' + k).val(),
                'unidad': $('#selectUnidadPartida' + k).val(),
                'cantidad': $('#cantidad' + k).val(),
                'descuento': $('#descuento' + k).val(),
                'costoUnidad': $('#costoUnidad' + k).val(),
                'subtotalPartida': $('#subtotalPartida' + k).val(),
                'observacionesPartida': $('#textAreaObservacionesPartida' + k).val()
            });
        });

        return arrayValoresCamposTabla;
    }

    var mostrarResumenOrdenesCompra = function () {
        $('#listaCompras').removeClass('hidden');
        $('#seccionFormularioOrdenCompra').addClass('hidden');
    }

});