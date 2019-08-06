$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var tabla = new Tabla();
    var fecha = new Fecha();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de lineas
    tabla.generaTablaPersonal('#data-table-almacenes', null, null, true);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    $('#btnAgregarAlmacen').on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioAlmacen', '', '#seccionAlmacenes', function (respuesta) {
            $('#listaAlmacenes').addClass('hidden');
            $('#formularioAlmacen').removeClass('hidden').empty().append(respuesta.formulario);
            select.crearSelect('select');
            //Evento que genera un nueva linea
            $('#btnNuevoAlmacen').on('click', function () {
                var nombre = $('#inputNombreAlmacen').val();
                var responsable = $('#listResponsableAlmacen').val();
                var activacion;
                var data = { nombre: nombre, responsable: responsable };
                if (evento.validarFormulario('#formNuevoAlmacen')) {
                    evento.enviarEvento('Catalogo/NuevoAlmacen', data, '#seccionAlmacenes', function (respuesta) {
                        if (respuesta instanceof Array || respuesta instanceof Object) {
                            tabla.limpiarTabla('#data-table-almacenes');
                            $.each(respuesta, function (key, valor) {
                                if (valor.Flag === '1') {
                                    activacion = 'Activo';
                                } else {
                                    activacion = 'Inactivo';
                                }
                                tabla.agregarFila('#data-table-almacenes', [valor.Id, valor.Nombre, valor.Tipo, activacion]);
                            });
                            evento.limpiarFormulario('#formNuevoAlmacen');
                            $('#formularioAlmacen').addClass('hidden');
                            $('#listaAlmacenes').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaAlmacenes', true, 'Datos insertados correctamente', 3000);
                        } else {
                            evento.mostrarMensaje('.errorAlmacen', false, 'Ya existe un almacén con el mismo nombre y responsable.', 3000);
                        }
                    });
                }
            });
            $('#btnCancelar').on('click', function () {
                $('#formularioAlmacen').empty().addClass('hidden');
                $('#listaAlmacenes').removeClass('hidden');
            });
        });
    });

    $("#btnTraspasarAlmacenes").on('click', function () {
        evento.enviarEvento('Catalogo/MostrarFormularioTraspaso', {}, '#seccionAlmacenes', function (respuesta) {
            $("#divTraspasarProducto").empty().append(respuesta.html);

            $("#divListaCatalogos").fadeOut(400, function () {
                $("#divTraspasarProducto").fadeIn(400);
                select.crearSelect('#listAlmacenOrigen');
                select.crearSelect('#listAlmacenDestino');
            });

            $("#divTraspasarProducto #btnRegresar").off("click");
            $("#divTraspasarProducto #btnRegresar").on("click", function () {
                $("#divTraspasarProducto").fadeOut(400, function () {
                    $("#divListaCatalogos").fadeIn(400, function () {
                        $("#divTraspasarProducto").empty();
                    });
                });
            });

            initTraspaso();
        });
    });

    $("#btnVerTraspasos").on('click', function () {
        evento.enviarEvento('Catalogo/MostrarTraspasos', {}, '#seccionAlmacenes', function (respuesta) {
            $("#divVerTraspasos").empty().append(respuesta.html);

            $("#divListaCatalogos").fadeOut(400, function () {
                $("#divVerTraspasos").fadeIn(400);

            });

            $("#divVerTraspasos #btnRegresar").off("click");
            $("#divVerTraspasos #btnRegresar").on("click", function () {
                $("#divVerTraspasos").fadeOut(400, function () {
                    $("#divListaCatalogos").fadeIn(400, function () {
                        $("#divVerTraspasos").empty();
                    });
                });
            });

            initVerTraspasos();
        });
    });

    $("#btnVerAltasIniciales").on('click', function () {
        evento.enviarEvento('Catalogo/MostrarAltasIniciales', {}, '#seccionAlmacenes', function (respuesta) {
            $("#divVerAltasIniciales").empty().append(respuesta.html);

            $("#divListaCatalogos").fadeOut(400, function () {
                $("#divVerAltasIniciales").fadeIn(400);

            });

            $("#divVerAltasIniciales #btnRegresar").off("click");
            $("#divVerAltasIniciales #btnRegresar").on("click", function () {
                $("#divVerAltasIniciales").fadeOut(400, function () {
                    $("#divListaCatalogos").fadeIn(400, function () {
                        $("#divVerAltasIniciales").empty();
                    });
                });
            });

            initVerAltasIniciales();
        });
    });

    $("#btnVerKitsEquipo").on('click', function () {
        verKitsEquipo('divListaCatalogos');
    });

    $("#btnDeshuesarEquipo").on('click', function () {
        verDeshuesarEquipo();
    });

    $("#btnHistorialEquipo").on("click", function () {
        evento.enviarEvento('Catalogo/MostrarFormularioHistorialEquipo', {}, '#seccionAlmacenes', function (respuesta) {
            $("#divHistorialEquipo").empty().append(respuesta.html);
            tabla.generaTablaPersonal('#data-table-movimientos', null, null, true);
            evento.cambiarDiv("#divListaCatalogos", "#divHistorialEquipo", verHistorialEquipo());
        });
    });

    function verHistorialEquipo() {
        $("#btnBuscarHistorialEquipo").off("click");
        $("#btnBuscarHistorialEquipo").on("click", function () {
            var id = $.trim($("#txtSerie").val());
            $("#divResult").hide();
            if (id !== '') {
                evento.enviarEvento('Catalogo/MostrarHistorialEquipo', { id: id }, '#panelHistorialEquipo', function (respuesta) {
                    tabla.limpiarTabla('#data-table-movimientos');
                    $.each(respuesta, function (k, v) {
                        tabla.agregarFila('#data-table-movimientos', [v.Movimiento, v.Almacen, v.TipoProducto, v.Producto, v.Serie, v.Estatus, v.Usuario, v.Fecha]);
                    });
                    $("#divResult").show();
                });
            } else {
                evento.mostrarMensaje(".divError", false, "La serie de equipo es necesaria para la generación del historial", 4000);
                tabla.limpiarTabla('#data-table-movimientos');
                $("#divResult").hide();
            }
        });
    }

    function verDeshuesarEquipo() {
        var notificacion = arguments[0];
        evento.enviarEvento('Catalogo/MostrarDeshuesarEquipo', {}, '#seccionAlmacenes', function (respuesta) {
            $("#divDeshuesarEquipo").empty().append(respuesta.html);
            tabla.generaTablaPersonal('#data-table-equipos-deshueso', null, null, true);


            $("#divListaCatalogos").fadeOut(400, function () {
                $("#divDeshuesarEquipo").fadeIn(400, function () {
                    if (typeof notificacion !== "undefined") {
                        if (notificacion == 1) {
                            evento.mostrarMensaje('#errorDeshuesar', true, 'Los componentes y sus estatus han sido agregados a su inventario.', 4000);
                        } else {
                            evento.mostrarMensaje('#errorDeshuesar', false, 'Ocurrió un error al intentar convertir el equipo a componentes. Intente de nuevo o contacte al administrador del sistema.', 4000);
                        }
                    }
                });
            });

            $("#divDeshuesarEquipo #divListaEquiposDeshuesar #btnRegresar").off("click");
            $("#divDeshuesarEquipo #divListaEquiposDeshuesar #btnRegresar").on("click", function () {
                $("#divDeshuesarEquipo").fadeOut(400, function () {
                    $("#divListaCatalogos").fadeIn(400, function () {
                        $("#divDeshuesarEquipo").empty();
                    });
                });
            });

            initDeshuesarEquipos();
        });
    }

    function initDeshuesarEquipos() {
        $('#data-table-equipos-deshueso tbody').on('click', 'tr', function () {
            var _datos = {
                datos: $('#data-table-equipos-deshueso').DataTable().row(this).data()
            }
            evento.enviarEvento('Catalogo/MostrarComponentesDeshueso', _datos, '#panelDeshuesarEquipo', function (respuesta) {
                $("#divListaComponentesDeshuesar").empty().append(respuesta.html);
                tabla.generaTablaPersonal('#data-table-componentes-deshueso', null, null, true, false, [], null, null, false);

                $("#divListaEquiposDeshuesar").fadeOut(400, function () {
                    $("#divListaComponentesDeshuesar").fadeIn(400);
                });

                $("#divListaComponentesDeshuesar #btnRegresar").off("click");
                $("#divListaComponentesDeshuesar #btnRegresar").on("click", function () {
                    $("#divListaComponentesDeshuesar").fadeOut(400, function () {
                        $("#divListaEquiposDeshuesar").fadeIn(400, function () {
                            $("#divListaComponentesDeshuesar").empty();
                        });
                    });
                });

                $("#btnGuargarDeshuesoEquipo").off("click");
                $("#btnGuargarDeshuesoEquipo").on("click", function () {
                    if (evento.validarFormulario("#formularioSeriesCaptureComponentes")) {
                        var data = { 'componentes': [], 'idInventario': _datos['datos'][0] };

                        var sinSerie = 0;
                        $(".serie-componente-deshueso").each(function () {
                            if ($(this).val() == "") {
                                sinSerie++;
                            }
                        });

                        $(".list-estus-componentes-deshueso").each(function () {
                            data.componentes.push({
                                'IdAlmacen': _datos['datos'][2],
                                'IdTipoProducto': 2,
                                'IdProducto': $(this).attr('data-id'),
                                'IdEstatus': $(this).val(),
                                'Cantidad': 1,
                                'Serie': $("#serie-componente-deshueso-" + $(this).attr("data-id")).val()
                            });
                        });

                        var listaErrores = [];

                        if (sinSerie > 0) {
                            listaErrores.push(sinSerie + " producto(s) no cuentan con Serie registrada.");
                        }

                        var html = '<h4 class="text-center">Se han encontrado las siguientes advertencias:</h4><h5 class="bg-warning"><ul>';

                        if (listaErrores.length > 0) {
                            $.each(listaErrores, function (k, v) {
                                html += '<li class="m-t-10">' + v + '</li>';
                            });
                        }

                        html += '</ul></h5><br /><h4 class="text-center">¿Desea proceder con el guardado de información?</h4>';

                        if (listaErrores.length > 0) {
                            evento.mostrarModal('Advertencia', html);
                            $('#btnModalConfirmar').off("click");
                            $('#btnModalConfirmar').on("click", function () {
                                evento.cerrarModal();
                                saveComponentesDeshueso(data);
                            });
                        } else {
                            saveComponentesDeshueso(data);
                        }
                    }
                });

            });
        });
    }

    function saveComponentesDeshueso() {
        var data = arguments[0] || [];
        var datos = {
            'data': data
        }
        var respuesta = { 'estatus': 200 };
        evento.enviarEvento('Catalogo/GuardarComponentesDeshueso', datos, '#panelDeshuesarEquipo', function (respuesta) {
            if (respuesta.estatus == 200) {
                $("#divListaComponentesDeshuesar #btnRegresar").click();
                verDeshuesarEquipo(1);
            } else {
                $("#divListaComponentesDeshuesar #btnRegresar").click();
                verDeshuesarEquipo(0);
            }
        });


    }

    function verKitsEquipo() {
        var divHide = arguments[0] || 'divListaCatalogos';
        evento.enviarEvento('Catalogo/MostrarKitsEquipos', {}, '#seccionAlmacenes', function (respuesta) {
            $("#divVerKitsEquipos").empty().append(respuesta.html);

            $("#" + divHide).fadeOut(400, function () {
                $("#divVerKitsEquipos").fadeIn(400);
            });

            $("#divVerKitsEquipos #listaKits #btnRegresar").off("click");
            $("#divVerKitsEquipos #listaKits #btnRegresar").on("click", function () {
                $("#divVerKitsEquipos").fadeOut(400, function () {
                    $("#divListaCatalogos").fadeIn(400, function () {
                        $("#divVerKitsEquipos").empty();
                    });
                });
            });

            initVerKitsEquipos();
        });
    }

    var _estatusProductos = {};

    $('#data-table-almacenes tbody').on('click', 'tr', function () {
        var _row = $(this);
        var _datos = {
            datos: $('#data-table-almacenes').DataTable().row(this).data()
        }
        evento.enviarEvento('Catalogo/MostrarAlamacenVirtual', _datos, '#seccionAlmacenes', function (respuesta) {
            $("#divInventarioAlmacen").empty().append(respuesta.html);

            $("#divListaCatalogos").fadeOut(400, function () {
                $("#divInventarioAlmacen").fadeIn(400);
            });

            $("#divInventarioAlmacen #btnRegresar").off("click");
            $("#divInventarioAlmacen #btnRegresar").on("click", function () {
                $("#divInventarioAlmacen").fadeOut(400, function () {
                    $("#divListaCatalogos").fadeIn(400, function () {
                        $("#divInventarioAlmacen").empty();
                    });
                });
            });

            if (respuesta.tipoAlmacen == 1 || respuesta.tipoAlmacen == 4) {
                tabla.generaTablaPersonal('#data-table-poliza', null, null, true);
                tabla.generaTablaPersonal('#data-table-salas', null, null, true);
                tabla.generaTablaPersonal('#data-table-otros', null, null, true);
                tabla.generaTablaPersonal('#data-table-movimientos', null, null, true);

                fecha.rangoFechas('#desde', '#hasta');
                $('#desde').data("DateTimePicker").enable().clear();
                $('#hasta').data("DateTimePicker").enable().clear();

                $("#btnFiltrarMovimientos").off("click");
                $("#btnFiltrarMovimientos").on("click", function () {
                    var datos = {
                        'id': _datos.datos[0],
                        'tipoMov': $("#listFilterTipoMovimiento").val(),
                        'tipoProd': $("#listFilterTipoProducto").val(),
                        'desde': $("#txtDesde").val(),
                        'hasta': $("#txtHasta").val(),
                    }
                    evento.enviarEvento('Catalogo/FiltrarMovimientosInventario', datos, '#panelInventarioAlmacen', function (respuesta) {
                        tabla.limpiarTabla("#data-table-movimientos");
                        $.each(respuesta, function (key, item) {
                            tabla.agregarFila('#data-table-movimientos', [item.Movimiento, item.Origen, item.TipoProducto, item.Producto, item.Cantidad, item.Serie, item.Estatus, item.Usuario, item.Fecha]);
                        });
                    });
                });

                $("#btnNuevaAltaInicial").off("click");
                $("#btnNuevaAltaInicial").on("click", function () {
                    evento.enviarEvento('Catalogo/NuevaAltaInicial', _datos, '#panelInventarioAlmacen', function (respuesta) {
                        _row.click();
                    });
                });

                $("#btnCerrarAltaInicial").off("click");
                $("#btnCerrarAltaInicial").on("click", function () {
                    evento.enviarEvento('Catalogo/CerrarAltaInicial', {}, '#panelInventarioAlmacen', function (respuesta) {
                        if (respuesta.estatus) {
                            window.open(respuesta.file);
                            _row.click();
                        } else {
                            evento.mostrarMensaje('#errorInventarioAlmacen', false, 'No se pudo cerrar el Alta. Intente de nuevo o recargue su página. Si el problema persiste, contácte al Administrador', 4000);
                        }
                    });
                });

                $("#btnAddInicialPoliza").off("click");
                $("#btnAddInicialPoliza").on("click", function () {
                    evento.enviarEvento('Catalogo/AgregarProductoPoliza', {}, '#panelInventarioAlmacen', function (respuesta) {
                        _estatusProductos = respuesta.estatus;
                        var _optionsEstatus = '';
                        $.each(_estatusProductos, function (k, v) {
                            _optionsEstatus += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                        });

                        $("#divAgregarProducto").empty().append(respuesta.html);
                        $("#divInventarioAlmacen").fadeOut(400, function () {
                            $("#divAgregarProducto").fadeIn(400);
                        });

                        $("#divAgregarProducto #btnRegresar").off("click");
                        $("#divAgregarProducto #btnRegresar").on("click", function () {
                            $("#divAgregarProducto").fadeOut(400, function () {
                                $("#divInventarioAlmacen").fadeIn(400, function () {
                                    $("#divAgregarProducto").empty();
                                });
                            });
                        });

                        select.crearSelect('#listModelos');
                        select.crearSelect('#listRefacciones');

                        $("#listModelos").on("change", function () {
                            var _modelo = $(this).val();

                            $("#listRefacciones option").each(function () {
                                if ($(this).val() != '') {
                                    $(this).remove();
                                }
                            });
                            var datos = {
                                modelo: _modelo
                            }
                            evento.enviarEvento('Catalogo/CargaComponentesPoliza', datos, '#panelAgregarProducto', function (respuesta) {
                                $.each(respuesta.componentes, function (k, v) {
                                    $("#listRefacciones").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                                });
                            });


                        });

                        $("#txtCantidad").bind('keyup mouseup', function () {
                            var cantidad = $("#txtCantidad").val();
                            var campos = $("#formularioSeriesCapture").children('div.row').length;
                            var contador = 0;

                            if (cantidad !== campos) {
                                if (cantidad < campos) {
                                    contador = 0;
                                    $("#formularioSeriesCapture > div.row").each(function () {
                                        var _this = $(this);
                                        contador++;
                                        if (contador > cantidad) {
                                            _this.remove();
                                        }
                                    });
                                } else {
                                    for (var i = 0; i < cantidad; i++) {
                                        if (!$("#formularioSeriesCapture").children('div.row').eq(i).length) {
                                            var html = `<div class="row">
                                                        <div class="col-md-1 col-sm-2 col-xs-12">
                                                            <div class="form-grup">
                                                                <label class="f-w-600">Producto</label>
                                                                <input type="text" value="#` + (i + 1) + `" disabled="disabled" class="form-control f-s-16 text-center" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="f-w-600">Serie</label>
                                                                <input type="text" class="info-serie-` + (i + 1) + ` form-control" placeholder="Introduce Serie" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="f-w-600">Estatus *</label>
                                                                <select id="list-info-estatus-` + (i + 1) + `" class="form-control" data-parsley-required="true">
                                                                    <option value="">Selecciona . . .</option>
                                                                    ` + _optionsEstatus + `
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>`;
                                            $("#formularioSeriesCapture").append(html);
                                        }
                                    }
                                }
                            }
                        });

                        $("#btnGuardarProductoPoliza").off("click");
                        $("#btnGuardarProductoPoliza").on("click", function () {
                            if (evento.validarFormulario('#formAddProducto')) {
                                if (evento.validarFormulario('#formularioSeriesCapture')) {
                                    var data = [];
                                    var contador = 0;
                                    var sinSerie = 0;
                                    var tipoProducto = 1;
                                    var producto = $("#listModelos option:selected").val();
                                    var componente = $("#listRefacciones option:selected").val();
                                    if (componente != '') {
                                        tipoProducto = 2;
                                        producto = componente;
                                    }


                                    $("#formularioSeriesCapture > div.row").each(function () {
                                        contador++;
                                        var serie = $(".info-serie-" + contador).val();
                                        sinSerie = (serie == '') ? (sinSerie + 1) : sinSerie;

                                        data.push({
                                            'IdAlmacen': _datos['datos'][0],
                                            'IdTipoProducto': tipoProducto,
                                            'IdProducto': producto,
                                            'IdEstatus': $("#list-info-estatus-" + contador).val(),
                                            'Cantidad': 1,
                                            'Serie': serie
                                        });
                                    });
                                    revisaSeriesDuplicadas(data, sinSerie);
                                }
                            }
                        });
                    });
                });

                $("#btnAddInicialSalas").off("click");
                $("#btnAddInicialSalas").on("click", function () {
                    evento.enviarEvento('Catalogo/AgregarProductoSalas', {}, '#panelInventarioAlmacen', function (respuesta) {
                        _estatusProductos = respuesta.estatus;
                        var _optionsEstatus = '';
                        $.each(_estatusProductos, function (k, v) {
                            _optionsEstatus += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                        });

                        initCambiarEstatusProductos();

                        $("#divAgregarProducto").empty().append(respuesta.html);
                        $("#divInventarioAlmacen").fadeOut(400, function () {
                            $("#divAgregarProducto").fadeIn(400);
                        });

                        $("#divAgregarProducto #btnRegresar").off("click");
                        $("#divAgregarProducto #btnRegresar").on("click", function () {
                            $("#divAgregarProducto").fadeOut(400, function () {
                                $("#divInventarioAlmacen").fadeIn(400, function () {
                                    $("#divAgregarProducto").empty();
                                });
                            });
                        });

                        select.crearSelect('#listModelos');
                        select.crearSelect('#listRefacciones');

                        $("#listModelos").on("change", function () {
                            var _modelo = $(this).val();

                            $("#listRefacciones option").each(function () {
                                if ($(this).val() != '') {
                                    $(this).remove();
                                }
                            });
                            var datos = {
                                modelo: _modelo
                            }
                            evento.enviarEvento('Catalogo/CargaSubelementosSalas4D', datos, '#panelAgregarProducto', function (respuesta) {
                                $.each(respuesta.componentes, function (k, v) {
                                    $("#listRefacciones").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                                });
                            });


                        });

                        $("#txtCantidad").bind('keyup mouseup', function () {
                            var cantidad = $("#txtCantidad").val();
                            var campos = $("#formularioSeriesCapture").children('div.row').length;
                            var contador = 0;

                            if (cantidad !== campos) {
                                if (cantidad < campos) {
                                    contador = 0;
                                    $("#formularioSeriesCapture > div.row").each(function () {
                                        var _this = $(this);
                                        contador++;
                                        if (contador > cantidad) {
                                            _this.remove();
                                        }
                                    });
                                } else {
                                    for (var i = 0; i < cantidad; i++) {
                                        if (!$("#formularioSeriesCapture").children('div.row').eq(i).length) {
                                            var html = `<div class="row">
                                                        <div class="col-md-1 col-sm-2 col-xs-12">
                                                            <div class="form-grup">
                                                                <label class="f-w-600">Producto</label>
                                                                <input type="text" value="#` + (i + 1) + `" disabled="disabled" class="form-control f-s-16 text-center" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="f-w-600">Serie</label>
                                                                <input type="text" class="info-serie-` + (i + 1) + ` form-control" placeholder="Introduce Serie" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                                            <div class="form-group">
                                                                <label class="f-w-600">Estatus *</label>
                                                                <select id="list-info-estatus-` + (i + 1) + `" class="form-control listEstatusProductos" data-parsley-required="true">
                                                                    <option value="">Selecciona . . .</option>
                                                                    ` + _optionsEstatus + `
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>`;
                                            $("#formularioSeriesCapture").append(html);
                                        }
                                    }
                                    initCambiarEstatusProductos();
                                }
                            }
                        });

                        $("#btnGuardarProductoSalas").off("click");
                        $("#btnGuardarProductoSalas").on("click", function () {
                            if (evento.validarFormulario('#formAddProducto')) {
                                if (evento.validarFormulario('#formularioSeriesCapture')) {
                                    var data = [];
                                    var contador = 0;
                                    var sinSerie = 0;
                                    var tipoProducto = 3;
                                    var producto = $("#listModelos option:selected").val();
                                    var componente = $("#listRefacciones option:selected").val();
                                    if (componente != '') {
                                        tipoProducto = 4;
                                        producto = componente;
                                    }


                                    $("#formularioSeriesCapture > div.row").each(function () {
                                        contador++;
                                        var serie = $(".info-serie-" + contador).val();
                                        sinSerie = (serie == '') ? (sinSerie + 1) : sinSerie;

                                        data.push({
                                            'IdAlmacen': _datos['datos'][0],
                                            'IdTipoProducto': tipoProducto,
                                            'IdProducto': producto,
                                            'IdEstatus': $("#list-info-estatus-" + contador).val(),
                                            'Cantidad': 1,
                                            'Serie': serie
                                        });
                                    });

                                    revisaSeriesDuplicadas(data, sinSerie);
                                }
                            }
                        });
                    });
                });

                $("#btnAddInicialOtros").off("click");
                $("#btnAddInicialOtros").on("click", function () {
                    evento.enviarEvento('Catalogo/AgregarProductoSAE', {}, '#panelInventarioAlmacen', function (respuesta) {
                        _estatusProductos = respuesta.estatus;
                        var _optionsEstatus = '';
                        $.each(_estatusProductos, function (k, v) {
                            _optionsEstatus += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
                        });

                        $("#divAgregarProducto").empty().append(respuesta.html);
                        $("#divInventarioAlmacen").fadeOut(400, function () {
                            $("#divAgregarProducto").fadeIn(400);
                        });

                        $("#divAgregarProducto #btnRegresar").off("click");
                        $("#divAgregarProducto #btnRegresar").on("click", function () {
                            $("#divAgregarProducto").fadeOut(400, function () {
                                $("#divInventarioAlmacen").fadeIn(400, function () {
                                    $("#divAgregarProducto").empty();
                                });
                            });
                        });

                        $("#btnFilterSAEProducts").off("click");
                        $("#btnFilterSAEProducts").on("click", function () {
                            filtraProductosSAE();
                        });

                        $('#txtFiltrarSAE').keyup(function (e) {
                            if (e.keyCode == 13) {
                                e.preventDefault();
                                filtraProductosSAE();
                            }
                        });

                        $("#btnGuardarProductoSAE").off("click");
                        $("#btnGuardarProductoSAE").on("click", function () {
                            if (evento.validarFormulario('#formAddProducto')) {
                                var data = [];
                                var tipoProducto = 5;
                                var producto = $("#listProductos option:selected").val();

                                data.push({
                                    'IdAlmacen': _datos['datos'][0],
                                    'IdTipoProducto': tipoProducto,
                                    'IdProducto': producto,
                                    'IdEstatus': 17,
                                    'Cantidad': $("#txtCantidad").val(),
                                    'Serie': ''
                                });

                                saveProducts(data, true);
                            }
                        });
                    });
                });
            } else if (respuesta.tipoAlmacen == 2) {
                tabla.generaTablaPersonal('#data-table-censo-poliza', null, null, true);
            } else if (respuesta.tipoAlmacen == 3) {
                tabla.generaTablaPersonal('#data-table-inventario-sala4d-elementos', null, null, true);
                tabla.generaTablaPersonal('#data-table-inventario-sala4d-subelementos', null, null, true);
            }
        });
    });

    function initCambiarEstatusProductos() {
        $(".btnMarcarEstatusAll").off("click");
        $(".btnMarcarEstatusAll").on("click", function () {
            var estatus = $(this).attr("data-id");
            $(".listEstatusProductos").val(estatus);
        });
    }

    function revisaSeriesDuplicadas() {
        var datos = {
            'data': arguments[0]
        }
        var sinSerie = arguments[1];

        evento.enviarEvento('Catalogo/RevisaSeriesDuplicadas', datos, '#panelAgregarProducto', function (respuesta) {
            var seriesDuplicadas = respuesta.series;
            if (seriesDuplicadas.length > 0) {
                var html = '<h4 class="text-center">Las siguientes series ya se encuentran registradas en el sistema.\nPor favor corrija la información.</h4><h5 class="bg-warning"><ul>';
                $.each(seriesDuplicadas, function (k, v) {
                    html += '<li class="m-t-10">' + v + '</li>';
                });
                html += '</ul></h5>';
                evento.mostrarModal('Error', html);
                $("#btnModalAbortar").empty().append("Aceptar");
                $("#btnModalConfirmar").hide();
            } else {
                var listaErrores = [];

                if (sinSerie > 0) {
                    listaErrores.push(sinSerie + " producto(s) no cuentan con Serie registrada.");
                }

                var html = '<h4 class="text-center">Se han encontrado las siguientes advertencias:</h4><h5 class="bg-warning"><ul>';

                if (listaErrores.length > 0) {
                    $.each(listaErrores, function (k, v) {
                        html += '<li class="m-t-10">' + v + '</li>';
                    });
                }

                html += '</ul></h5><br /><h4 class="text-center">¿Desea proceder con el guardado de información?</h4>';

                if (listaErrores.length > 0) {
                    evento.mostrarModal('Advertencia', html);
                    $("#btnModalAbortar").empty().append("Cancelar");
                    $("#btnModalConfirmar").empty().append("Aceptar").show();
                    $('#btnModalConfirmar').off("click");
                    $('#btnModalConfirmar').on("click", function () {
                        evento.cerrarModal();
                        saveProducts(datos.data);
                    });
                } else {
                    saveProducts(datos.data);
                }
            }
        });
    }

    function filtraProductosSAE() {
        $("#listProductos > option").removeClass("hidden")
        var _textFilter = $("#txtFiltrarSAE").val();
        $("#listProductos option").each(function () {
            var _this = $(this);
            var _string = _this.text();
            if (_string.toUpperCase().indexOf(_textFilter.toUpperCase()) === -1) {
                _this.addClass("hidden");
            }
        });

        $("#listProductos").focus(function () {
            $("#listProductos").click();
        });
    }

    function saveProducts() {
        var data = arguments[0] || [];
        var SAE = arguments[1] || false;
        if (data.length > 0) {
            var datos = {
                'data': data
            }
            evento.enviarEvento('Catalogo/GuardarProductosInventario', datos, '#panelAgregarProducto', function (respuesta) {
                if (respuesta.estatus == 200) {
                    if (SAE) {
                        $("#txtFiltrarSAE").val("");
                        $("#listProductos").val("");
                        $("#txtCantidad").val("1");
                        evento.limpiarFormulario("#formAddProducto");

                        tabla.limpiarTabla("#data-table-otros");
                        $.each(respuesta.otros, function (k, v) {
                            tabla.agregarFila("#data-table-otros", [v.Id, v.Producto, v.Tipo, v.Cantidad, v.Serie, v.Estatus]);
                        });


                        filtraProductosSAE();

                    } else {
                        $("#listModelos").val("");
                        $("#listRefacciones").val("");
                        $("#txtCantidad").val("");
                        $("#info-serie-1").val("");
                        $("#list-info-estatus-1").val("");
                        var contador = 0;
                        $("#formularioSeriesCapture > div.row").each(function () {
                            var _this = $(this);
                            contador++;
                            if (contador > 1) {
                                _this.remove();
                            }
                        });

                        evento.limpiarFormulario("#formAddProducto");
                        evento.limpiarFormulario("#formularioSeriesCapture");

                        tabla.limpiarTabla("#data-table-poliza");
                        $.each(respuesta.poliza, function (k, v) {
                            tabla.agregarFila("#data-table-poliza", [v.Id, v.Producto, v.Tipo, v.Cantidad, v.Serie, v.Estatus]);
                        });

                        tabla.limpiarTabla("#data-table-salas");
                        $.each(respuesta.salas, function (k, v) {
                            tabla.agregarFila("#data-table-salas", [v.Id, v.Producto, v.Tipo, v.Cantidad, v.Serie, v.Estatus]);
                        });
                    }
                }
            });
        }

    }

    function initTraspaso() {
        $("#listAlmacenOrigen").on("change", function () {
            var _origen = $(this).val();
            $("#divProductosTraspaso").empty().addClass('hidden');
            $("#divBtnTraspaso").addClass('hidden');
            if (_origen != '') {
                var data = {
                    'origen': _origen
                }
                evento.enviarEvento('Catalogo/MostrarProductosTraspaso', data, '#panelTraspaso', function (respuesta) {
                    $("#divProductosTraspaso").empty().append(respuesta.html).removeClass('hidden');
                    $("#divBtnTraspaso").removeClass('hidden');
                    tabla.generaTablaPersonal('#data-table-inventario', null, null, true, false, [], null, null, false);
                    tabla.generaTablaPersonal('#data-table-inventario-otros', null, null, true, false, [], null, null, false);

                    $('#data-table-inventario tbody').on('click', 'tr', function () {
                        var _dataId = $(this).attr("data-id");
                        var checkBox = $("#producto-" + _dataId);
                        checkBox.attr("checked", !checkBox.attr("checked"));
                    });

                    $('#data-table-inventario-otros tbody').on('click', 'tr', function () {
                        var _dataId = $(this).attr("data-id");
                        var checkBox = $("#producto-" + _dataId);
                        checkBox.attr("checked", !checkBox.attr("checked"));
                    });

                    $(".cantidad-producto-otros").focusout(function () {
                        var _this = $(this);
                        var id = _this.attr("data-id");
                        var _cantidad = _this.val();
                        var max = $("#cantidad-producto-hidden-" + id).val();

                        if (parseFloat(_cantidad) > parseFloat(max)) {
                            _this.val(max);
                        }

                        if (parseFloat(_cantidad) < parseFloat(0)) {
                            _this.val(0);
                        }

                    }).bind(function () {
                        var _this = $(this);
                        var id = _this.attr("data-id");
                        var _cantidad = _this.val();
                        var max = $("#cantidad-producto-hidden-" + id).val();

                        if (parseFloat(_cantidad) > parseFloat(max)) {
                            _this.val(max);
                        }

                        if (parseFloat(_cantidad) < parseFloat(0)) {
                            _this.val(0);
                        }
                    });



                    $("#btnTraspasar").off("click");
                    $("#btnTraspasar").on("click", function () {
                        var productoTraspasar = {
                            'equipos': [],
                            'otros': []
                        };


                        $(".producto-inventario").each(function () {
                            if ($(this).is(":checked")) {
                                productoTraspasar.equipos.push($(this).attr("data-id"));
                            }
                        });




                        $(".cantidad-producto-otros").each(function () {
                            var _this = $(this);
                            var id = _this.attr("data-id");
                            var _cantidad = _this.val();
                            var max = $("#cantidad-producto-hidden-" + id).val();

                            if (parseFloat(_cantidad) > parseFloat(max)) {
                                _this.val(max);
                            }

                            if (parseFloat(_cantidad) < parseFloat(0)) {
                                _this.val(0);
                            }

                            if (_cantidad != '' && _cantidad > 0) {
                                productoTraspasar.otros.push({
                                    'id': id,
                                    'cantidad': _cantidad
                                });
                            }
                        });

                        if (productoTraspasar.equipos.length > 0 || productoTraspasar.otros.length > 0) {
                            if ($("#listAlmacenOrigen").val() != "" && $("#listAlmacenDestino").val() != "") {
                                var data = {
                                    'origen': $("#listAlmacenOrigen").val(),
                                    'destino': $("#listAlmacenDestino").val(),
                                    'equipos': productoTraspasar.equipos,
                                    'otros': productoTraspasar.otros
                                }
                                evento.enviarEvento('Catalogo/TraspasarProductos', data, '#panelTraspaso', function (respuesta) {
                                    if (respuesta.estatus) {
                                        window.open(respuesta.file);
                                        $("#btnTraspasarAlmacenes").click();
                                    } else {
                                        evento.mostrarMensaje('#errorTraspasar', false, 'No se pudo registrar el traspaso. Intente de nuevo o recargue su página.', 4000);
                                    }
                                });
                            } else {
                                evento.mostrarMensaje('#errorTraspasar', false, 'Debe seleccionar un almacén de Origen y un almacén Destino.', 4000);
                            }
                        } else {
                            evento.mostrarMensaje('#errorTraspasar', false, 'No se han seleccionaro productos para traspasar.', 4000);
                        }
                    });
                });
            }
        });
    }

    function initVerTraspasos() {
        tabla.generaTablaPersonal('#data-table-traspasos', null, null, true);
        $('#data-table-traspasos tbody').on('click', 'tr', function () {
            var datos = $('#data-table-traspasos').DataTable().row(this).data();
            var data = {
                'id': datos[0]
            }
            evento.enviarEvento('Catalogo/ImprimirTraspaso', data, '#panelTraspasos', function (respuesta) {
                window.open(respuesta.file);
            });
        });
    }

    function initVerAltasIniciales() {
        tabla.generaTablaPersonal('#data-table-altas-iniciales', null, null, true);
        $('#data-table-altas-iniciales tbody').on('click', 'tr', function () {
            var datos = $('#data-table-altas-iniciales').DataTable().row(this).data();
            var data = {
                'id': datos[0]
            }
            evento.enviarEvento('Catalogo/ImprimirAltaInicial', data, '#panelAltasIniciales', function (respuesta) {
                window.open(respuesta.file);
            });
        });
    }

    function initVerKitsEquipos() {
        tabla.generaTablaPersonal('#data-table-kits-equipos', null, null, true);
        $('#data-table-kits-equipos tbody').on('click', 'tr', function () {
            var datos = $('#data-table-kits-equipos').DataTable().row(this).data();
            var data = {
                'id': datos[0]
            }

            $("#mainTitle").empty().append("Editar Kit de Equipo");
            $("#panelAgregarEditarKit .panel-title").empty().append("Editar Kit de Equipo");
            $("#mainTitleBody").empty().append("Editar Kit de Equipo");

            $("#listEquiposKit").val(data.id).trigger("change");

            $("#listaKits").fadeOut(400, function () {
                $("#agregarEditarKit").fadeIn(400);
            });

            $("#agregarEditarKit #btnRegresar").off("click");
            $("#agregarEditarKit #btnRegresar").on("click", function () {
                $("#agregarEditarKit").fadeOut(400, function () {
                    $("#listaKits").fadeIn(400, function () {

                    });
                });
            });

        });

        select.crearSelect("#listEquiposKit");

        $("#btnAgregarKitEquipo").off("click");
        $("#btnAgregarKitEquipo").on("click", function () {
            $("#mainTitle").empty().append("Agregar Kit de Equipo");
            $("#panelAgregarEditarKit .panel-title").empty().append("Agregar Kit de Equipo");
            $("#mainTitleBody").empty().append("Agregar nuevo Kit de Equipo");

            $("#listEquiposKit").val("").trigger("change");
            $("#div-table-componentes-kit").empty();

            $("#listaKits").fadeOut(400, function () {
                $("#agregarEditarKit").fadeIn(400);

            });

            $("#agregarEditarKit #btnRegresar").off("click");
            $("#agregarEditarKit #btnRegresar").on("click", function () {
                $("#agregarEditarKit").fadeOut(400, function () {
                    $("#listaKits").fadeIn(400, function () {

                    });
                });
            });
        });

        $("#listEquiposKit").on("change", function () {
            var _modelo = $(this).val();
            if (_modelo == "") {
                $("#div-table-componentes-kit").empty();
            } else {
                var data = {
                    'equipo': _modelo
                }
                evento.enviarEvento('Catalogo/MostrarComponentesEquipoKit', data, '#panelAgregarEditarKit', function (respuesta) {
                    $("#div-table-componentes-kit").empty().append(respuesta.html);
                    tabla.generaTablaPersonal('#data-table-componentes-kit', null, null, true, false, [], null, null, false);

                    $("#btnGuardarKit").off("click");
                    $("#btnGuardarKit").on("click", function () {
                        var arrayComponentes = [];
                        $(".cantidad-componente-kit").each(function (k, v) {
                            var _cantidadComponente = $(this).val();
                            if (!isNaN(_cantidadComponente) && _cantidadComponente > 0) {
                                arrayComponentes.push($(this).attr("data-id") + "_" + _cantidadComponente);
                            }
                        });

                        if (arrayComponentes.length > 0) {
                            var data = {
                                'modelo': _modelo,
                                'componentes': arrayComponentes
                            };
                            evento.enviarEvento('Catalogo/GuardarKit', data, '#panelAgregarEditarKit', function (respuesta) {
                                if (respuesta.estatus) {
                                    verKitsEquipo('agregarEditarKit');
                                } else {
                                    evento.mostrarMensaje('#errorTraspasar', false, 'No se pudo registrar el traspaso. Intente de nuevo o recargue su página.', 4000);
                                }
                            });
                        }
                    });

                });
            }
        });


    }
});
