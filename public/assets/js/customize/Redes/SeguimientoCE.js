$(function () {

    peticion = new Utileria();
    modal = new Modal();

    evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();

    let tablaPrincipal = new TablaBasica('table-ServiciosGeneralesRedes');
    let tablaNodos = new TablaBasica('table-nodo');
    let tablaMateriales = new TablaBasica('table-material');
    let tablaAgregarMateriales = new TablaBasica('table-materalNodo');
    let datoServicio = {
        id: null,
        folio: null,
        ticket: null,
        servicio: null
    }

    tablaPrincipal.evento(function () {
        let datosFila = tablaPrincipal.datosFila(this);
        let tamañoDatosFila = 0;
        $.each(datosFila, function () {
            tamañoDatosFila += 1;
        });
        datoServicio.id = datosFila[0];
        datoServicio.folio = datosFila[1];
        datoServicio.ticket = datosFila[2];
        datoServicio.servicio = datosFila[3];
        if (datosFila[tamañoDatosFila - 1] === "ABIERTO") {
            modal.mostrarModal('Iniciar Servicio', '<h3>¿Quieres atender el servicio?</h3>');
            $('#btnAceptar').on('click', function () {
                atenderServicio(datoServicio);
                
            });
        } else {
            $('#contentServiciosGeneralesRedes').addClass('hidden');
            $('#contentServiciosRedes').removeClass('hidden');
            if (datosFila[1] != 0 && datosFila[1] != null) {
                $('#addFolio').val(datosFila[1]);
                elementosAgregarFolio();
                elementosGuerdarFolio();
            }
            $('html, body').animate({
                scrollTop: $("#contentServiciosRedes").offset().top - 50
            }, 600);
        }
    });
    
    function atenderServicio(datoServicio){
        peticion.enviar('contentServiciosGeneralesRedes0', 'CONTROLLER', datoServicio, function (respuesta) {
                    modal.cerrarModal();
                    console.log(respuesta)
                });
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
            let folio = $('#addFolio').val();
            elementosGuerdarFolio();
        }
    });
    function elementosGuerdarFolio() {
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
    });
    $('#btnConMaterial').on('click', function () {
        $('#btnConMaterial').addClass('hidden');
        $('#btnSinMaterial').removeClass('hidden');
    });

    $('#btnReportar').on('click', function () {
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
        let contenthtml = $('#materialNodo').html();
        modal.mostrarModal('Material', contenthtml);
        $('#btnAceptar').on('click', function () {
            modal.cerrarModal();
            console.log('btnAgregarNodo')
        });
    });

    /**Empiezan eventos de botones para la tabla de nodos**/
    $('#evidenciaNodo').on('click', function () {
        console.log('evidenciaNodo')
    });
    $('#editarNodo').on('click', function () {
        let contenthtml = $('#formAgregarNodo').html();
        modal.mostrarModal('Actualizar Nodo', contenthtml);
        $('#btnAceptar').on('click', function () {
            modal.cerrarModal();
            console.log('editarNodo')
        });
    });
    $('#editarMaterial').on('click', function () {
        let contenthtml = $('#materialNodo').html();
        modal.mostrarModal('Material', contenthtml);
        $('#btnAceptar').on('click', function () {
            modal.cerrarModal();
            console.log('editarMaterial')
        });
    });
    $('#eliminarNodo').on('click', function () {
        modal.mostrarModal('Eliminar Nodo', '<h4>Al eliminar el nodo se borrara toda la información del material y de las evidencias<br>\n\
                                            ¿Estas seguro de querer eliminar el nodo?</h4>');
        $('#btnAceptar').on('click', function () {
            modal.cerrarModal();
            console.log('eliminarNodo')
        });
    });
    /**Finalizan eventos de botones para la tabla de nodos**/
});