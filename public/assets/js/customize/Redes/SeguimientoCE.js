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
    let selectArea = null;
    let selectSwitch = null;
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
//    let datoServicioGral = {
//        sucursal: null,
//        observaciones: null
//    }
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
    let idNodo = null;

    tablaPrincipal.evento(function () {
        let tamañoDatosFila = 0, datosFila = tablaPrincipal.datosFila(this);

        $.each(datosFila, function () {
            tamañoDatosFila += 1;
        });

        datoServicioTabla.id = datosFila[0];
        datoServicioTabla.tipo = datosFila[4];
        if (datosFila[1] !== '' || datosFila[1] !== 0) {
            datoServicioTabla.folio = datosFila[1];
        }

        if (datosFila[tamañoDatosFila - 1] === "ABIERTO") {
            modal.mostrarModal('Iniciar Servicio', '<h3>¿Quieres atender el servicio?</h3>');
            $('#btnAceptar').on('click', function () {
                peticion.enviar('panelServicios', 'SeguimientoCE/SeguimientoGeneral/Atender/' + datosFila[4], datoServicioTabla, function (respuesta) {
                    modal.cerrarModal();
                    cambioVista(respuesta);
                });
            });
        } else {
            peticion.enviar('panelServicios', 'SeguimientoCE/SeguimientoGeneral/Seguimiento/' + datosFila[4], datoServicioTabla, function (respuesta) {
                cambioVista(respuesta);
                console.log(respuesta)
            });
        }
    });

    function cambioVista(infoServicio) {
        $('#contentServiciosGeneralesRedes').addClass('hidden');
        $('#contentServiciosRedes').removeClass('hidden');
        listaTotalNodos = infoServicio.solucion.nodos;
        censoSwitches = infoServicio.datosServicio.censoSwitch;
        areasSucursales = infoServicio.datosServicio.areasSucursal;
        materialTecnico = infoServicio.datosServicio.materialUsuario;
        listaTotalMaterialUsado = infoServicio.solucion.totalMaterial;
        iniciarObjetos();
        if (infoServicio.servicio.Folio != 0 && infoServicio.servicio.Folio != null) {
            mostrarElementosAgregarFolio();
            mostrarInformacionFolio(infoServicio.folio);
            arreglarNotas(infoServicio.notasFolio);
        }
        if (infoServicio.sucursales.length > 0) {
            selectSucursal.cargaDatosEnSelect(infoServicio.sucursales);
            selectSucursal.definirValor(infoServicio.solucion.IdSucursal);
        }
        if (infoServicio.solucion.solucion[0].Archivos !== "") {
            $('#btnConMaterial').addClass('hidden');
            $('#btnSinMaterial').removeClass('hidden');
            $('#sinMaterial').removeClass('hidden');
            $('#conMaterial').addClass('hidden');
        }
        if (infoServicio.problemas !== null) {
            cargarContenidoProblemas(infoServicio.problemas);
        }
        cargarContenidoServicio(infoServicio.servicio);
        cargarContenidoSolucion(infoServicio.solucion);
        cargarContenidoModalMaterial(infoServicio.datosServicio);
        cargarContenidoTablaNodos();
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
        selectMaterial = new SelectBasico('selectMaterial');
        evidenciaMaterial = new FileUpload_Basico('agregarEvidenciaNodo', {url: 'SeguimientoCE/SeguimientoGeneral/Accion/agregarNodo', extensiones: ['jpg', 'jpeg', 'png']});
        evidenciaMaterial.iniciarFileUpload()
        evidenciaProblema = new FileUpload_Basico('agregarEvidenciaProblema', {url: 'SeguimientoCE/SeguimientoGeneral/agregarProblema', extensiones: ['jpg', 'jpeg', 'png']});
        evidenciaProblema.iniciarFileUpload()
        evidenciaFija = new FileUpload_Basico('agregarEvidenciaFija', {url: 'SeguimientoCE/SeguimientoGeneral/', extensiones: ['jpg', 'jpeg', 'png']});
        evidenciaFija.iniciarFileUpload()
        collapseNotas = new Collapse('collapseNotas');
        selectSucursal.iniciarSelect();
        selectArea.iniciarSelect();
        selectSwitch.iniciarSelect();
        selectMaterial.iniciarSelect();
    }

    function mostrarElementosAgregarFolio() {
        $('#infoServicio').removeClass('col-md-12');
        $('#infoServicio').addClass('col-md-6');
        $('#btnAgregarFolio').addClass('hidden');
        $('#agregarFolio').removeClass('hidden');
    }

    function mostrarInformacionFolio(infoFolio) {
        $('#infoFolio').removeClass('hidden');
        $('#editarFolio').removeClass('hidden');
        $('#eliminarFolio').removeClass('hidden');
        $('#guardarFolio').addClass('hidden');
        $('#cancelarFolio').addClass('hidden');
        $('#addFolio').val(infoFolio.WORKORDERID).prop("disabled", true);
        $("#creadoPorFolio").text(infoFolio.CREATEDBY);
        $("#fechaCreacionFolio").text(fecha.formatoFecha(infoFolio.CREATEDTIME));
        $("#solicitaFolio").text(infoFolio.REQUESTER);
        $("#prioridadFolio").text(infoFolio.PRIORITY);
        $("#asignadoFolio").text(infoFolio.TECHNICIAN);
        $("#estatusFolio").text(infoFolio.STATUS);
        $("#asuntoFolio").text(infoFolio.SHORTDESCRIPTION);
    }

    function arreglarNotas(notas) {
        if (notas.length > 0) {
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
        let problema = '';
        $.each(infoProblemas, function (key, value) {
            problema += '<div class="problema col-md-12 row">\n\
                            <div class="col-md-6 col-sm-12">\n\
                                Usuario: <label class="semi-bold">' + value.usuario + '</label>\n\
                            </div>\n\
                            <div class="col-md-6 col-sm-12">\n\
                                Fecha:<label class="semi-bold">' + value.fecha + '</label>\n\
                            </div>\n\
                            <div class="col-md-11 col-sm-11 col-xs-10">\n\
                                <textarea class="form-control" rows="2" disabled>' + value.descripcion + '</textarea>\n\
                            </div>\n\
                            <div class="col-md-1 col-sm-2 col-xs-1">\n\
                                <a href="' + value.archivos[0] + '" data-lightbox="problema"><i class="fa fa-file-photo-o "></i></a>\n\
                            </div>\n\
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

        selectSucursal.evento('change', function () {
            datoServicioTabla.idSucursal = selectSucursal.obtenerValor();
            let totalNodos = listaTotalNodos.length;
            if (totalNodos > 0) {
                modal.mostrarModal('Aviso', '<h4>Si realizas el cambio de sucursal se Borrara los Nodos registrados</h4>');

                modal.btnAceptar('btnAceptar', function () {
                    peticionCambioSucursal();
                });

                $('#btnCerrar').on('click', function () {
                    selectSucursal.definirValor(solucion.IdSucursal);
                    modal.cerrarModal();
                });
            } else {
                peticionCambioSucursal()
            }
        });
    }

    function peticionCambioSucursal() {
        peticion.enviar('contentServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Accion/borrarNodos', datoServicioTabla, function (respuesta) {
            listaTotalNodos = respuesta.solucion.nodos;
            materialTecnico = respuesta.datosServicio.materialUsuario;
            listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
            cargarContenidoModalMaterial(respuesta.datosServicio);
            tablaNodos.limpiartabla();
            modal.cerrarModal();
        });
    }

    /**Empiesan eventos del modal Material**/
    function cargarContenidoModalMaterial(materialNodo) {
        selectSwitch.bloquearElemento();
        if (materialNodo.areasSucursal.length > 0) {
            selectArea.cargaDatosEnSelect(materialNodo.areasSucursal);
            selectArea.evento('change', function () {
                selectSwitch.limpiarElemento();
                selectSwitch.habilitarElemento();
                let switches = [], contador = 0, areaSeleccionada = selectArea.obtenerValor();
                $.each(materialNodo.censoSwitch, function (key, value) {
                    if (value.idArea === areaSeleccionada) {
                        switches[contador] = {id: value.modelo, text: value.text};
                        contador++;
                    }
                });
                selectSwitch.cargaDatosEnSelect(switches);
            });
        }
        if (materialNodo.censoSwitch.length > 0) {
            selectSwitch.cargaDatosEnSelect(materialNodo.censoSwitch);
        }
        if (materialNodo.materialUsuario.length > 0) {
            selectMaterial.cargaDatosEnSelect(materialNodo.materialUsuario);
            selectMaterial.evento('change', function () {
                let materialSeleccionado = selectMaterial.obtenerValor();
                $.each(materialNodo.materialUsuario, function (key, value) {
                    if (value.id == materialSeleccionado) {
                        $('#materialDisponible').val(value.cantidad);
                    }
                });
            });
        }
    }

    $('#btnAgregarMaterialATablaNodo').on('click', function () {
        if (evento.validarFormulario('#formMaterial')) {
            if (parseFloat($('#materialUtilizar').val()) <= parseFloat($('#materialDisponible').val()) && parseFloat($('#materialUtilizar').val()) > 0) {
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
                selectMaterial.cargaDatosEnSelect(materialTecnico);
                $('#materialDisponible').val('');
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
        if (evento.validarFormulario('#formDatosNodo') && evento.validarFormulario('#formEvidenciaMaterial')) {
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
            evidenciaMaterial.enviarPeticionServidor('#modalMaterialNodo', infoMaterialNodo, function (respuesta) {
                limpiarElementosModalMaterial();
                tablaNodos.limpiartabla();
                listaTotalNodos = respuesta.solucion.nodos;
                listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
                materialTecnico = respuesta.datosServicio.materialUsuario;
                cargarContenidoModalMaterial(respuesta.datosServicio);
                cargarContenidoTablaNodos();
            });
        }
    });

    $('#btnCancelarAgregarMaterial').on('click', function () {
        let suma = 0, infoTabla = tablaAgregarMateriales.datosTabla();
        if (idNodo === null) {
            if (infoTabla.length > 0) {
                $.each(materialTecnico, function (key, value) {
                    $.each(infoTabla, function (llave, valor) {
                        if (value.id == valor[0]) {
                            suma = parseFloat(value.cantidad) + parseFloat(valor[2]);
                            value.cantidad = suma;
                        }
                    });
                });
            }
        }
        limpiarElementosModalMaterial();
        restaurarElementosModal();
        idNodo = null;
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
        infoMaterialNodo.evidencias = false;

        $.each(tablaAgregarMateriales.datosTabla(), function (key, value) {
            if (infoMaterialNodo.material === null) {
                infoMaterialNodo.material = '{"idMaterial": ' + value[0] + ', "cantidad": ' + value[2] + '}';
            } else {
                infoMaterialNodo.material += '|{"idMaterial": ' + value[0] + ', "cantidad": ' + value[2] + '}';
            }
        });
//        let evidenciaOpcional = $('#agregarEvidenciaNodo').val();
//        let evidenciaEstablecida = $('#evidenciasMaterialUtilizado').html();
//        if(evidenciaOpcional == '' && evidenciaEstablecida == ''){
//            
//        }
//        peticion.enviar('contentServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Accion/actualizarNodo', infoMaterialNodo, function (respuesta) {
//            console.log(respuesta);
//        });

//        evidenciaMaterial.enviarPeticionServidor('#modalMaterialNodo', infoMaterialNodo, function (respuesta) {
//            limpiarElementosModalMaterial();
//            $('#modalMaterialNodo').modal('toggle');
//            tablaNodos.limpiartabla();
        idNodo = null;
//        console.log(evidenciaEstablecida);
    });
//    });

    $('[id*=img-] .eliminarEvidencia').on('click', function () {
        let dato = $(this);
        console.log('aqui')
        console.log(dato)
    });

    $('#btnEliminarAgregarMaterial').on('click', function () {
        let datos = {};
        datos.id = datoServicioTabla.id;
        datos.tipo = datoServicioTabla.tipo;
        datos.idNodo = idNodo;
        let suma = 0, datosMaterial = [];
        $.each(listaTotalNodos, function (key, value) {
            if (value.IdNodo === idNodo) {
                datosMaterial.push(value);
            }
        });
        $.each(materialTecnico, function (key, value) {
            $.each(datosMaterial, function (llave, valor) {
                if (value.id == valor.IdMaterialTecnico) {
                    suma = parseFloat(value.cantidad) + parseFloat(valor.Cantidad);
                    value.cantidad = suma;
                }
            });
        });

        peticion.enviar('contentServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Accion/borrarNodo', datos, function (respuesta) {
            limpiarElementosModalMaterial();
            restaurarElementosModal();
            $('#modalMaterialNodo').modal('toggle');
            tablaNodos.limpiartabla();
            listaTotalNodos = respuesta.solucion.nodos;
            listaTotalMaterialUsado = respuesta.solucion.totalMaterial;
            materialTecnico = respuesta.datosServicio.materialUsuario;
            cargarContenidoModalMaterial(respuesta.datosServicio);
            cargarContenidoTablaNodos();
            idNodo = null;
        });
    });

    function  limpiarElementosModalMaterial() {
        selectArea.limpiarElemento();
        $('#inputNodo').val('');
        selectSwitch.limpiarElemento();
        $('#inputNumSwith').val('');
        selectMaterial.limpiarElemento();
        $('#materialDisponible').val('');
        $('#materialUtilizar').val('');
        tablaAgregarMateriales.limpiartabla();
        evidenciaMaterial.limpiarElemento();
    }

    function restaurarElementosModal() {
        $('#imagenEvidencia').addClass('hidden');
        $('#btnActualizarAgregarMaterial').addClass('hidden');
        $('#btnEliminarAgregarMaterial').addClass('hidden');
        $('#btnAceptarAgregarMaterial').removeClass('hidden');
        $('#fileMostrarEvidencia').addClass('hidden');
        $('#evidenciasMaterialUtilizado').empty();
    }
    /**Finalizan eventos del modal Material**/

    function cargarContenidoTablaNodos() {
        let listaTemporalNodos = JSON.parse(JSON.stringify(listaTotalNodos));
        $.each(listaTemporalNodos, function (key, value) {
            $.each(areasSucursales, function (llave, valor) {
                if (value.IdArea === valor.id) {
                    value.IdArea = valor.text;
                }
            });
            $.each(censoSwitches, function (llave, valor) {
                if (value.IdSwitch === valor.modelo) {
                    value.IdSwitch = valor.text;
                }
            });
        });
        $.each(listaTemporalNodos, function (key, value) {
            tablaNodos.agregarDatosFila([
                value.IdNodo,
                value.IdArea,
                value.Nombre,
                value.IdSwitch,
                value.NumeroSwitch
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
                actualizarContenidoModalMaterial(datos[0]);
            }
        });

        tablaAgregarMateriales.evento(function () {
            let suma = 0, datosFila = tablaAgregarMateriales.datosFila(this);
            $.each(materialTecnico, function (key, value) {
                if (value.id === datosFila[0]) {
                    suma = parseFloat(value.cantidad) + parseFloat(datosFila[2]);
                    value.cantidad = suma;
                }
            });
            tablaAgregarMateriales.eliminarFila(this);
            selectMaterial.cargaDatosEnSelect(materialTecnico);
            $('#materialDisponible').val('');
            $('#materialUtilizar').val('');
        });
    }

    function actualizarContenidoModalMaterial(id) {
        idNodo = null;
        let listaTemporalNodos = [], evidencias = '', archivos;
        $.each(listaTotalNodos, function (key, value) {
            if (value.IdNodo === id) {
                listaTemporalNodos.push(value);
            }
        });
        selectArea.definirValor(listaTemporalNodos[0].IdArea);
        $('#inputNodo').val(listaTemporalNodos[0].Nombre);
        selectSwitch.definirValor(listaTemporalNodos[0].IdSwitch);
        $('#inputNumSwith').val(listaTemporalNodos[0].NumeroSwitch);
        archivos = listaTemporalNodos[0].Archivos.split(',');
        $.each(archivos, function (key, value) {
            evidencias += '<div class="evidencia"><a href="' + value + '" data-lightbox="evidencias"><img src ="' + value + '" /></a><div><a id="img-' + key + '" class="eliminarEvidencia" href="#"><i class="fa fa-trash text-danger"></i></a></div></div>';
        });
        $('#evidenciasMaterialUtilizado').append(evidencias);
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
        idNodo = id;
    }
    /*********************************************************************************************************************************************/
    function ocultarElementosDefault(solucion, firmas) {
        let datosNodo = tablaNodos.datosTabla();
        if (datosNodo.length == 0 || solucion.IdSucursal == null) {
            $('#btnConcluir').attr("disabled", true);
            $('#btnConcluir').off("click");
        }
        if (firmas !== null) {
            $('#firmasExistentes').removeClass('hidden');
        } else {
            $('#firmasExistentes').addClass('hidden');
        }
    }

    /**Empiezan eventos de botones del encabezado**/
    $('#btnRegresar').on('click', function () {
        location.reload();
    });

    $('#btnEditarServicio').on('click', function () {
        console.log('btnEditarServicio')
    });

    $('#btnAgregarFolio').on('click', function () {
        mostrarElementosAgregarFolio();
    });
    /**Finalizan eventos de botones del encabezado**/

    /**Empiezan eventos de botones para folio**/
    $('#guardarFolio').on('click', function () {
        if (evento.validarFormulario('#folio')) {
            datoServicioTabla.folio = $('#addFolio').val();
            peticion.enviar('contentServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Folio/guardar', datoServicioTabla, function (respuesta) {
                if (!respuesta.operacion) {
                    datoServicioTabla.folio = null;
                }
                mostrarElementosAgregarFolio();
                mostrarInformacionFolio(respuesta.folio);
                arreglarNotas(respuesta.notasFolio);
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

    $('#cancelarFolio').on('click', function () {
        if (datoServicioTabla.folio !== null && datoServicioTabla.folio !== '0') {
            $('#addFolio').prop('disabled', true);
            $('#infoFolio').removeClass('hidden');
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
            peticion.enviar('contentServiciosGeneralesRedes', 'SeguimientoCE/SeguimientoGeneral/Folio/eliminar', datoServicioTabla, function (respuesta) {
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
        $('#btnConMaterial').removeClass('hidden');
        $('#btnSinMaterial').addClass('hidden');
        $('#sinMaterial').addClass('hidden');
        $('#conMaterial').removeClass('hidden');
    });

    $('#btnConMaterial').on('click', function () {
        $('#btnConMaterial').addClass('hidden');
        $('#btnSinMaterial').removeClass('hidden');
        $('#sinMaterial').removeClass('hidden');
        $('#conMaterial').addClass('hidden');
    });

    $('#btnAceptarProblema').on('click', function () {
        if (evento.validarFormulario('#formEvidenciaProblema')) {
            datoServicioTabla.descripcion = $('#textareaDescProblema').val();
            evidenciaProblema.enviarPeticionServidor('#modalDefinirProblema', datoServicioTabla, function (respuesta) {
                console.log(respuesta);
                mostrarInformacionFolio(respuesta.folio);
                arreglarNotas(respuesta.notasFolio);
                cargarContenidoProblemas(respuesta.problemas);
                $('#textareaDescProblema').val('');
                evidenciaProblema.limpiarElemento();
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
            datoServicioTabla.observaciones = $('#textareaObservaciones').val();
            datoServicioTabla.idSucursal = selectSucursal.obtenerValor();
            datoServicioTabla.material = false;
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/guardarSolucion', datoServicioTabla, function (respuesta) {
                console.log(respuesta);
            });
        }
    });

    $('#btnConcluir').on('click', function () {
        $('#contentFirmasConclucion').removeClass('hidden');
        $('#contentServiciosRedes').addClass('hidden');
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

    $('#btnContinuar').on('click', function () {
        let imgFirmaCliente = firmaClienet.getImg();
        let inputFirmaCliente = (firmaClienet.blankCanvas == imgFirmaCliente) ? '' : imgFirmaCliente;

        if (evento.validarFormulario('#formAgregarCliente')) {
            if (inputFirmaCliente == '') {
                evento.mostrarMensaje("#errorMessageFirmaCliente", false, 'Falta firma del Cliente', 2000);
            } else {
                datoServicioTabla.nombreCliente = $('#inputCliente').val()
                $('#contentfirmaTecnico').removeClass('hidden');
                $('#btnTerminar').removeClass('hidden');
                $('#btnRegresarServicio2').removeClass('hidden');
                $('#contentfirmaCliente').addClass('hidden');
                $('#btnContinuar').addClass('hidden');
                $('#btnRegresarServicio').addClass('hidden');
            }
        }
    });

    $('#btnTerminar').on('click', function () {
        let imgFirmaTecnico = firmaTecnico.getImg();
        let inputFirmaTecnico = (firmaTecnico.blankCanvas == imgFirmaTecnico) ? '' : imgFirmaTecnico;
        if (inputFirmaTecnico == '') {
            evento.mostrarMensaje("#errorMessageFirmaTecnico", false, 'Falta firma del Tecnico', 2000);
        } else {
            datoServicioTabla.firmaCliente = firmaClienet.getImg();
            datoServicioTabla.firmaTecnico = firmaTecnico.getImg();
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/concluir', datoServicioTabla, function (respuesta) {
                console.log(respuesta);
            });
        }
    });

});
