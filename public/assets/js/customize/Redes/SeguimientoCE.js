$(function () {

    let peticion = new Utileria();
    let modal = new Modal();

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

    let datoServicioTabla = {
        id: null,
        tipo: null,
        folio: null
    }
    let datoServicioGral = {
        sucursal: null,
        observaciones: null
    }
    let  nodo = {
        area: null,
        nodo: null,
        switch : null,
        numSwitch: null
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
        cargarElementosServicio(infoServicio);
        eventosTablas();
        verBotonConcluir();
        $('html, body').animate({
            scrollTop: $("#contentServiciosRedes").offset().top - 50
        }, 600);
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
        //$("#fechaCreacionFolio").text(infoFolio);
        $("#solicitaFolio").text(infoFolio.REQUESTER);
        $("#prioridadFolio").text(infoFolio.PRIORITY);
        $("#asignadoFolio").text(infoFolio.TECHNICIAN);
        $("#estatusFolio").text(infoFolio.STATUS);
        $("#asuntoFolio").text(infoFolio.SHORTDESCRIPTION);
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
        selectMaterial.iniciarSelect();
    }

    function cargarElementosServicio(datos) {
        let servicio = datos.servicio;
        selectSucursal.cargaDatosEnSelect(datos.sucursales);
        $("#fechaServicio").text(servicio.FechaCreacion);
        $("#ticketServicio").text(servicio.Ticket);
        $("#atendidoServicio").text(servicio.Atiende);
        $("#solicitudServicio").text(servicio.idSolicitud);
        $("#textareaDescripcion").text(servicio.Descripcion);
        $("#solicitaSolicitud").text(servicio.Solicita);
        $("#fechaSolicitud").text(servicio.FechaSolicitud);
        $("#textareaDescripcionSolicitud").text(servicio.descripcionSolicitud);
    }

    function arreglarNotas(notas) {
        let datos = [];
        let contador = 0;
        $.each(notas, function (key, value) {
            datos[contador] = {titulo: value.USERNAME, contenido: value.NOTESTEXT};
            contador++;
        });
        collapseNotas.multipleCollapse(datos);
    }

    function eventosTablas() {
        tablaNodos.evento(function () {
            let datos = tablaNodos.datosFila(this);
            let contentHtml = $('#modalMaterialNodo').html();
            modal.mostrarModal('Editar', contentHtml);
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

    $('#btnAgregarMaterialNodo').on('click', function () {
        if (evento.validarFormulario('#formMaterial')) {
            tablaAgregarMateriales.agregarDatosFila([
                selectMaterial.obtenerValor(),
                $('#materialUtilizar').val()
            ]);
        }
    });
    $('#btnAceptarM').on('click', function () {
        if (evento.validarFormulario('#formDatosNodo') && evento.validarFormulario('#formEvidenciaMaterial')) {
//            nodo.area = selectArea.obtenerValor();
//            nodo.nodo = $('#inputNodo').val();
//            nodo.switch = selectSwitch.obtenerValor();
//            nodo.numSwitch = $('#inputNumSwith').val();
            console.log('agregar tabla de nodos');
        }
    });

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
            datoServicioGral.sucursal = selectSucursal.obtenerValor();
            datoServicioGral.observaciones = $('#textareaObservaciones').val();
            console.log(datoServicioGral)
        }
    });
    $('#btnConcluir').on('click', function () {
        $('#contentFirmasConclucion').removeClass('hidden');
        $('#contentServiciosRedes').addClass('hidden');
    });
    /**Finalizan seccion de botonos generales**/

    function verBotonConcluir() {
        let datosNodo = tablaNodos.datosTabla();
        if (datosNodo.length == 0) {
            $('#btnConcluir').attr("disabled", true);
            $('#btnConcluir').off("click");
        }
    }

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
