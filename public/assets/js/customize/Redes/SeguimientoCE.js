$(function () {

    let peticion = new Utileria();
    let modal = new Modal();
    let fecha = new Fecha();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();

    let tablaPrincipal = new TablaBasica('table-ServiciosGeneralesRedes');

    let tablaNodos = null;
    let tablaMateriales = null;
    let tablaAgregarMateriales = null;
    let selectSucursal = null;
    let actualizarEvidencia = null;
    let selectArea = null;
    let selectSwitch = null;
    let selectTipoMaterial = null;
    let selectMaterial = null;
    let evidenciaMaterial = null;
    let evidenciaProblema = null;
    let evidenciaFija = null;
    let collapseNotas = null;

    let firmaClienet = new DrawingBoard.Board("firmaCliente", {
        background: "#fff",
        color: "#000",
        size: 1,
        controlsPosition: "right",
        controls: [
            {
                Navigation: {
                    back: false,
                    forward: false
                }
            }
        ],
        webStorage: false
    });
    let firmaTecnico = new DrawingBoard.Board("firmaTecnico", {
        background: "#fff",
        color: "#000",
        size: 1,
        controlsPosition: "right",
        controls: [
            {
                Navigation: {
                    back: false,
                    forward: false
                }
            }
        ],
        webStorage: false
    });

    let datoServicioTabla = {
        id: null,
        tipo: null,
        folio: null,
        idSucursal: null,
        nombreCliente: null
    };
    let  infoMaterialNodo = {
        id: null,
        tipo: null,
        area: null,
        nodo: null,
        switch : null,
        numSwitch: null,
        material: null
    };
    let materialTecnico = null;
    let censoSwitches = null;
    let areasSucursales = null;
    let listaTotalNodos = null;
    let listaTotalMaterialUsado = null;
    let evidenciasNodo = null;
    let archivosEstablecidos = null;
    let idNodo = null;
    let validacion = null;
    let tieneFirmas = null;

    tablaPrincipal.evento(function () {
        let tamañoDatosFila = 0, datosFila = tablaPrincipal.datosFila(this);

        let nombre = $('#nombreTrabajador').text();
        let acceso = $('#accesoTrabajador').text();

        $.each(datosFila, function () {
            tamañoDatosFila += 1;
        });

        datoServicioTabla.id = datosFila[0];
        datoServicioTabla.tipo = datosFila[4];
        datoServicioTabla.folio = datosFila[1];

        if (datosFila[tamañoDatosFila - 1] === "ABIERTO") {
            modal.mostrarModal('Iniciar Servicio', '<h3>¿Quieres atender el servicio?</h3>');
            $('#btnAceptar').on('click', function () {
                peticion.enviar('panelServicios', 'SeguimientoCE/SeguimientoGeneral/Atender', datoServicioTabla, function (respuesta) {
                    if (!validarError(respuesta)) {
                        return;
                    }
                    modal.cerrarModal();
                    cambioVistaSinMaterial(respuesta.solucion);
                    cambioVistaNodos(respuesta);
                });
            });
        } else {
            peticion.enviar('panelServicios', 'SeguimientoCE/SeguimientoGeneral/Seguimiento/' + datosFila[4], datoServicioTabla, function (respuesta) {
                if (!validarError(respuesta)) {
                    return;
                }
                if (respuesta.firmas != null) {
                    tieneFirmas = true;
                } else {
                    tieneFirmas = false;
                }
                cambioVistaSinMaterial(respuesta.solucion);
                cambioVistaNodos(respuesta);
                if (acceso === '1' && nombre !== datosFila[6]) {
                    $('.bloqueoConclusion').prop("disabled", true);
                    $('.bloqueoConclusionBtn').addClass('hidden');
                    $('#table-materialNodo tbody').off("click");
                    validacion = "EN VALIDACIÓN";
                }
                if (datosFila[tamañoDatosFila - 1] === "EN VALIDACIÓN") {
                    $('.bloqueoConclusion').prop("disabled", true);
                    $('.bloqueoConclusionBtn').addClass('hidden');
                    $('#scciones').removeClass('hidden');
                    $('#table-materialNodo tbody').off("click");
                    validacion = "EN VALIDACIÓN";
                }

            });
        }
    });

    function cambioVistaSinMaterial(datosSolucion) {
        if (datosSolucion.solucion.length > 0) {
            if (datosSolucion.solucion[0].Archivos !== "") {
                $('#btnConMaterial').addClass('hidden');
                $('#btnSinMaterial').removeClass('hidden');
                $('#sinMaterial').removeClass('hidden');
                $('#conMaterial').addClass('hidden');
                archivosEstablecidos = datosSolucion.solucion[0].Archivos;
                cargarEvidenciaArchivos();
            }
        }
    }

    function cambioVistaNodos(infoServicio) {
        $('#contentServiciosGeneralesRedes').addClass('hidden');
        $('#contentServiciosRedes').removeClass('hidden');
        listaTotalNodos = infoServicio.solucion.nodos;
        censoSwitches = infoServicio.datosServicio.censoSwitch;
        areasSucursales = infoServicio.datosServicio.areasSucursal;
        materialTecnico = infoServicio.datosServicio.materialAlmacen;
        listaTotalMaterialUsado = infoServicio.solucion.totalMaterial;
        iniciarObjetos();
        if (infoServicio.servicio.Folio !== 0 && infoServicio.servicio.Folio !== null) {
            mostrarElementosAgregarFolio();
            mostrarInformacionFolio(infoServicio.folio);
            arreglarNotas(infoServicio.notasFolio);
        }

        if (infoServicio.sucursales.length > 0) {
            selectSucursal.cargaDatosEnSelect(infoServicio.sucursales);
            selectSucursal.definirValor(infoServicio.solucion.IdSucursal);
        }

        if (infoServicio.problemas !== null) {
            cargarContenidoProblemas(infoServicio.problemas);
        }

        cargarContenidoServicio(infoServicio.servicio);
        cargarContenidoSolucion(infoServicio.solucion);
        cargarContenidoModalMaterial(infoServicio.datosServicio);
        cargarContenidoTablaNodos();
        cargarContenidoTablaMaterial(infoServicio.solucion.totalMaterial);
        eventosTablas();
        ocultarElementosDefault(infoServicio.solucion, infoServicio.firmas);
        $('html, body').animate({
            scrollTop: $("#contentServiciosRedes").offset().top - 50
        }, 600);
    }

    function iniciarObjetos() {
        tablaNodos = new TablaBasica('table-nodo');
        tablaMateriales = new TablaBasica('table-material');
        tablaAgregarMateriales = new TablaBasica('table-materialNodo');
        selectSucursal = new SelectBasico('selectSucursal');
        selectArea = new SelectBasico('selectArea');
        selectSwitch = new SelectBasico('selectSwith');
        selectTipoMaterial = new SelectBasico('selectTipoMaterial');
        selectMaterial = new SelectBasico('selectMaterial');
        evidenciaMaterial = new FileUpload_Basico('agregarEvidenciaNodo', {url: 'SeguimientoCE/SeguimientoGeneral/Accion/agregarNodo', extensiones: ['jpg', 'jpeg', 'png']});
        evidenciaMaterial.iniciarFileUpload();
        evidenciaProblema = new FileUpload_Basico('agregarEvidenciaProblema', {url: 'SeguimientoCE/SeguimientoGeneral/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        evidenciaProblema.iniciarFileUpload();
        evidenciaFija = new FileUpload_Basico('agregarEvidenciaFija', {url: 'SeguimientoCE/SeguimientoGeneral/guardarSolucion', extensiones: ['jpg', 'jpeg', 'png']});
        evidenciaFija.iniciarFileUpload();
        actualizarEvidencia = new FileUpload_Basico('actualizarEvidenciaNodo', {url: 'SeguimientoCE/SeguimientoGeneral/Accion/actualizarNodo', extensiones: ['jpg', 'jpeg', 'png']});
        actualizarEvidencia.iniciarFileUpload();
        collapseNotas = new Collapse('collapseNotas');
        selectSucursal.iniciarSelect();
        selectArea.iniciarSelect();
        selectSwitch.iniciarSelect();
        selectTipoMaterial.iniciarSelect();
        selectMaterial.iniciarSelect();
    }

    function mostrarElementosAgregarFolio() {
        $('#infoServicio').removeClass('col-md-12');
        $('#infoServicio').addClass('col-md-6');
        $('#btnAgregarFolio').addClass('hidden');
        $('#agregarFolio').removeClass('hidden');
    }

    function mostrarInformacionFolio(infoFolio) {
        if (infoFolio.operacion !== false) {
            $('#infoFolio').removeClass('hidden');
            $('#infoFolio').attr('data-infoFolio', 'true');
            $('#infoFolio').attr('data-folio', infoFolio.WORKORDERID);
            $('#editarFolio').removeClass('hidden');
            $('#eliminarFolio').removeClass('hidden');
            $('#guardarFolio').addClass('hidden');
            $('#cancelarFolio').addClass('hidden');
            $('#errorFolio').empty();
            $('#addFolio').val(infoFolio.WORKORDERID).prop("disabled", true);
            $("#creadoPorFolio").text(infoFolio.CREATEDBY);
            $("#fechaCreacionFolio").text(fecha.formatoFecha(infoFolio.CREATEDTIME));
            $("#solicitaFolio").text(infoFolio.REQUESTER);
            $("#prioridadFolio").text(infoFolio.PRIORITY);
            $("#asignadoFolio").text(infoFolio.TECHNICIAN);
            $("#estatusFolio").text(infoFolio.STATUS);
            $("#asuntoFolio").text(infoFolio.SHORTDESCRIPTION);
        } else {
            $('#infoFolio').addClass('hidden');
            $('#errorFolio').empty();
            $('#errorFolio').append('<div class="col-md-12"><br>\n\
                                            <form id="errorFolio">\n\
                                                <div class="form-group">\n\
                                                   <label class="col-md-10 semi-bold">' + infoFolio.Error + '</label>\n\
                                                </div>\n\
                                            </form><br><br><br>\n\
                                        </div>');
        }
    }

    function mostrarErrorFolio(error) {
        let mensajeError = arguments[0];
        let operacionFolio = arguments[1] = false;

        $('#formularioAgregarFolio').addClass('hidden');
        $('#infoFolio').addClass('hidden');
        $('#agregarFolio').append('<div class="notaFolioError" class="col-md-12">\n\
                                            <br>\n\
                                            <div class="col-md-10">\n\
                                                <label class="col-md-10">' + mensajeError + '<br></label>\n\
                                            </div>\n\
                                            <div class="col-md-2">\n\
                                                <a class="recargarFolio"><i data-toggle="tooltip" data-placement="top" data-title="Recargar" class="fa fa-2x fa-refresh  text-success"></i>Recargar</a>\n\
                                            </div>\n\
                                        </div>');
        $(".recargarFolio").on('click', function () {
            if (operacionFolio === false) {
                if ($('#infoFolio').attr('data-infoFolio') !== undefined) {
                    if ($('#infoFolio').attr('data-infoFolio') === 'true') {
                        $('#addFolio').val('');
                        $('#formularioAgregarFolio').removeClass('hidden');
                        $('#agregarFolio').removeClass('hidden');
                    } else {
                        $('#formularioAgregarFolio').removeClass('hidden');
                    }
                    $('#infoFolio').removeClass('hidden');
                } else {
                    $('#addFolio').val('');
                    $('#formularioAgregarFolio').removeClass('hidden');
                    $('#agregarFolio').removeClass('hidden');
                }
            } else {
                $('#addFolio').val('');
                $('#agregarFolio').removeClass('hidden');
            }
            $('.notaFolioError').addClass('hidden');
        });
    }

    function arreglarNotas(notas) {
        if (notas !== null) {
            let datos = [];
            let contador = 0;
            $.each(notas, function (key, value) {
                datos[contador] = {titulo: value.USERNAME, contenido: value.NOTESTEXT};
                contador++;
            });
            collapseNotas.multipleCollapse(datos);
        }
    }

    function cargarContenidoProblemas(infoProblemas) {
        $('#observacionesProblemas').empty();
        let problema = '';
        let evidencias = '';
        let icono = '';
        $.each(infoProblemas, function (key, value) {
            evidencias = '';
            icono = '';
            $.each(value.archivos, function (llave, valor) {
                if (llave === 0) {
                    icono = '<i class="fa fa-file-photo-o "></i>';
                } else {
                    icono = '';
                }
                evidencias += '<div class="col-md-1 col-sm-2 col-xs-1">\n\
                                <a href="' + valor + '" data-lightbox="problema' + key + '">' + icono + '</a>\n\
                            </div>';
            });
            problema += '<div class="problema' + key + ' col-md-12 row">\n\
                            <div class="col-md-6 col-sm-12">\n\
                                Usuario: <label class="semi-bold">' + value.usuario + '</label>\n\
                            </div>\n\
                            <div class="col-md-6 col-sm-12">\n\
                                Fecha:<label class="semi-bold">' + value.fecha + '</label>\n\
                            </div>\n\
                            <div class="col-md-11 col-sm-11 col-xs-10">\n\
                                <textarea class="form-control" rows="2" disabled>' + value.descripcion + '</textarea>\n\
                            </div>\n\
                            ' + evidencias + '\n\
                        </div><br><br><br><br><br><br>';
        });
        $('#observacionesProblemas').append(problema);
    }

    function cargarContenidoServicio(datosServicio) {
        $("#fechaServicio").text(datosServicio.FechaCreacion);
        $("#ticketServicio").text(datosServicio.Ticket);
        $("#atendidoServicio").text(datosServicio.Atiende);
        $("#solicitudServicio").text(datosServicio.idSolicitud);
        $("#textareaDescripcion").text(datosServicio.Descripcion);
        $("#solicitaSolicitud").text(datosServicio.Solicita);
        $("#fechaSolicitud").text(datosServicio.FechaSolicitud);
        $("#textareaDescripcionSolicitud").text(datosServicio.descripcionSolicitud);
    }

    function cargarContenidoSolucion(solucion) {
        if (solucion.solucion.length > 0) {
            $('#textareaObservaciones').text(solucion.solucion[0].Observaciones);
        }
    }

    /**Empiesan eventos del modal Material**/
    function cargarContenidoModalMaterial(materialNodo) {
        if (materialNodo.areasSucursal.length > 0) {
            selectArea.cargaDatosEnSelect(materialNodo.areasSucursal);
        }
        if (materialNodo.censoSwitch.length > 0) {
            selectSwitch.cargaDatosEnSelect(materialNodo.censoSwitch);
        }
        if (materialNodo.tipoMaterialAlmacen.length > 0) {
            selectTipoMaterial.cargaDatosEnSelect(materialNodo.tipoMaterialAlmacen);
            selectTipoMaterial.evento('change', function () {
                datoServicioTabla.tipoMaterial = selectTipoMaterial.obtenerValor();
                peticion.enviar('modalMaterialNodo', 'SeguimientoCE/SeguimientoGeneral/material', datoServicioTabla, function (respuesta) {
                    selectMaterial.cargaDatosEnSelect(respuesta.materialAlmacen);
                });
            });
        }
        if (materialNodo.materialAlmacen.length > 0) {
            selectMaterial.cargaDatosEnSelect(materialNodo.materialAlmacen);
        }
    }

    $('#btnAgregarMaterialATablaNodo').on('click', function () {
        if (evento.validarFormulario('#formMaterial')) {
            if (parseFloat($('#materialUtilizar').val()) > 0) {
                let resta = null;
                $.each(materialTecnico, function (key, value) {
                    if (value.id === selectMaterial.obtenerValor()) {
                        resta = parseFloat(value.cantidad) - parseFloat($('#materialUtilizar').val());
                        value.cantidad = resta;
                    }
                });
                tablaAgregarMateriales.agregarDatosFila([
                    selectMaterial.obtenerValor(),
                    selectMaterial.obtenerTexto(),
                    $('#materialUtilizar').val()
                ]);
                selectMaterial.limpiarElemento();
                $('#materialUtilizar').val('');
            } else {
                $("#notaMaterial").removeClass("hidden").delay(4000).queue(function (next) {
                    $(this).addClass("hidden");
                    next();
                });
            }
        }
    });

    $('#btnAceptarAgregarMaterial').on('click', function () {
        let infoTabla = tablaAgregarMateriales.validarNumeroFilas();
        if (evento.validarFormulario('#formDatosNodo') && infoTabla == true) {
            infoMaterialNodo.id = datoServicioTabla.id;
            infoMaterialNodo.tipo = datoServicioTabla.tipo;
            infoMaterialNodo.area = selectArea.obtenerValor();
            infoMaterialNodo.nodo = $('#inputNodo').val();
            infoMaterialNodo.switch = selectSwitch.obtenerValor();
            infoMaterialNodo.numSwitch = $('#inputNumSwith').val();
            infoMaterialNodo.material = null;

            $.each(tablaAgregarMateriales.datosTabla(), function (key, value) {
                if (infoMaterialNodo.material === null) {
                    infoMaterialNodo.material = '{"idMaterial": ' + value[0] + ', "cantidad": ' + value[2] + '}';
                } else {
                    infoMaterialNodo.material += '|{"idMaterial": ' + value[0] + ', "cantidad": ' + value[2] + '}';
                }
            });

            try {
                evidenciaMaterial.validarArchivos()
                infoMaterialNodo.evidencias = true;
                evidenciaMaterial.enviarPeticionServidor('modalMaterialNodo', infoMaterialNodo, function (respuesta) {
                    if (!validarError(respuesta, 'modalMaterialNodo')) {
                        return;
                    }
                    limpiarElementosModalMaterial();
                    tablaNodos.limpiartabla();
                    listaTotalNodos = respuesta.solucion.nodos;
                    listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
                    materialTecnico = respuesta.datosServicio.materialAlmacen;
                    cargarContenidoModalMaterial(respuesta.datosServicio);
                    cargarContenidoTablaNodos();
                    cargarContenidoTablaMaterial(respuesta.solucion.totalMaterial);
                    ocultarElementosDefault(respuesta.solucion);
                    $('#modalMaterialNodo').modal('hide');
                });
            } catch (exception) {
                infoMaterialNodo.evidencias = false;
                peticion.enviar('modalMaterialNodo', 'SeguimientoCE/SeguimientoGeneral/Accion/agregarNodo', infoMaterialNodo, function (respuesta) {
                    if (!validarError(respuesta, 'modalMaterialNodo')) {
                        return;
                    }
                    limpiarElementosModalMaterial();
                    tablaNodos.limpiartabla();
                    listaTotalNodos = respuesta.solucion.nodos;
                    listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
                    materialTecnico = respuesta.datosServicio.materialAlmacen;
                    cargarContenidoModalMaterial(respuesta.datosServicio);
                    cargarContenidoTablaNodos();
                    ocultarElementosDefault(respuesta.solucion, respuesta.firmas);
                    $('#modalMaterialNodo').modal('toggle');
                });
            }
        } else {
            $("#notaAgregarMaterial").removeClass("hidden").delay(4000).queue(function (next) {
                $(this).addClass("hidden");
                next();
            });
        }
    });

    $('#btnCancelarAgregarMaterial').on('click', function () {
        limpiarElementosModalMaterial();
        restaurarElementosModal();
    });

    $('#btnActualizarAgregarMaterial').on('click', function () {
        infoMaterialNodo.id = datoServicioTabla.id;
        infoMaterialNodo.tipo = datoServicioTabla.tipo;
        infoMaterialNodo.idNodo = idNodo;
        infoMaterialNodo.area = selectArea.obtenerValor();
        infoMaterialNodo.nodo = $('#inputNodo').val();
        infoMaterialNodo.switch = selectSwitch.obtenerValor();
        infoMaterialNodo.numSwitch = $('#inputNumSwith').val();
        infoMaterialNodo.material = null;

        $.each(tablaAgregarMateriales.datosTabla(), function (key, value) {
            if (infoMaterialNodo.material === null) {
                infoMaterialNodo.material = '{"idMaterial": ' + value[0] + ', "cantidad": ' + value[2] + '}';
            } else {
                infoMaterialNodo.material += '|{"idMaterial": ' + value[0] + ', "cantidad": ' + value[2] + '}';
            }
        });
        let evidenciaOpcional = $('#actualizarEvidenciaNodo').val();
        let infoTabla = tablaAgregarMateriales.validarNumeroFilas();
        if (infoTabla == true) {
            if (evidenciaOpcional === '') {
                infoMaterialNodo.archivos = evidenciaOpcional;
                infoMaterialNodo.evidencias = false;
                peticion.enviar('modalMaterialNodo', 'SeguimientoCE/SeguimientoGeneral/Accion/actualizarNodo', infoMaterialNodo, function (respuesta) {
                    respuestaNodos(respuesta);
                });
            } else if (evidenciaOpcional === null) {
                infoMaterialNodo.archivos = evidenciaOpcional;
                infoMaterialNodo.evidencias = false;
                peticion.enviar('modalMaterialNodo', 'SeguimientoCE/SeguimientoGeneral/Accion/actualizarNodo', infoMaterialNodo, function (respuesta) {
                    respuestaNodos(respuesta);
                });
            } else {
                infoMaterialNodo.archivos = evidenciaOpcional;
                infoMaterialNodo.evidencias = true;
                actualizarEvidencia.enviarPeticionServidor('modalMaterialNodo', infoMaterialNodo, function (respuesta) {
                    respuestaNodos(respuesta);
                });
            }
        } else {
            $("#notaAgregarMaterial").removeClass("hidden").delay(4000).queue(function (next) {
                $(this).addClass("hidden");
                next();
            });
        }
    });

    function respuestaNodos(respuesta) {
        limpiarElementosModalMaterial();
        restaurarElementosModal();
        listaTotalNodos = respuesta.solucion.nodos;
        listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
        materialTecnico = respuesta.datosServicio.materialAlmacen;
        cargarContenidoModalMaterial(respuesta.datosServicio);
        tablaNodos.limpiartabla();
        cargarContenidoTablaNodos();
        cargarContenidoTablaMaterial(respuesta.solucion.totalMaterial);
        $('#modalMaterialNodo').modal('hide');
    }

    $('#btnEliminarAgregarMaterial').on('click', function () {
        let datos = {};
        datos.id = datoServicioTabla.id;
        datos.tipo = datoServicioTabla.tipo;
        datos.idNodo = idNodo;
        let datosMaterial = [];
        $.each(listaTotalNodos, function (key, value) {
            if (value.IdNodo === idNodo) {
                datosMaterial.push(value);
            }
        });

        peticion.enviar('modalMaterialNodo', 'SeguimientoCE/SeguimientoGeneral/Accion/borrarNodo', datos, function (respuesta) {
            if (!validarError(respuesta, 'modalMaterialNodo')) {
                return;
            }
            limpiarElementosModalMaterial();
            restaurarElementosModal();
            $('#modalMaterialNodo').modal('hide');
            tablaNodos.limpiartabla();
            listaTotalNodos = respuesta.solucion.nodos;
            listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
            materialTecnico = respuesta.datosServicio.materialAlmacen;
            cargarContenidoModalMaterial(respuesta.datosServicio);
            cargarContenidoTablaNodos();
            cargarContenidoTablaMaterial(respuesta.solucion.totalMaterial);
        });
    });

    function  limpiarElementosModalMaterial() {
        selectArea.limpiarElemento();
        $('#inputNodo').val('');
        selectSwitch.limpiarElemento();
        $('#inputNumSwith').val('');
        selectMaterial.limpiarElemento();
        $('#materialUtilizar').val('');
        tablaAgregarMateriales.limpiartabla();
        evidenciaMaterial.limpiarElemento();
        actualizarEvidencia.limpiarElemento();
    }

    function restaurarElementosModal() {
        $('#imagenEvidencia').addClass('hidden');
        $('#btnActualizarAgregarMaterial').addClass('hidden');
        $('#btnEliminarAgregarMaterial').addClass('hidden');
        $('#btnAceptarAgregarMaterial').removeClass('hidden');
        $('#fileMostrarEvidencia').addClass('hidden');
        $('#fileEvidencia').removeClass('hidden');
        $('#fileEvidenciaActualizar').addClass('hidden');
        $('#evidenciasMaterialUtilizado').empty();
    }
    /**Finalizan eventos del modal Material**/

    function cargarContenidoTablaNodos() {
        let listaTemporalTotalNodos = JSON.parse(JSON.stringify(listaTotalNodos));
        $.each(listaTemporalTotalNodos, function (key, value) {
            $.each(areasSucursales, function (llave, valor) {
                if (value.IdArea === valor.id) {
                    value.IdArea = valor.text;
                }
            });
            $.each(censoSwitches, function (llave, valor) {
                if (value.IdSwitch === valor.id) {
                    value.IdSwitch = valor.text;
                }
            });
        });

        let hash = {};
        listaTemporalTotalNodos = listaTemporalTotalNodos.filter(function (cuenta) {
            var exists = !hash[cuenta.IdNodo] || false;
            hash[cuenta.IdNodo] = true;
            return exists;
        });

        $.each(listaTemporalTotalNodos, function (key, value) {
            tablaNodos.agregarDatosFila([
                value.IdNodo,
                value.IdArea,
                value.Nombre,
                value.IdSwitch,
                value.NumeroSwitch
            ]);
        });
    }

    function cargarContenidoTablaMaterial(materialUsado) {
        tablaMateriales.limpiartabla();
        $.each(materialUsado, function (key, value) {
            tablaMateriales.agregarDatosFila([
                value.TipoProducto,
                value.Producto,
                value.Cantidad
            ]);
        });
    }

    function eventosTablas() {
        tablaNodos.evento(function () {
            let datosNodo = tablaNodos.datosTabla();
            if (datosNodo.length !== 0) {
                let datos = tablaNodos.datosFila(this);
                $('#modalMaterialNodo').modal().show();
                $('#imagenEvidencia').removeClass('hidden');
                $('#btnAceptarAgregarMaterial').addClass('hidden');
                $('#btnActualizarAgregarMaterial').removeClass('hidden');
                $('#btnEliminarAgregarMaterial').removeClass('hidden');
                $('#fileMostrarEvidencia').removeClass('hidden');
                $('#fileEvidencia').addClass('hidden');
                $('#fileEvidenciaActualizar').removeClass('hidden');
                actualizarContenidoModalMaterial(datos[0]);
            }
        });

        tablaAgregarMateriales.evento(function () {
            tablaAgregarMateriales.eliminarFila(this);
            $('#materialUtilizar').val('');
        });
    }

    function actualizarContenidoModalMaterial(id) {
        evidenciasNodo = null
        idNodo = id;
        let listaTemporalNodos = [], evidencias = '';
        $.each(listaTotalNodos, function (key, value) {
            if (value.IdNodo === id) {
                listaTemporalNodos.push(value);
            }
        });
        selectArea.definirValor(listaTemporalNodos[0].IdArea);
        $('#inputNodo').val(listaTemporalNodos[0].Nombre);
        selectSwitch.definirValor(listaTemporalNodos[0].IdSwitch);
        $('#inputNumSwith').val(listaTemporalNodos[0].NumeroSwitch);
        if (listaTemporalNodos[0].Archivos !== "") {
            evidenciasNodo = listaTemporalNodos[0].Archivos.split(',');
            $.each(evidenciasNodo, function (key, value) {
                if (value !== '') {
                    evidencias += '<div id="img-' + key + '" class="evidencia">\n\
                                    <a href="' + value + '" data-lightbox="evidencias">\n\
                                        <img src ="' + value + '" />\n\
                                    </a>\n\
                                    <div class="eliminarEvidencia bloqueoConclusionBtn" data-value="' + value + '" data-key="' + key + '">\n\
                                        <a href="#">\n\
                                            <i class="fa fa-trash text-danger"></i>\n\
                                        </a>\n\
                                    </div>\n\
                                </div>';
                }
            });
            $('#evidenciasMaterialUtilizado').append(evidencias);
            if (validacion === "EN VALIDACIÓN") {
                $('.bloqueoConclusionBtn').addClass('hidden');
                tablaAgregarMateriales.evento(function () {
                    console.log(validacion);
                });
            }
        }
        $.each(listaTemporalNodos, function (key, value) {
            $.each(materialTecnico, function (llave, valor) {
                if (value.IdMaterialTecnico == valor.id)
                    tablaAgregarMateriales.agregarDatosFila([
                        value.IdMaterialTecnico,
                        valor.text,
                        value.Cantidad
                    ]);
            });
        });

        $('.eliminarEvidencia').on('click', function () {
            let archivo = $(this).attr('data-value');
            let indice = $(this).attr('data-key');
            $.each(evidenciasNodo, function (key, value) {
                if (key == indice) {
                    delete evidenciasNodo[key];
                }
            });

            datoServicioTabla.evidencia = archivo;
            datoServicioTabla.idNodo = idNodo;
            peticion.enviar('modalMaterialNodo', 'SeguimientoCE/SeguimientoGeneral/Accion/borrarArchivo', datoServicioTabla, function (respuesta) {
                listaTotalNodos = respuesta.solucion.nodos;
                $(`#img-${indice}`).addClass('hidden');
            });
        });
    }

    function cargarEvidenciaArchivos() {
        if (archivosEstablecidos !== "") {
            let evidencias = '', archivos = archivosEstablecidos.split(',');
            $.each(archivos, function (key, value) {
                evidencias += '<div id="img-' + key + '" class="evidencia">\n\
                                <a href="' + value + '" data-lightbox="evidencias">\n\
                                    <img src ="' + value + '" />\n\
                                </a>\n\
                            </div>';
            });
            $('#evidenciasMaterialFija').append(evidencias);
        }
    }

    function ocultarElementosDefault(solucion, firmas = null) {
        if (solucion.solucion.length > 0) {
            if (solucion.solucion[0].Archivos == "" && solucion.nodos.length == 0 || solucion.IdSucursal == null) {
                $('#btnConcluir').addClass('hidden');
            } else {
                $('#btnConcluir').removeClass('hidden');
            }
        } else {
            $('#btnConcluir').addClass('hidden');
        }
        if (firmas !== null) {
            $('#firmaExistenteCliente').empty();
            $('#firmaExistenteTecnico').empty();
            $('#firmasExistentes').removeClass('hidden');
            let firma = firmas.split(',');
            $('#firmaExistenteCliente').append('<img src ="' + firma[0] + '" />');
            $('#firmaExistenteTecnico').append('<img src ="' + firma[1] + '" />');
    }
    }

    /**Empiezan eventos de botones del encabezado**/
    $('#btnRegresar').on('click', function () {
        location.reload();
    });

    $('#btnAgregarFolio').on('click', function () {
        mostrarElementosAgregarFolio();
    });
    /**Finalizan eventos de botones del encabezado**/

    /**Empiezan eventos de botones para folio**/
    $('#guardarFolio').off('click');
    $('#guardarFolio').on('click', function () {
        datoServicioTabla.folio = $('#addFolio').val();
        if (evento.validarFormulario('#folio')) {
            peticion.enviar('panelServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Folio/guardar', datoServicioTabla, function (respuesta) {
                if (respuesta.nuevoFolio !== false) {
                    if (respuesta.operacionFolio) {
                        mostrarElementosAgregarFolio();
                        mostrarInformacionFolio(respuesta.folio);
                        arreglarNotas(respuesta.notasFolio);
                    } else {
                        mostrarErrorFolio(respuesta.errorFolio.Error, respuesta.operacionFolio);
                    }
                } else {
                    mostrarErrorFolio(respuesta.errorFolio.Error, respuesta.operacionFolio);
                }
            });
        }
    });

    $('#editarFolio').on('click', function () {
        $('#addFolio').prop('disabled', false);
        $('#editarFolio').addClass('hidden');
        $('#eliminarFolio').addClass('hidden');
        $('#guardarFolio').removeClass('hidden');
        $('#cancelarFolio').removeClass('hidden');
    });

    $('#cancelarFolio').off('click');
    $('#cancelarFolio').on('click', function () {
        if (datoServicioTabla.folio !== '' && datoServicioTabla.folio !== '0') {
            $('#addFolio').prop('disabled', true);
            $('#editarFolio').removeClass('hidden');
            $('#eliminarFolio').removeClass('hidden');
            $('#guardarFolio').addClass('hidden');
            $('#cancelarFolio').addClass('hidden');
        } else {
            ocultarElementosAgregarFolio();
        }
    });

    $('#eliminarFolio').on('click', function () {
        modal.mostrarModal('Eliminar Folio', '<h4>¿Estas Seguro de eliminar este FOLIO?</h4>');
        $('#btnAceptar').on('click', function () {
            datoServicioTabla.folio = '';
            peticion.enviar('panelServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/eliminarFolio', datoServicioTabla, function (respuesta) {
                $('#addFolio').prop('disabled', false);
                $('#addFolio').val('');

                ocultarElementosAgregarFolio();
                $("#creadoPorFolio").empty();
                $("#fechaCreacionFolio").empty();
                $("#solicitaFolio").empty();
                $("#prioridadFolio").empty();
                $("#asignadoFolio").empty();
                $("#estatusFolio").empty();
                $("#asuntoFolio").empty();
                $('#editarFolio').addClass('hidden');
                $('#guardarFolio').removeClass('hidden');

            });
            modal.cerrarModal();
        });
    });

    function ocultarElementosAgregarFolio() {
        $('#infoServicio').addClass('col-md-12');
        $('#infoServicio').removeClass('col-md-6');
        $('#infoFolio').addClass('hidden');
        $('#btnAgregarFolio').removeClass('hidden');
        $('#agregarFolio').addClass('hidden');
        $('#addFolio').val('');
    }
    /**Finalizan eventos de botones para folio**/

    /**Empiezan eventos de botones para ver detalles de servicio y folio**/
    $('#masDetalles').on('click', function () {
        $('#masDetalles').addClass('hidden');
        $('#menosDetalles').removeClass('hidden');
        $('#detallesServicio').removeClass('hidden');
    });

    $('#menosDetalles').on('click', function () {
        $('#masDetalles').removeClass('hidden');
        $('#menosDetalles').addClass('hidden');
        $('#detallesServicio').addClass('hidden');
    });

    $('#masDetallesFolio').on('click', function () {
        $('#masDetallesFolio').addClass('hidden');
        $('#menosDetallesFolio').removeClass('hidden');
        $('#detallesFolio').removeClass('hidden');
    });

    $('#menosDetallesFolio').on('click', function () {
        $('#masDetallesFolio').removeClass('hidden');
        $('#menosDetallesFolio').addClass('hidden');
        $('#detallesFolio').addClass('hidden');
    });
    /**Finalizan eventos de botones para ver detalles de servicio y folio**/

    /**Empiezan eventos de botones para datos y problemas**/
    $('#btnSinMaterial').on('click', function () {
        datoServicioTabla.idSucursal = selectSucursal.obtenerValor();
        if (archivosEstablecidos !== null) {
            modal.mostrarModal('Aviso', '<h4>Si realizas esta acción se Borrara la Evidencia y cambios guardados</h4>');

            modal.btnAceptar('btnAceptar', function () {
                peticion.enviar('modal-dialogo', 'SeguimientoCE/SeguimientoGeneral/borrarEvidencias', datoServicioTabla, function (respuesta) {
                    if (!validarError(respuesta, 'modal-dialogo')) {
                        return;
                    }
                    modal.cerrarModal();
                    cambioBtnSinMaterial();
                    $('#evidenciasMaterialFija').empty();
                    $('#btnConcluir').addClass('hidden');
                    archivosEstablecidos = null;
                });
            });
        } else {
            cambioBtnSinMaterial();
        }
    });

    function cambioBtnSinMaterial() {
        $('#btnConMaterial').removeClass('hidden');
        $('#btnSinMaterial').addClass('hidden');
        $('#sinMaterial').addClass('hidden');
        $('#conMaterial').removeClass('hidden');
    }

    $('#btnConMaterial').on('click', function () {
        datoServicioTabla.idSucursal = selectSucursal.obtenerValor();
        if (listaTotalNodos.length > 0) {
            modal.mostrarModal('Aviso', '<h4>Si realizas esta acción se Borrara la Información y cambios guardados</h4>');

            modal.btnAceptar('btnAceptar', function () {
                peticion.enviar('modal-dialogo', 'SeguimientoCE/SeguimientoGeneral/Accion/borrarNodos', datoServicioTabla, function (respuesta) {
                    if (!validarError(respuesta, 'modal-dialogo')) {
                        return;
                    }
                    listaTotalNodos = respuesta.solucion.nodos;
                    materialTecnico = respuesta.datosServicio.materialAlmacen;
                    listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
                    cargarContenidoModalMaterial(respuesta.datosServicio);
                    ocultarElementosDefault(respuesta.solucion);
                    tablaNodos.limpiartabla();
                    cambioBtnComMaterial()
                    modal.cerrarModal();
                });
            });
        } else {
            cambioBtnComMaterial();
        }
    });

    function cambioBtnComMaterial() {
        $('#btnConMaterial').addClass('hidden');
        $('#btnSinMaterial').removeClass('hidden');
        $('#sinMaterial').removeClass('hidden');
        $('#conMaterial').addClass('hidden');
    }

    $('#btnAceptarProblema').on('click', function () {
        if (evento.validarFormulario('#formEvidenciaProblema')) {
            datoServicioTabla.descripcion = $('#textareaDescProblema').val();
            evidenciaProblema.enviarPeticionServidor('#modalDefinirProblema', datoServicioTabla, function (respuesta) {
                if (!validarError(respuesta, 'modalDefinirProblema')) {
                    return;
                }
                mostrarInformacionFolio(respuesta.folio);
                arreglarNotas(respuesta.notasFolio);
                cargarContenidoProblemas(respuesta.problemas);
                $('#textareaDescProblema').val('');
                evidenciaProblema.limpiarElemento();
                $('#modalDefinirProblema').modal('hide');
            });
        }
    });

    $('#btnVerMaterial').on('click', function () {
        $('#btnVerMaterial').addClass('hidden');
        $('#vistaNodos').addClass('hidden');
        $('#btnVerNodos').removeClass('hidden');
        $('#vistaMaterialUsado').removeClass('hidden');
    });

    $('#btnVerNodos').on('click', function () {
        $('#btnVerNodos').addClass('hidden');
        $('#vistaMaterialUsado').addClass('hidden');
        $('#btnVerMaterial').removeClass('hidden');
        $('#vistaNodos').removeClass('hidden');
    });
    /**Finalizan eventos de botones para datos y problemas**/

    /**Empiezan seccion de botones generales**/
    $('#btnGuardar').on('click', function () {
        if (evento.validarFormulario('#formDatosSolucion')) {
            let validarImagen = $('#agregarEvidenciaFija').val();
            datoServicioTabla.observaciones = $('#textareaObservaciones').val();
            datoServicioTabla.idSucursal = selectSucursal.obtenerValor();
            if (validarImagen == '') {
                datoServicioTabla.material = false;
                peticion.enviar('panelServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/guardarSolucion', datoServicioTabla, function (respuesta) {
                    if (!validarError(respuesta)) {
                        return;
                    }
                    modal.mostrarModal("Exito", '<h4>Se han guardado los cambios correctamente</h4>');
                    $('#btnAceptar').addClass('hidden');
                });
            } else {
                datoServicioTabla.material = true;
                evidenciaFija.enviarPeticionServidor('#contentServiciosGeneralesRedes', datoServicioTabla, function (respuesta) {
                    if (!validarError(respuesta)) {
                        return;
                    }
                    modal.mostrarModal("Exito", '<h4>Se han guardado los cambios correctamente</h4>');
                    $('#btnAceptar').addClass('hidden');
                    evidenciaFija.limpiarElemento();
                    $('#evidenciasMaterialFija').empty();
                    archivosEstablecidos = respuesta.solucion.solucion[0].Archivos;
                    ocultarElementosDefault(respuesta.solucion);
                    cargarEvidenciaArchivos();
                    $('#btnAceptar').addClass('hidden');
                });
            }
        }
    });

    $('#btnConcluir').on('click', function () {
        let faltaEvidencia = true;

        if (listaTotalNodos.length > 0) {
            $.each(listaTotalNodos, function (key, value) {
                if (value.Archivos == "") {
                    faltaEvidencia += 1;
                    modal.mostrarModal('AVISO', '<h4>El Nodo <b>' + value.Nombre + '</b> no tiene evidencia</h4>');
                    $('#btnAceptar').addClass('hidden');
                    faltaEvidencia = false;
                }
            });
            if (faltaEvidencia === true) {
                $('#contentFirmasConclucion').removeClass('hidden');
                $('#contentServiciosRedes').addClass('hidden');
            }
        } else {
            if (archivosEstablecidos !== null) {
                $('#contentFirmasConclucion').removeClass('hidden');
                $('#contentServiciosRedes').addClass('hidden');
            }
        }
    });

    $('#validarServicio').on('click', function () {
        peticion.enviar('panelServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/validarServicio', datoServicioTabla, function (respuesta) {
            if (!validarError(respuesta)) {
                return;
            }
            modal.mostrarModal("Exito", '<h4>Servicio validado correctamente</h4>');
            $('#btnAceptar').addClass('hidden');
            modal.btnAceptar('btnCerrar', function () {
                modal.cerrarModal();
                location.reload();
            });
        });
    });

    $('#rechazarServicio').on('click', function () {
        peticion.enviar('panelServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/rechazarServicio', datoServicioTabla, function (respuesta) {
            if (!validarError(respuesta)) {
                return;
            }

            modal.mostrarModal("Exito", '<h4>Servicio En Atención Nuevamente</h4>');
            $('#btnAceptar').addClass('hidden');
            modal.btnAceptar('btnCerrar', function () {
                modal.cerrarModal();
                location.reload();
            });
        });
    });
    /**Finalizan seccion de botones generales**/

    $('#btnRegresarServicio').on('click', function () {
        $('#contentServiciosRedes').removeClass('hidden');
        $('#contentFirmasConclucion').addClass('hidden');
    });

    $('#btnRegresarServicio2').on('click', function () {
        $('#contentfirmaCliente').removeClass('hidden');
        $('#btnRegresarServicio').removeClass('hidden');
        $('#contentfirmaTecnico').addClass('hidden');
        $('#btnRegresarServicio2').addClass('hidden');
    });

    $('#btnTerminar').on('click', function () {
        let imgFirmaCliente = firmaClienet.getImg();
        let inputFirmaCliente = (firmaClienet.blankCanvas == imgFirmaCliente) ? '' : imgFirmaCliente;
        if (inputFirmaCliente == '') {
            evento.mostrarMensaje("#errorMessageFirmaCliente", false, 'Falta firma del Cliente', 2000);
        } else {
            datoServicioTabla.nombreCliente = $('#inputCliente').val()
            datoServicioTabla.firmaCliente = firmaClienet.getImg();
            datoServicioTabla.nodos = listaTotalNodos;

            peticion.enviar('panelFirmas', 'SeguimientoCE/SeguimientoGeneral/concluir', datoServicioTabla, function (respuesta) {
                if (!validarError(respuesta)) {
                    return;
                }
                modal.mostrarModal("Exito", '<h4>Se han concluido el servicio correctamente</h4>');
                $('#btnCerrar').addClass('hidden');
                modal.btnAceptar('btnAceptar', function () {
                    modal.cerrarModal();
                    location.reload();
                });
            });
        }
    });

    $('#exportarPDF').on('click', function () {
        peticion.enviar('panelServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/exportarPDF', datoServicioTabla, function (respuesta) {
            if (!validarError(respuesta)) {
                return;
            }
            window.open(respuesta.PDF, '_blank');
        });
    });

    function validarError(respuesta, objeto = null) {
        if (respuesta === undefined) {
            if (objeto !== null) {
                $(`#${objeto}`).modal('hide');
            }
            modal.mostrarModal('Error', '<h3>Ocurrió un problema en la petición. Intentalo mas tarde</h3>');
            $('#btnAceptar').addClass('hidden');
            return false;
        }
        return true;
    }

});
