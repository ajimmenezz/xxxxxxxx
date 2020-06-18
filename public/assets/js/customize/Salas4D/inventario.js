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

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();


    $("#btnGo").off("click");
    $("#btnGo").on("click", function () {
        $("#errorMainPageInventario").html("");
        var sucursal = $("#listaSucursales").val();
        if (sucursal !== "") {
            showInventarioSucursal();
        } else {
            evento.mostrarMensaje('#errorMainPageInventario', false, 'Debes seleccionar la sucursal de la lista.', 4000);
        }
    });

    function showInventarioSucursal() {
        var lastDiv = arguments[0] || 'mainPageInventario';
        var vista = arguments[1] || 'vistaElementos';
        var sucursal = $("#listaSucursales").val();
        var nombreSucursal = $("#listaSucursales option:selected").text();
        evento.enviarEvento('Inventario/InventarioSucursal', {id: sucursal}, '#panelMainInventario', function (respuesta) {
            if (respuesta.code == 200) {
                $("#capturePageInventario").empty().append(respuesta.html);

                $("#titleResumenInventario").append("Inventario de " + nombreSucursal);
                tabla.generaTablaPersonal('#data-table-elementos', null, null, true, true, [[0, 'desc']]);
                tabla.generaTablaPersonal('#data-table-subelementos', null, null, true, true, [[0, 'desc']]);
                initTableElementsView();

                $("#idSucursal").val(sucursal);

                $("#" + lastDiv).fadeOut(400, function () {
                    $("#capturePageInventario").fadeIn(400, function () {
                        if (vista != 'vistaElementos') {
                            $("#vistaElementos").fadeOut(400, function () {
                                $("#" + vista).fadeIn(400);
                            });
                        }
                    });
                });

                initButtonsCapturePage();

            } else {
                evento.mostrarMensaje('#errorMainPageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
            }
        });
    }

    function initButtonsCapturePage() {
        $("#btnVersionImprimible").off("click");
        $("#btnVersionImprimible").on("click", function () {
            var sucursal = $("#listaSucursales").val();
            var nombreSucursal = $("#listaSucursales option:selected").text();
            evento.enviarEvento('Inventario/VersionImprimible', {'idsucursal': sucursal, 'sucursal': nombreSucursal}, '#capturePageInventario', function (respuesta) {
                window.open(respuesta, '_blank');
            });
        });

        $("#btnRegresarToMain").off("click");
        $("#btnRegresarToMain").on("click", function () {
            $("#capturePageInventario").fadeOut(400, function () {
                $("#mainPageInventario").fadeIn(400);
            });
        });

        $("#btnVerVistaSubelementos").off("click");
        $("#btnVerVistaSubelementos").on("click", function () {
            $("#vistaElementos").fadeOut(400, function () {
                $("#vistaSubelementos").fadeIn(400);
            });
        });

        $("#btnVerVistaElementos").off("click");
        $("#btnVerVistaElementos").on("click", function () {
            $("#vistaSubelementos").fadeOut(400, function () {
                $("#vistaElementos").fadeIn(400);
            });
        });

        $("#btnAddElement").off("click");
        $("#btnAddElement").on("click", function () {
            evento.enviarEvento('Inventario/FormularioAgregarElemento', {}, '#panelResumenInventario', function (respuesta) {
                if (respuesta.code == 200) {
                    $("#addElementPageInventario").empty().append(respuesta.html);

                    $("#capturePageInventario").fadeOut(400, function () {
                        $("#addElementPageInventario").fadeIn(400);
                    });

                    initButtonsAddElementPage();

                } else {
                    evento.mostrarMensaje('#errorCapturePageInventario', false, 'Error desconocido. Favor de contactar con el administrador.', 4000);
                }
            });
        });
    }

    function initButtonsAddElementPage() {
        var file = new Upload();
        file.crearUpload('#fotosElemento', 'Inventario/GuardaElementosFoto');

        $("#btnRegresarToCapture").off("click");
        $("#btnRegresarToCapture").on("click", function () {
            $("#addElementPageInventario").fadeOut(400, function () {
                $("#capturePageInventario").fadeIn(400);
            });
        });

        $(".btn-save-without-subelements").off("click");
        $(".btn-save-without-subelements").on("click", function () {
            saveNewElements(file);
        });

        $("#txtCantidad").bind('keyup mouseup', function () {
            var cantidad = $("#txtCantidad").val();
            var campos = $("#formSeriesCapture").children('div.row').length;
            var contador = 0;

            if (cantidad >= 4) {
                $(".btns-bottom").removeClass("hidden");
            } else {
                $(".btns-bottom").addClass("hidden");
            }

            if (cantidad !== campos) {
                if (cantidad < campos) {
                    contador = 0;
                    $("#formSeriesCapture > div.row").each(function () {
                        var _this = $(this);
                        contador++;
                        if (contador > cantidad) {
                            _this.remove();
                        }
                    });
                } else {
                    for (var i = 0; i < cantidad; i++) {
                        if (!$("#formSeriesCapture").children('div.row').eq(i).length) {
                            var html = `<div class="row">
                                        <div class="col-md-1 col-sm-2 col-xs-12">
                                            <div class="form-grup">
                                                <label class="f-w-600">Elemento</label>
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
                                                <label class="f-w-600">Clave Cinemex (Inventario)</label>
                                                <input type="text" class="info-clave-` + (i + 1) + ` form-control" placeholder="Introduce Clave" />
                                            </div>
                                        </div>
                                    </div>`;
                            $("#formSeriesCapture").append(html);
                        }
                    }
                }
            }
        });
    }

    function saveNewElements() {
        var file = arguments[0];
        if (evento.validarFormulario('#formAddElement')) {
            var fotos = $("#fotosElemento").val();

            var data = [];
            var contador = 0;
            var sinSerie = 0;
            var sinClave = 0;
            $("#formSeriesCapture > div.row").each(function () {
                contador++;
                var serie = $(".info-serie-" + contador).val();
                sinSerie = (serie == '') ? (sinSerie + 1) : sinSerie;
                var clave = $(".info-clave-" + contador).val();
                sinClave = (clave == '') ? (sinClave + 1) : sinClave;
                var ubicacion = $("#listUbicaciones option:selected").val();
                var sistema = $("#listSistemas option:selected").val();
                var elemento = $("#listElementos option:selected").val();
                data.push({
                    ubicacion: ubicacion,
                    sistema: sistema,
                    elemento: elemento,
                    serie: serie,
                    clave: clave,
                    fotos: fotos
                });
            });

            var dataError = {
                serie: sinSerie,
                clave: sinClave
            }

            var listaErrores = [];



            if (sinSerie > 0) {
                listaErrores.push(sinSerie + " elemento(s) no cuentan con Serie registrada.");
            }

            if (sinClave > 0) {
                listaErrores.push(sinClave + " elemento(s) no cuentan con Clave Registrada.");
            }

            if (fotos == '') {
                listaErrores.push("No hay fotografía para los elementos.");
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
                    if (fotos == '') {
                        saveElementsWithoutImage(data, null);
                    } else {
                        var datos = {
                            sucursal: $("#listaSucursales option:selected").val()
                        }
                        file.enviarArchivos('#fotosElemento', 'Inventario/GuardaElementosFoto', '#panelAgregarElemento', datos, function (respuesta) {
                            evento.cerrarModal();
                            if (respuesta.code == 200) {
                                saveElementsWithoutImage(data, respuesta.files);
                            }
                        });
                    }
                });
            } else {
                if (fotos == '') {
                    saveElementsWithoutImage(data, null);
                } else {
                    var datos = {
                        sucursal: $("#listaSucursales option:selected").val()
                    }
                    file.enviarArchivos('#fotosElemento', 'Inventario/GuardaElementosFoto', '#panelAgregarElemento', datos, function (respuesta) {
                        if (respuesta.code == 200) {
                            saveElementsWithoutImage(data, respuesta.files);
                        }
                    });
                }
            }
        }
    }

    function saveElementsWithoutImage() {
        var data = arguments[0];
        var images = arguments[1] || '';

        var sucursal = $("#listaSucursales").val();
        var nombreSucursal = $("#listaSucursales option:selected").text();

        var datos = {
            sucursal: sucursal,
            data: data,
            images: images
        }
        evento.enviarEvento('Inventario/GuardaElementos', datos, '#panelAgregarElemento', function (respuesta) {
            evento.cerrarModal();
            if (respuesta.code == 200) {
                tabla.limpiarTabla("#data-table-elementos");

                $("#capturePageInventario").empty().append(respuesta.html);

                $("#titleResumenInventario").append("Inventario de " + nombreSucursal);
                tabla.generaTablaPersonal('#data-table-elementos', null, null, true, true, [[0, 'desc']]);
                initTableElementsView();

                $("#idSucursal").val(sucursal);

                $("#addElementPageInventario").fadeOut(400, function () {
                    $("#capturePageInventario").fadeIn(400);
                });

                initButtonsCapturePage();

            } else {
                evento.mostrarMensaje('#errorAddElementPage', false, 'No se pudo guardar la información. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
            }
        });
    }

    function initButtonsAddSubelementsPage() {
        $("#listElementosRegistrados").on("change", function () {
            $("#SubelementsList").empty();
            var _value = $(this).val();
            if (_value != "") {
                evento.enviarEvento('Inventario/ListaSubelementos', {elemento: _value}, '#panelAgregarSubelementos', function (respuesta) {
                    $("#SubelementsList").empty().append(respuesta.html);
                });
            }
        });
    }

    function initTableElementsView() {
        $('#data-table-elementos tbody').on('click', 'tr', function () {
            var datos = $('#data-table-elementos').DataTable().row(this).data();
            var data = {
                'registro': datos[0]
            };
            loadElementInfo(data);
        });

        $('#data-table-subelementos tbody').on('click', 'tr', function () {
            var datos = $('#data-table-subelementos').DataTable().row(this).data();
            var data = {
                'registro': datos[0]
            };
            loadSubElementInfo(data);
        });
    }

    function loadElementInfo() {
        var data = arguments[0];
        evento.enviarEvento('Inventario/CargaInfoElemento', {data: data}, '#panelResumenInventario', function (respuesta) {
            if (respuesta.code == 200) {

                $("#infoElementPage").empty().append(respuesta.html);

                $("#capturePageInventario").fadeOut(400, function () {
                    $("#infoElementPage").fadeIn(400);
                });

                initButtonsInfoElementPage(data);

            } else {
                evento.mostrarMensaje('#errorCapturePageInventario', false, 'No se pudo obtener la información del elemento. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
            }
        });
    }

    function loadSubElementInfo() {
        var data = arguments[0];
        evento.enviarEvento('Inventario/CargaInfoSublemento', {data: data}, '#panelResumenInventario', function (respuesta) {
            if (respuesta.code == 200) {

                $("#infoElementPage").empty().append(respuesta.html);

                $("#capturePageInventario").fadeOut(400, function () {
                    $("#infoElementPage").fadeIn(400);
                });

                initButtonsInfoSubelementPage();

            } else {
                evento.mostrarMensaje('#errorCapturePageInventario', false, 'No se pudo obtener la información del sub-elemento. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
            }
        });
    }

    function initButtonsInfoSubelementPage() {
        $("#infoElementPage #btnRegresar").off("click");
        $("#infoElementPage #btnRegresar").on("click", function () {
            $("#infoElementPage").fadeOut(400, function () {
                $("#capturePageInventario").fadeIn(400, function () {
                    $("#infoElementPage").empty();
                });
            });
        });

        $("#btnDeleteSubelemento").off("click");
        $("#btnDeleteSubelemento").on("click", function () {
            var id = $(this).attr('data-id');

            var mensaje = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>¿Está seguro de querer eliminar este sub-elemento?</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-sm btn-danger" id="btnAceptarDeleteSubelemento"><i class="fa fa-check"></i> Eliminar Subelemento</button>
                                <button type="button" class="btn btn-sm btn-default" id="btnCancelarDeleteSubelemento"><i class="fa fa-times"></i> Cancelar</button>
                            </div>
                        </div>`;

            evento.mostrarModal('Advertencia', mensaje);
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            $('#btnAceptarDeleteSubelemento').off('click');
            $('#btnAceptarDeleteSubelemento').on('click', function () {
                evento.cerrarModal();
                var data = {id: id};
                evento.enviarEvento('Inventario/EliminarSubelemento', data, '#panelInfoElemento', function (respuesta) {
                    if (respuesta.code == 200) {
                        showInventarioSucursal("infoElementPage", 'vistaSubelementos');
                    } else {
                        evento.mostrarMensaje('#errorInfoSubelemento', false, 'No se pudo eliminar el subelemento. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
                    }
                });
            });

            $('#btnCancelarDeleteSubelemento').off('click');
            $('#btnCancelarDeleteSubelemento').on('click', function () {
                evento.cerrarModal();
            });
        });
    }

    function initButtonsInfoElementPage() {
        let _data = arguments[0];
        let fileSubelement = new Upload();
        fileSubelement.crearUpload('#fotosSubelemento', 'Inventario/GuardaSubelementoFoto', null, false, [], '', null, false, 0, false, false, false, false, 0, false);
        var elemento = $("#id-elemento-h").val();
        $("#infoElementPage #btnRegresar").off("click");
        $("#infoElementPage #btnRegresar").on("click", function () {
            $("#infoElementPage").fadeOut(400, function () {
                $("#capturePageInventario").fadeIn(400, function () {
                    $("#infoElementPage").empty();
                });
            });
        });

        let fileEditarElemento = new Upload();
        var initialImages = $("#hiddenImagenes").val().split(",");
        fileEditarElemento.crearUpload('#fotosEditarElemento', 'Inventario/GuardaCambiosElemento', null, false, initialImages, 'Inventario/EliminarArchivoElemento', null, false, 0, false, false, {'elemento': elemento});
        $("#btnEditElemento").off("click");
        $("#btnEditElemento").on("click", function () {
            $("#infoReadOnly").fadeOut(400, function () {
                $("#formEditElement").fadeIn(400);
            });
        });

        $("#btnCancelarEditarElemento").off("click");
        $("#btnCancelarEditarElemento").on("click", function () {
            loadElementInfo(_data);
        });

        $("#btnDeleteElemento").off("click");
        $("#btnDeleteElemento").on("click", function () {
            var id = $(this).attr('data-id');

            var mensaje = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>¿Está seguro de querer eliminar este elemento?</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-sm btn-danger" id="btnAceptarDeleteElemento"><i class="fa fa-check"></i> Eliminar Elemento</button>
                                <button type="button" class="btn btn-sm btn-default" id="btnCancelarDeleteElemento"><i class="fa fa-times"></i> Cancelar</button>
                            </div>
                        </div>`;

            evento.mostrarModal('Advertencia', mensaje);
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            $('#btnAceptarDeleteElemento').off('click');
            $('#btnAceptarDeleteElemento').on('click', function () {
                evento.cerrarModal();
                var sucursal = $("#listaSucursales").val();
                var nombreSucursal = $("#listaSucursales option:selected").text();
                var data = {id: id, sucursal: sucursal};
                evento.enviarEvento('Inventario/EliminarElemento', data, '#panelInfoElemento', function (respuesta) {
                    if (respuesta.code == 200) {
                        tabla.limpiarTabla("#data-table-elementos");

                        $("#capturePageInventario").empty().append(respuesta.html);

                        $("#titleResumenInventario").append("Inventario de " + nombreSucursal);
                        tabla.generaTablaPersonal('#data-table-elementos', null, null, true, true, [[0, 'desc']]);
                        initTableElementsView();

                        $("#idSucursal").val(sucursal);

                        $("#infoElementPage").fadeOut(400, function () {
                            $("#capturePageInventario").fadeIn(400);
                        });

                        initButtonsCapturePage();
                    } else {
                        evento.mostrarMensaje('#errorInfoElemento', false, 'No se pudo eliminar el elemento. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
                    }
                });
            });

            $('#btnCancelarDeleteElemento').off('click');
            $('#btnCancelarDeleteElemento').on('click', function () {
                evento.cerrarModal();
            });
        });

        $("#btnGuardarCambiosElemento").off("click");
        $("#btnGuardarCambiosElemento").on("click", function () {
            if (evento.validarFormulario('#formEditElement')) {
                var datosOld = {
                    'ubicacion': $("#hiddenIdUbicacion").val(),
                    'sistema': $("#hiddenIdSistema").val(),
                    'elemento': $("#hiddenIdElemento").val(),
                    'serie': $("#hiddenSerie").val(),
                    'clave': $("#hiddenClave").val()
                }

                var datos = {
                    'registro': elemento,
                    'sucursal': $("#listaSucursales option:selected").val(),
                    'ubicacion': $("#listUbicaciones option:selected").val(),
                    'sistema': $("#listSistemas option:selected").val(),
                    'elemento': $("#listElementos option:selected").val(),
                    'serie': $("#serieElementoEditar").val(),
                    'clave': $("#claveElementoEditar").val()
                }

                var archivos = $("#fotosEditarElemento").val();
                let pasa = false;
                if (archivos != '') {
                    pasa = true;
                } else {
                    if (
                            datosOld.ubicacion != datos.ubicacion
                            || datosOld.sistema != datos.sistema
                            || datosOld.elemento != datos.elemento
                            || datosOld.serie != datos.serie
                            || datosOld.clave != datos.clave
                            ) {
                        pasa = true;
                    }
                }

                if (pasa) {
                    fileEditarElemento.enviarArchivos('#fotosEditarElemento', 'Inventario/GuardaCambiosElemento', '#panelInfoElemento', datos, function (respuesta) {
                        if (respuesta.code == 200) {
                            loadElementInfo(_data);
                        } else {
                            evento.mostrarMensaje('#errorCambiosElemento', false, 'No se ha podido guardar la información. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorCambiosElemento', false, 'No se han detectado cambios que guardar en el elemento.', 6000);
                }
            }
        });


        $("#btnGuardaSubelemento").off("click");
        $("#btnGuardaSubelemento").on("click", function () {
            var subelemento = $("#listSubelements option:selected").val();
            if (subelemento != '') {
                var datos = {
                    'sucursal': $("#listaSucursales option:selected").val(),
                    'elemento': elemento,
                    'subelemento': subelemento,
                    'subelementoS': $("#listSubelements option:selected").text(),
                    'serie': $("#serie-subelemento").val(),
                    'clave': $("#clave-subelemento").val()
                }
                fileSubelement.enviarArchivos('#fotosSubelemento', 'Inventario/GuardaSubelementoFoto', '#panelInfoElemento', datos, function (respuesta) {
                    if (respuesta.id != 'null') {
                        loadElementInfo(_data);
                    } else {
                        evento.mostrarMensaje('#errorAddSubelemento', false, 'Error al guardar el elemento. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
                    }
                });
            } else {
                evento.mostrarMensaje('#errorAddSubelemento', false, 'Debe seleccionar un sub-elemento para guardar el registro.', 6000);
            }
        });


        $("#btnLimpiaFormularioSubelemento").off("click");
        $("#btnLimpiaFormularioSubelemento").on("click", function () {
            loadElementInfo(_data);
        });

        $('[data-toggle="tooltip"]').tooltip();

        initButtonsDeleteSubelemento();

    }

    function initButtonsDeleteSubelemento() {
        $(".btnDeleteSubelemento").off("click");
        $(".btnDeleteSubelemento").on("click", function () {
            var id = $(this).attr('data-id');

            var mensaje = `<div class="row">
                            <div class="col-md-12 text-center">
                                <p>¿Está seguro de querer eliminar este sub-elemento?</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-sm btn-danger" id="btnAceptarDeleteSubelemento"><i class="fa fa-check"></i> Eliminar Subelemento</button>
                                <button type="button" class="btn btn-sm btn-default" id="btnCancelarDeleteSubelemento"><i class="fa fa-times"></i> Cancelar</button>
                            </div>
                        </div>`;

            evento.mostrarModal('Advertencia', mensaje);
            $('#btnModalConfirmar').addClass('hidden');
            $('#btnModalAbortar').addClass('hidden');
            $('#btnAceptarDeleteSubelemento').off('click');
            $('#btnAceptarDeleteSubelemento').on('click', function () {
                evento.cerrarModal();
                var data = {id: id};
                evento.enviarEvento('Inventario/EliminarSubelemento', data, '#panelInfoElemento', function (respuesta) {
                    if (respuesta.code == 200) {
                        $(".subelemento-" + id).remove();
                    } else {
                        evento.mostrarMensaje('#errorAddSubelemento', false, 'No se pudo eliminar el subelemento. Intente de nuevo en unos momentos y si el problema persiste, contacte al administrador.', 6000);
                    }
                });
            });

            $('#btnCancelarDeleteSubelemento').off('click');
            $('#btnCancelarDeleteSubelemento').on('click', function () {
                evento.cerrarModal();
            });
        });
    }

});