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
    let evidenciasComentarios = new FileUpload_Basico('agregarEvidencia', {url: 'SeguimientoRehabilitacion/SetComentario', extensiones: ['jpg', 'jpeg', 'png']});
    evidenciasComentarios.iniciarFileUpload();
    let tablaRefaccion = new TablaBasica('data-tablaRefaccion');
    let tablaDeshuesar = new TablaBasica('data-tablaDeshuesar');
    let collapseComentarios = new Collapse('collapseComentarios');

    tablaPrincipal.evento(function () {
        let datosTabla = tablaPrincipal.datosTabla();
        let datosFila = tablaPrincipal.datosFila(this);
        if (datosTabla.length !== 0) {
            let sendModel = {
                id: datosFila[0],
                modelo: datosFila[1]
            }
            peticion.enviar('panelRehabilitacionEquiposTabla', 'SeguimientoRehabilitacion/InfoBitacora', sendModel, function (respuesta) {
                if (respuesta.response === 200) {
                    infoEquipo = {
                        inventario: respuesta.datos.infoBitacora.id,
                        modelo: respuesta.datos.infoBitacora.modelo,
                        serie: respuesta.datos.infoBitacora.serie,
                        estatus: respuesta.datos.infoBitacora.estatus,
                        ticket: respuesta.datos.infoBitacora.ticketFolio
                    }
                    cargaInformacionEquipo(infoEquipo);
                    agregarContenidoComentarios(respuesta.datos.infoBitacora.comentarios);
                    agregarContenidoDeshuesar(respuesta.datos.infoBitacora.refacciones);
                    $('.cambioVistas').removeClass('hidden');
                    $('#panelRehabilitacionEquiposTabla').addClass('hidden');
                } else {
                    modal.mostrarModal('<h3>Error</h3>', '<h4>No se encontraron datos del modelo</h4>');
                    $('#btnAceptar').addClass('hidden');
                }
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
        collapseComentarios.limpiarCollapse();
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
                    if (respuesta.response === 200) {
                        console.log(respuesta);
                        agregarContenidoComentarios(respuesta.datos);
                        $('#modalAgregarComentario').modal('hide');
                    } else {
                        modal.mostrarModal('<h3>Error</h3>', '<h4>Error de conexión</h4>');
                        $('#btnAceptar').addClass('hidden');
                    }
                });
            } else {
                peticion.enviar('modalAgregarComentario', 'SeguimientoRehabilitacion/SetComentario', sendComment, function (respuesta) {
                    if (respuesta.response === 200) {
                        console.log(respuesta);
                        agregarContenidoComentarios(respuesta.datos);
                        $('#modalAgregarComentario').modal('hide');
                    } else {
                        modal.mostrarModal('<h3>Error</h3>', '<h4>Error de conexión</h4>');
                        $('#btnAceptar').addClass('hidden');
                    }
                });
            }
            limpiarCamposComentarios();
        }
    });
    
    $('#btnCancelarComentario').on('click', function () {
        limpiarCamposComentarios();
    });

    function limpiarCamposComentarios() {
        $('#textareaComentario').val('');
        evidenciasComentarios.limpiarElemento();
    }

    function cargaInformacionEquipo(infoEquipo) {
        $('#cargaModelo').val(infoEquipo.modelo);
        $('#cargaSerie').val(infoEquipo.serie);
        $('#cargaEstatus').val(infoEquipo.estatus);
        $('#cargaTicket').val(infoEquipo.ticket);
    }

    function agregarContenidoComentarios(comentarios) {
        console.log(comentarios);
        collapseComentarios.limpiarCollapse();
        if (comentarios.length > 0) {
            let datos = [];
            let contador = 0;
            $.each(comentarios, function (key, value) {
                datos[contador] = {
                    titulo: value.nombre,
                    fecha: value.fecha,
                    contenido: value.comentario,
                    evidencias: value.evidencias,
                    boton: 'Editar'
                };
                contador++;
            });
            collapseComentarios.multipleCardMedia(datos);
        }
    }

    function agregarContenidoDeshuesar(refacciones) {
        if (refacciones.length > 0) {
            tablaDeshuesar.limpiartabla();
            $.each(refacciones, function (key, value) {
                tablaDeshuesar.agregarDatosFila([
                    value.Id,
                    value.Nombre,
                    '',
                    ''
                ]);
            });
        }
    }
});

