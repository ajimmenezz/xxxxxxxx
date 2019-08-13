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
        folio: null
    }
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
        evidencia: [],
        material: []
    }

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
                peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Atender/' + datosFila[4], datoServicioTabla, function (respuesta) {
                    modal.cerrarModal();
                    cambioVista(respuesta);
                });
            });
        } else {
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Seguimiento/' + datosFila[4], datoServicioTabla, function (respuesta) {
                cambioVista(respuesta);
            });
        }
    });

    function cambioVista(infoServicio) {
        $('#contentServiciosGeneralesRedes').addClass('hidden');
        $('#contentServiciosRedes').removeClass('hidden');
        iniciarObjetos();
        if (infoServicio.servicio.Folio != 0 && infoServicio.servicio.Folio != null) {
            mostrarElementosAgregarFolio();
            mostrarInformacionFolio(infoServicio.folio);
            arreglarNotas(infoServicio.notasFolio);
        }
        cargarContenidoServicio(infoServicio);
        cargarContenidoSolucion(infoServicio.solucion);
        cargarContenidoModalMaterial(infoServicio.datosServicio);
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
        evidenciaMaterial = new FileUpload_Basico('agregarEvidenciaNodo');
        collapseNotas = new Collapse('collapseNotas');
        selectSucursal.iniciarSelect();
        selectArea.iniciarSelect();
        selectSwitch.iniciarSelect();
        selectSwitch.bloquearElemento();
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

    function cargarContenidoServicio(datos) {
        let servicio = datos.servicio;
        if (datos.sucursales.length > 0) {
            selectSucursal.cargaDatosEnSelect(datos.sucursales);
        }
        $("#fechaServicio").text(servicio.FechaCreacion);
        $("#ticketServicio").text(servicio.Ticket);
        $("#atendidoServicio").text(servicio.Atiende);
        $("#solicitudServicio").text(servicio.idSolicitud);
        $("#textareaDescripcion").text(servicio.Descripcion);
        $("#solicitaSolicitud").text(servicio.Solicita);
        $("#fechaSolicitud").text(servicio.FechaSolicitud);
        $("#textareaDescripcionSolicitud").text(servicio.descripcionSolicitud);
    }

    function cargarContenidoSolucion(solucion) {
        selectSucursal.definirValor(solucion.IdSucursal);
        if (solucion.solucion.length > 0) {
            $('#textareaObservaciones').text(solucion.solucion[0].Observaciones);
        }
        selectSucursal.evento('change', function () {
            let totalNodos = tablaNodos.datosTabla();
            if (totalNodos.length > 0) {
                modal.mostrarModal('Aviso', '<h4>Si realizas el cambio de sucursal se Borrara los Nodos registrados</h4>');
                $('#btnAceptar').on('click', function () {
                    modal.cerrarModal();
                    console.log(totalNodos)
                });
                $('#btnCerrar').on('click', function () {
                    selectSucursal.definirValor(solucion.IdSucursal);
                    modal.cerrarModal();
                });
            }
        });
    }

    function cargarContenidoModalMaterial(materialNodo) {
        if (materialNodo.areasSucursal.length > 0) {
            selectArea.cargaDatosEnSelect(materialNodo.areasSucursal);
            selectArea.evento('change', function () {
                selectSwitch.limpiarElemento();
                selectSwitch.habilitarElemento();
                let switches = [], contador = 0, areaSeleccionada = selectArea.obtenerValor();
                $.each(materialNodo.censoSwitch, function (key, value) {
                    if (value.idArea === areaSeleccionada) {
                        switches[contador] = {id: value.id, text: value.text};
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
            tablaAgregarMateriales.agregarDatosFila([
                selectMaterial.obtenerValor(),
                selectMaterial.obtenerTexto(),
                $('#materialUtilizar').val()
            ]);
        }
    });
    $('#btnAceptarAgregarMaterial').on('click', function () {
//        if (evento.validarFormulario('#formDatosNodo') && evento.validarFormulario('#formEvidenciaMaterial')) {
            infoMaterialNodo.id = datoServicioTabla.id,
            infoMaterialNodo.tipo =  datoServicioTabla.tipo,
            infoMaterialNodo.area = selectArea.obtenerValor();
            infoMaterialNodo.nodo = $('#inputNodo').val();
            infoMaterialNodo.switch = selectSwitch.obtenerValor();
            infoMaterialNodo.numSwitch = $('#inputNumSwith').val();
            infoMaterialNodo.evidencia = $('#agregarEvidenciaNodo').val();
            let contador = 0;
            $.each(tablaAgregarMateriales.datosTabla(), function (key, value) {
                infoMaterialNodo.material[contador] = {idMaterial: value[0], cantidad: value[2]};
                contador++;
            });
            console.log(infoMaterialNodo);
//        }
    });

    function eventosTablas() {
        tablaNodos.evento(function () {
            let datos = tablaNodos.datosFila(this);
            $('#inputNodo').val(datos[2]);
            $('#imagenEvidencia').removeClass('hidden');
        });

        tablaAgregarMateriales.evento(function () {
            let _this = this;
            modal.mostrarModal('Eliminar Material', '<h4>Se Eliminará este material de la lista<br>\n\
                                            ¿Estas seguro de esto?</h4>');
            $('#btnAceptar').on('click', function () {
                modal.cerrarModal();
                tablaAgregarMateriales.eliminarFila(_this);
            });
        });
    }

    function ocultarElementosDefault(solucion, firmas) {
        let datosNodo = tablaNodos.datosTabla();
        if (datosNodo.length == 0 && solucion == null) {
            $('#btnConcluir').attr("disabled", true);
            $('#btnConcluir').off("click");
        }
        if (firmas !== null) {
            $('#firmasExistentes').removeClass('hidden');
        } else {
            $('#firmasExistentes').addClass('hidden');
        }
    }

    /*********************************************************************************************************************************************/

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
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Folio/guardar', datoServicioTabla, function (respuesta) {
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
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Folio/eliminar', datoServicioTabla, function (respuesta) {
                $('#addFolio').prop('disabled', false);
                $('#addFolio').val('');

                ocultarElementosAgregarFolio();
                $("#creadoPorFolio").empty()
                //$("#fechaCreacionFolio").text(infoFolio);
                $("#solicitaFolio").empty()
                $("#prioridadFolio").empty()
                $("#asignadoFolio").empty()
                $("#estatusFolio").empty()
                $("#asuntoFolio").empty()
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

    $('#btnReportar').on('click', function () {
        let contentReportar = $('#segReportar').html();
        let contentEvidencia = $('#vistaEvidencias').html();
        modal.mostrarModal('Definir Problema', contentReportar + contentEvidencia, 'text-left');
        console.log('btnReportar')
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


    /**Empiezan eventos de botones para la tabla de nodos**/
//    $('.evidenciaNodo').on('click', function () {
//        modal.mostrarModalBotonTabla("evidenciaNodo", '#modalEvidencia');
//        let row = $(this).closest("tr");
//    });
//    $('.editarNodo').on('click', function () {
//        let row, sucursal, nodo, switk, numSwitk;
//        modal.mostrarModalBotonTabla("editarNodo", '#modalEditarNodo');
//        row = $(this).closest("tr");
//
//        sucursal = row.find(".sucursal").text();
//        $('#inputEdicionNodo').val(row.find(".nodo").text());
//        switk = row.find(".switch").text();
//        $('#inputEdicionNumSwith').val(row.find(".numSwitch").text());
//    });
//    $('.editarMaterial').on('click', function () {
//        modal.mostrarModalBotonTabla("editarMaterial", '#modalMaterialNodo');
//        let row = $(this).closest("tr");
//    });
//    $('.eliminarNodo').on('click', function () {
//        let row = $(this).closest("tr");
//        modal.mostrarModal('Eliminar Nodo', '<h4>Al eliminar el nodo se borrara toda la información del material y de las evidencias<br>\n\
//                                            ¿Estas seguro de querer eliminar el nodo?</h4>');
//        $('#btnAceptar').on('click', function () {
//            modal.cerrarModal();
//            tablaNodos.eliminarFila(row);
//        });
//    });
    /**Finalizan eventos de botones para la tabla de nodos**/


    /**Empiezan seccion de botonos generales**/
    $('#btnGuardar').on('click', function () {
        if (evento.validarFormulario('#formDatosSolucion')) {
//            datoServicioGral.sucursal = selectSucursal.obtenerValor();
//            datoServicioGral.observaciones = $('#textareaObservaciones').val();
//            console.log(datoServicioGral)
        }
    });
    $('#btnConcluir').on('click', function () {
        $('#contentFirmasConclucion').removeClass('hidden');
        $('#contentServiciosRedes').addClass('hidden');
    });
    /**Finalizan seccion de botonos generales**/

    $('#btnContinuar').on('click', function () {
        let imgFirmaCliente = firmaClienet.getImg();
        let inputFirmaCliente = (firmaClienet.blankCanvas == imgFirmaCliente) ? '' : imgFirmaCliente;

        if (evento.validarFormulario('#formAgregarCliente')) {
            if (inputFirmaCliente == '') {
                evento.mostrarMensaje("#errorMessageFirmaCliente", false, 'Falta firma del Cliente', 2000);
            } else {
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
            console.log("concluye servicio")
        }
    });

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
});
