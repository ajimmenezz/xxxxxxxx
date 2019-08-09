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

    let datoServicioTabla = {
        id: null
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
        datoServicioTabla.id = datosFila[0];
        if (datosFila[tamañoDatosFila - 1] === "ABIERTO") {
            modal.mostrarModalBasico('Iniciar Servicio', '<h3>¿Quieres atender el servicio?</h3>');
            $('#btnAceptar').on('click', function () {
                peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Atender/'+datosFila[4], datoServicioTabla, function (respuesta) {
                    modal.cerrarModal();
                    cambioVista(datosFila[1]);
                    cargarElementosServicio(respuesta);
                });
            });
        } else {
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Atender', datoServicioTabla, function (respuesta) {
                cambioVista(datosFila[1]);
                cargarElementosServicio(respuesta);
            });
        }
    });

    function cambioVista(folio) {
        $('#contentServiciosGeneralesRedes').addClass('hidden');
        $('#contentServiciosRedes').removeClass('hidden');
        if (folio != 0 && folio != null) {
            $('#addFolio').val(folio);
            elementosAgregarFolio();
            elementosInfoFolio();
        }
        iniciarObjetos();
        verBotonConcluir();
        $('html, body').animate({
            scrollTop: $("#contentServiciosRedes").offset().top - 50
        }, 600);
    }

    function cargarElementosServicio(datosServicio) {
        selectSucursal.cargaDatosEnSelect(datosServicio.sucursales);
        $("#fechaServicio").text(datosServicio.FechaCreacion);
        $("#ticket").text(datosServicio.Ticket);
        $("#atendido").text(datosServicio.Atiende);
        $("#solicitud").text(datosServicio.idSolicitud);
        $("#textareaDescripcion").text(datosServicio.Descripcion);
        $("#solicitaS").text(datosServicio.Solicita);
        $("#fechaSolicitud").text(datosServicio.FechaSolicitud);
        $("#textareaDescripcionS").text(datosServicio.descripcionSolicitud);
    }
/*********************************************************************************************************************************************/
    function atenderServicio(datoServicioTabla) {
        peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/Atender', datoServicioTabla, function (respuesta) {
            modal.cerrarModal();
            console.log(respuesta)
        });
//        console.log(datoServicioTabla);
//        peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/ActualizarFolio', {id: datoServicioTabla.id, folio: '12'}, function (respuesta) {
//            modal.cerrarModal();
//            console.log(respuesta)
//        });
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
        selectSucursal.iniciarSelect();
        selectArea.iniciarSelect();
        selectSwitch.iniciarSelect();
        selectMaterial.iniciarSelect();
    }

    /**Empiezan eventos de botones del encabezado**/
    $('#btnRegresar').on('click', function () {
        location.reload();
    });

    $('#btnEditarServicio').on('click', function () {
        console.log('btnEditarServicio')
    });
    $('#btnAgregarFolio').on('click', function () {
        elementosAgregarFolio();
    });

    function elementosAgregarFolio() {
        $('#infoServicio').removeClass('col-md-12');
        $('#infoServicio').addClass('col-md-6');
        $('#btnAgregarFolio').addClass('hidden');
        $('#agregarFolio').removeClass('hidden');
    }
    /**Finalizan eventos de botones del encabezado**/

    /**Empiezan eventos de botones para folio**/
    $('#guardarFolio').on('click', function () {
        if (evento.validarFormulario('#folio')) {
            let dato = {folio: $('#addFolio').val(), idServicio: datoServicioTabla};
            peticion.enviar('contentServiciosGeneralesRedes0', 'SeguimientoCE/SeguimientoGeneral/GuardarFolio', dato, function (respuesta) {
                //modal.cerrarModal();
                //elementosInfoFolio();
                console.log(respuesta);
            });
        }
    });
    function elementosInfoFolio() {
        $('#infoFolio').removeClass('hidden');
        $('#editarFolio').removeClass('hidden');
        $('#eliminarFolio').removeClass('hidden');
        $('#guardarFolio').addClass('hidden');
        $('#cancelarFolio').addClass('hidden');
    }
    $('#editarFolio').on('click', function () {
        console.log('editarFolio')
    });
    $('#cancelarFolio').on('click', function () {
        $('#infoServicio').removeClass('col-md-6');
        $('#infoServicio').addClass('col-md-12');
        $('#btnAgregarFolio').removeClass('hidden');
        $('#agregarFolio').addClass('hidden');
    });
    $('#eliminarFolio').on('click', function () {
        $('#infoFolio').addClass('hidden');
        $('#editarFolio').addClass('hidden');
        $('#eliminarFolio').addClass('hidden');
        $('#guardarFolio').removeClass('hidden');
        $('#cancelarFolio').removeClass('hidden');
        $('#addFolio').val('');
    });
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
        modal.mostrarModalBasico('Definir Problema', contentReportar + contentEvidencia, 'text-left');
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

    $('#btnAgregarNodo').on('click', function () {
        if (evento.validarFormulario('#formDatosNodo')) {
            nodo.area = selectArea.obtenerValor();
            nodo.nodo = $('#inputNodo').val();
            nodo.switch = selectSwitch.obtenerValor();
            nodo.numSwitch = $('#inputNumSwith').val();
            document.getElementById('btnAgregarNodo').setAttribute('href', '#modalMaterialNodo');
        }
    });
    $('#btnAgregarMaterialNodo').on('click', function () {
        if (evento.validarFormulario('#formMaterial')) {
            tablaAgregarMateriales.agregarDatosFila([
                selectMaterial.obtenerValor(),
                $('#materialUtilizar').val(),
                '<th>\n\
                    <i class="fa fa-2x fa-trash-o text-danger eliminarMaterialNodo"></i>\n\
                </th>'
            ]);
        }
        $(".eliminarMaterialNodo").on('click', function () {
            let row = $(this).closest("tr");
            modal.mostrarModalBasico('Eliminar Material', '<h4>Se Eliminará este material de la lista<br>\n\
                                            ¿Estas seguro de esto?</h4>');
            $('#btnAceptar').on('click', function () {
                modal.cerrarModal();
                tablaAgregarMateriales.eliminarFila(row);
            });
        });
    });
    $('#btnAceptarM').on('click', function () {
        if (evento.validarFormulario('#formEvidenciaMaterial')) {
            console.log('agregar tabla de nodos');
        }
    });

    /**Empiezan eventos de botones para la tabla de nodos**/
    $('.evidenciaNodo').on('click', function () {
        modal.mostrarModalBotonTabla("evidenciaNodo", '#modalEvidencia');
        let row = $(this).closest("tr");
    });
    $('.editarNodo').on('click', function () {
        let row, sucursal, nodo, switk, numSwitk;
        modal.mostrarModalBotonTabla("editarNodo", '#modalEditarNodo');
        row = $(this).closest("tr");

        sucursal = row.find(".sucursal").text();
        $('#inputEdicionNodo').val(row.find(".nodo").text());
        switk = row.find(".switch").text();
        $('#inputEdicionNumSwith').val(row.find(".numSwitch").text());
    });
    $('#btnAceptarAM').on('click', function () {
        if (evento.validarFormulario('#formEdicionNodo')) {
            document.getElementById('btnAceptarAM').setAttribute('data-dismiss', 'modal');
            console.log('actualiza nodo')
        }
    });
    $('.editarMaterial').on('click', function () {
        modal.mostrarModalBotonTabla("editarMaterial", '#modalMaterialNodo');
        let row = $(this).closest("tr");
    });
    $('.eliminarNodo').on('click', function () {
        let row = $(this).closest("tr");
        modal.mostrarModalBasico('Eliminar Nodo', '<h4>Al eliminar el nodo se borrara toda la información del material y de las evidencias<br>\n\
                                            ¿Estas seguro de querer eliminar el nodo?</h4>');
        $('#btnAceptar').on('click', function () {
            modal.cerrarModal();
            tablaNodos.eliminarFila(row);
        });
    });
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
        if (evento.validarFormulario('#formAgregarCliente')) {
            console.log('btnConcluir');
        }
    });
    /**Finalizan seccion de botonos generales**/

    function verBotonConcluir() {
        let datosNodo = tablaNodos.datosTabla();
        if (datosNodo.length == 0) {
            $('#btnConcluir').attr("disabled", true);
        }
    }
    
    var firmaGerente = new DrawingBoard.Board("firmaGerente", {
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
                
    $('#modalConcluir').on('click', function () {
        $('#contentFirmasConclucion').removeClass('hidden');
        $('#contentServiciosRedes').addClass('hidden');
    });
});
