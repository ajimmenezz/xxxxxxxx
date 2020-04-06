$(function () {
    let evento = new Base();
    evento.horaServidor($('#horaServidor').val());
    evento.cerrarSesion();
    evento.mostrarAyuda('Ayuda_Proyectos');
    let peticion = new Utileria();
    let modal = new Modal();
    App.init();

    let tablaPrincipal = new TablaBasica('data-tablaModelos');

    let tablaRefaccion = new TablaBasica('data-tablaRefaccion');
    let tablaDeshuesar = new TablaBasica('data-tablaDeshuesar');
    let evidenciasComentarios = new FileUpload_Basico('agregarEvidencia', {url: 'SeguimientoRehabilitacion/SetComentario', extensiones: ['jpg', 'jpeg', 'png']});
    evidenciasComentarios.iniciarFileUpload();
    let collapseComentarios = new Collapse('collapseComentarios');
    let infoEquipo = null;

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
                    console.log(respuesta);
                    infoEquipo = {
                        inventario: respuesta.datos.infoBitacora.id,
                        modelo: respuesta.datos.infoBitacora.modelo,
                        serie: respuesta.datos.infoBitacora.serie,
                        estatus: respuesta.datos.infoBitacora.estatus,
                        ticket: respuesta.datos.infoBitacora.ticketFolio
                    }
                    cargaInformacionEquipo(infoEquipo);
                    agregarContenidoComentarios(respuesta.datos.infoBitacora.comentarios);
                    agregarContenidoRefacciones(respuesta.datos.infoBitacora.refacciones);
                    agregarContenidoDeshuesar(respuesta.datos.infoBitacora.deshuesar);
                    $('.cambioVistas').removeClass('hidden');
                    $('#panelRehabilitacionEquiposTabla').addClass('hidden');
                } else {
                    modal.mostrarModal('<h3>Error</h3>', '<h4>No se encontraron datos del modelo</h4>');
                    $('#btnAceptar').addClass('hidden');
                }
            });
        }
    });

    function cargaInformacionEquipo(infoEquipo) {
        $('#cargaModelo').val(infoEquipo.modelo);
        $('#cargaSerie').val(infoEquipo.serie);
        $('#cargaEstatus').val(infoEquipo.estatus);
        $('#cargaTicket').val(infoEquipo.ticket);
    }

    function agregarContenidoComentarios(comentarios) {
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

    function agregarContenidoRefacciones(refacciones) {
        if (refacciones.length > 0) {
            tablaRefaccion.limpiartabla();
            $.each(refacciones, function (key, value) {
                tablaRefaccion.agregarDatosFila([
                    value.Id,
                    value.Bloqueado,
                    value.Nombre,
                    value.NoParte,
                    '<div class="checkbox">\n\
                        <label>\n\
                            <input id="addRefaccion-' + key + '" class="checkRefacciones" data-key="' + key + '" type="checkbox" />\n\
                        </label>\n\
                    </div>'
                ]);
            });
        }
    }

    tablaRefaccion.evento(function () {
        let datosFila = tablaRefaccion.datosFila(this);
        let sendReview = null;

        $(".checkRefacciones").off('click');
        $(".checkRefacciones").click(function () {
            sendReview = {
                idInventario: infoEquipo.inventario,
                idRefaccion: datosFila[0],
                bloqueado: datosFila[1]
            }
//                peticion.enviar('panelRehabilitacionEquiposInfoModelo', 'SeguimientoRehabilitacion/RefaccionRehabilitacion', sendReview, function (respuesta) {
//                    if (respuesta.response === 200) {
                        console.log("enviar: ");
                        console.log(sendReview);
//                    }
//                });
        });
    });

    function agregarContenidoDeshuesar(deshuesar) {
        if (deshuesar.length > 0) {
            tablaDeshuesar.limpiartabla();
            $.each(deshuesar, function (key, value) {
                tablaDeshuesar.agregarDatosFila([
                    value.Id,
                    value.Nombre,
                    '<select id="" class="form-control" style="width: 100%">\n\
                        <option value="">Seleccionar</option>\n\
                        <option value="Disponible">Disponible</option>\n\
                        <option value="Dañado">Dañado</option>\n\
                    </select>',
                    '<input type="text" class="form-control" style="width: 100%" placeholder="ILEGIBLE"/>'
                ]);
            });
        }
    }

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
        tablaRefaccion.limpiartabla();
        tablaDeshuesar.limpiartabla();
        collapseComentarios.limpiarCollapse();
    });

    $('#btnCancelarComentario').on('click', function () {
        limpiarCamposComentarios();
    });

    function limpiarCamposComentarios() {
        $('#textareaComentario').val('');
        evidenciasComentarios.limpiarElemento();
    }

});

