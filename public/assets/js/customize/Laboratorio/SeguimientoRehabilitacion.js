$(function () {
    let evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    App.init();

    let peticion = new Utileria();
    let modal = new Modal();

    let tablaPrincipal = new TablaBasica('data-tablaModelos');

    let infoEquipo = null;
    let inventario = null;
    let evidenciasComentarios = new FileUpload_Basico('agregarEvidencia', {url: 'SeguimientoRehabilitacion/SetComentario', extensiones: ['jpg', 'jpeg', 'png']});
    evidenciasComentarios.iniciarFileUpload();
    let tablaRefaccion = new TablaBasica('data-tablaRefaccion');
    let tablaDeshuesar = new TablaBasica('data-tablaDeshuesar');

    tablaPrincipal.evento(function () {
        let datosTabla = tablaPrincipal.datosTabla();
        let datosFila = tablaPrincipal.datosFila(this);
        if (datosTabla.length !== 0) {
            let sendModel = {
                id: datosFila[0],
                modelo: datosFila[1]
            }
            peticion.enviar('panelRehabilitacionEquiposTabla', 'SeguimientoRehabilitacion/InfoBitacora', sendModel, function (respuesta) {
                console.log(respuesta);
                infoEquipo = {
                    inventario: respuesta.datos.infoBitacora.id,
                    modelo: respuesta.datos.infoBitacora.modelo,
                    serie: respuesta.datos.infoBitacora.serie,
                    estatus: respuesta.datos.infoBitacora.estatus,
                    ticket: respuesta.datos.infoBitacora.ticketFolio
                }
                cargaInformacionEquipo(infoEquipo);
                agregarContenidoDeshuesar(respuesta.infoBitacora.refacciones);
                $('.cambioVistas').removeClass('hidden');
                $('#panelRehabilitacionEquiposTabla').addClass('hidden');
            });
        }
    });

    $('#btnRegresar').on('click', function () {
        $('.cambioVistas').addClass('hidden');
        $('#panelRehabilitacionEquiposTabla').removeClass('hidden');
        infoEquipo = {
            inventario: '',
            modelo: '',
            serie: '',
            estatus: '',
            ticket: ''
        }
        cargaInformacionEquipo(infoEquipo);
        tablaDeshuesar.limpiartabla();
    });

    $('#btnAceptarComentario').on('click', function () {
        if (evento.validarFormulario('#formAgregarComentario')) {
            let sendComment = {
                idInventario: infoEquipo.inventario,
                comentario: $('#textareaComentario').val(),
                operacion: 'agregar',
                evidencias: false
            }
            if ($('#agregarEvidencia').val() !== '') {
                evidenciasComentarios.enviarPeticionServidor('#modalAgregarComentario', sendComment, function (respuesta) {
                    console.log(respuesta);
                });
            } else {
                peticion.enviar('modalAgregarComentario', 'SeguimientoRehabilitacion/SetComentario', sendComment, function (respuesta) {
                    console.log(respuesta);
                });
            }
        }
    });

    function cargaInformacionEquipo(infoEquipo) {
        $('#cargaModelo').val(infoEquipo.modelo);
        $('#cargaSerie').val(infoEquipo.serie);
        $('#cargaEstatus').val(infoEquipo.estatus);
        $('#cargaTicket').val(infoEquipo.ticket);
    }

    function agregarContenidoDeshuesar(refacciones) {
        if (refacciones.length > 0) {
            tablaDeshuesar.limpiartabla();
            $.each(refacciones, function (key, value) {
                tablaDeshuesar.agregarDatosFila([
                    value.Id,
                    value.Nombre,
                    '<input id="cargaTicket" type="text" class="form-control" style="width: 100%"/>',
                    '<input id="cargaTicket" type="text" class="form-control" style="width: 100%"/>'
                ]);
            });
        }
    }
});

