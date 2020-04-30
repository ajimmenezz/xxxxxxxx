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
    let evidenciasComentarios = new FileUpload_Basico('agregarEvidencia', {url: 'SeguimientoRehabilitacion/SetComentario', extensiones: []});
    evidenciasComentarios.iniciarFileUpload();
    let collapseComentarios = new Collapse('collapseComentarios');
    let idInventario = null;
    let infoEquipo = null;
    let esActualizarComentario = false;
    let idComentario = null;

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
                    idInventario = respuesta.datos.infoBitacora.id;
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
                    agregarContenidoDeshuesar(respuesta.datos.infoBitacora.deshuesar, respuesta.datos.infoBitacora.estatusDeshuesar);
                    initCambiarEstatusProductos();
                    $('.cambioVistas').removeClass('hidden');
                    $('#panelRehabilitacionEquiposTabla').addClass('hidden');
                } else {
                    modal.mostrarModal('<h3>Error</h3>', '<h4>No se encontraron datos del modelo</h4>');
                    $('#btnAceptar').addClass('hidden');
                }
            });
        }
    });

    function initCambiarEstatusProductos() {
        $(".btnMarcarEstatusAll").off("click");
        $(".btnMarcarEstatusAll").on("click", function () {
            var estatus = $(this).attr("data-id");
            $(".listEstatus").val(estatus);
        });
    }

    function cargaInformacionEquipo(infoEquipo) {
        $('#cargaModelo').val(infoEquipo.modelo);
        $('#cargaSerie').val(infoEquipo.serie);
        $('#cargaEstatus').val(infoEquipo.estatus);
        $('#cargaTicket').val(infoEquipo.ticket);
    }

    function agregarContenidoComentarios(comentarios) {
        collapseComentarios.limpiarCollapse();
        let evidencias = null
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

        $(".cardUtileria").on('click', function () {
            let indice = $(this).attr('data-key');
            let posicion = indice.split('-')[1];
            let htmlEvidencias = '';
            esActualizarComentario = true;
            idComentario = comentarios[posicion].id;
            $('#modalAgregarComentario').modal('show');
            evidencias = comentarios[posicion].evidencias.split(',');

            $('#textareaComentario').val(comentarios[posicion].comentario);
            if (comentarios[posicion].evidencias !== null) {
                $.each(evidencias, function (key, value) {
                    if (value !== '') {
                        htmlEvidencias += '<div class="col-md-3 col-sm-3 col-xs-3">\n\
                                                <div id="img-' + key + '" class="evidencia">\n\
                                                    <img src ="..' + value + '" />\n\
                                                    <div class="eliminarEvidencia" data-value="' + value + '" data-key="' + key + '">\n\
                                                        <a href="#">\n\
                                                            <i class="fa fa-trash text-danger"></i>\n\
                                                        </a>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>';
                    }
                });
                $('#existenEvidencias').append(htmlEvidencias);
            }

            $('.eliminarEvidencia').on('click', function () {
                let archivo = $(this).attr('data-value');
                let indice = $(this).attr('data-key');
                $.each(evidencias, function (key, value) {
                    if (key == indice) {
                        delete evidencias[key];
                    }
                });
                let deleteEvidencia = {
                    archivo: archivo,
                    id: idComentario,
                    idInventario: idInventario
                }
                peticion.enviar('modalAgregarComentario', 'SeguimientoRehabilitacion/EliminiarEvidencia', deleteEvidencia, function (respuesta) {
                    if (respuesta.response === 200) {
                        agregarContenidoComentarios(respuesta.datos);
                        $(`#img-${indice}`).addClass('hidden');
                    }
                });
            });
        });
    }

    function agregarContenidoRefacciones(refacciones) {
        let checkRef = '';
        if (refacciones.length > 0) {
            tablaRefaccion.limpiartabla();
            $.each(refacciones, function (key, value) {
                if (value.Bloqueado == 1) {
                    checkRef = '<div class="checkbox">\n\
                                    <label>\n\
                                        <input class="checkRefacciones" type="checkbox" checked/>\n\
                                    </label>\n\
                                </div>'
                } else {
                    checkRef = '<div class="checkbox">\n\
                                    <label>\n\
                                        <input class="checkRefacciones" type="checkbox"/>\n\
                                    </label>\n\
                                </div>'
                }
                tablaRefaccion.agregarDatosFila([
                    value.IdInventario,
                    value.Bloqueado,
                    key,
                    value.Nombre,
                    value.Serie,
                    checkRef
                ]);
            });
        }
    }

    tablaRefaccion.evento(function () {
        let datosFila = tablaRefaccion.datosFila(this);
        let sendReview = null;
        sendReview = {
            id: infoEquipo.inventario,
            idRefaccion: datosFila[0]
        }

        if (datosFila[1] === "1") {
            sendReview.bloqueado = 0;
        } else {
            sendReview.bloqueado = 1;
        }

        if ($(`#addRefaccion-${datosFila[2]}`).is(':checked')) {
            $(`#addRefaccion-${datosFila[2]}`).prop("checked", false).change();
        } else {
            $(`#addRefaccion-${datosFila[2]}`).prop("checked", true).change();
        }

        peticion.enviar('panelRehabilitacionEquiposInfoModelo', 'SeguimientoRehabilitacion/RefaccionRehabilitacion', sendReview, function (respuesta) {
            if (respuesta.response === 200) {
                agregarContenidoRefacciones(respuesta.datos);
            }
        });
    });

    function agregarContenidoDeshuesar(deshuesar, estatusDeshuesar) {
        $('#nuevaTabla').empty();
        let htmDeshueso = '';
        let htmlSelect = '<option value="">Seleccionar</option>';

        agregarSeleccionTotal(estatusDeshuesar);

        if (deshuesar.length > 0) {
            tablaDeshuesar.limpiartabla();
            $.each(deshuesar, function (key, value) {
                tablaDeshuesar.agregarDatosFila([
                    value.Id,
                    value.Nombre
                ]);
            });

            $.each(estatusDeshuesar, function (k, v) {
                htmlSelect += '<option value="' + v.Id + '">' + v.Nombre + '</option>';
            });

            $.each(deshuesar, function (key, value) {
                htmDeshueso += '<div class="row">\n\
                                    <div class="col-sm-4 col-md-4 col-lg-4">\n\
                                        <div class="form-group">\n\
                                            <label>Refaccion</label>\n\
                                            <input id="cargaRefaccion' + key + '" type="text" class="form-control" value="' + value.Nombre + '" data-key="' + value.Id + '" style="width: 100%" disabled/>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-sm-4 col-md-4 col-lg-4">\n\
                                        <div class="form-group">\n\
                                            <label>Estatus</label>\n\
                                            <select id="selectDeshuesar' + key + '" class="form-control listEstatus" style="width: 100%">\n\
                                                ' + htmlSelect + '\n\
                                            </select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-sm-4 col-md-4 col-lg-4">\n\
                                        <div class="form-group">\n\
                                            <label>Serie</label>\n\
                                            <input id="inputDeshuesar' + key + '" type="text" class="form-control" style="width: 100%" placeholder="ILEGIBLE"/>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>';
            });
            $('#nuevaTabla').append(htmDeshueso);
        }
    }

    function agregarSeleccionTotal(estatusDeshuesar) {
        let seleccion = '';

        $.each(estatusDeshuesar, function (key, value) {
            seleccion += `<a role="button" data-id="${value.Id}" class="btnMarcarEstatusAll m-r-10 f-w-600">${value.Nombre}</a>`;
        });

        let html = (`<div class="col-md-12">
                        ${seleccion}
                    </div>`);

        $('#seleccionTotal').empty().append(html);
    }

    $('#btnAceptarComentario').on('click', function () {
        if (evento.validarFormulario('#formAgregarComentario')) {
            let sendComment = {
                idInventario: infoEquipo.inventario,
                comentario: $('#textareaComentario').val(),
                evidencias: false
            }
            if (esActualizarComentario) {
                sendComment.operacion = 'actualizar';
                sendComment.id = idComentario;
            } else {
                sendComment.operacion = 'agregar';
            }
            if ($('#agregarEvidencia').val() !== '') {
                evidenciasComentarios.enviarPeticionServidor('modalAgregarComentario', sendComment, function (respuesta) {
                    if (respuesta.response === 200) {
                        limpiarCamposComentarios();
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
                        limpiarCamposComentarios();
                        agregarContenidoComentarios(respuesta.datos);
                        $('#modalAgregarComentario').modal('hide');
                    } else {
                        modal.mostrarModal('<h3>Error</h3>', '<h4>Error de conexión</h4>');
                        $('#btnAceptar').addClass('hidden');
                    }
                });
            }
        }
    });

    $('#btnConcluirRevision').on('click', function () {
        let sendReview = {
            id: idInventario
        }
        peticion.enviar('panelRehabilitacionEquiposInfoModelo', 'SeguimientoRehabilitacion/ConcluirRehabilitacion', sendReview, function (respuesta) {
            if (respuesta.response === 200) {
                peticion.mostrarMensaje('#mensajeConcluir', true, 'Se ha concluido la revisión', 3000);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            } else {
                evento.mostrarMensaje('#mensajeConcluir', false, respuesta.message, 3000);
            }
        });
    });

    $('#btnConcluirDeshuesar').on('click', function () {
        let tablaDeshuesarTemp = {};
        let continuarDeshueso = true;
        let infoTablaDeshuesar = tablaDeshuesar.datosTabla();
        let sendBoning = {
            id: idInventario
        }

        $.each(infoTablaDeshuesar, function (key, value) {
            tablaDeshuesarTemp[key] = infoTablaDeshuesar[key];
            tablaDeshuesarTemp[key] = infoTablaDeshuesar[key];
        });
        $.each(infoTablaDeshuesar, function (key, value) {
            if ($(`#selectDeshuesar${key}`).val() === '') {
                continuarDeshueso = false;
                evento.mostrarMensaje('#mensajeConcluirDeshuesar', false, 'Te faltan datos en las refacciones', 3000);
            }
            tablaDeshuesarTemp[key][2] = $(`#selectDeshuesar${key}`).val();
            if ($(`#inputDeshuesar${key}`).val() === '') {
                tablaDeshuesarTemp[key][3] = "ILEGIBLE";
            } else {
                tablaDeshuesarTemp[key][3] = $(`#inputDeshuesar${key}`).val();
            }
        });

        if (continuarDeshueso) {
            sendBoning.infoDeshueso = tablaDeshuesarTemp;

            peticion.enviar('panelRehabilitacionEquiposInfoModelo', 'SeguimientoRehabilitacion/ConcluirDeshuesar', sendBoning, function (respuesta) {
                if (respuesta.response === 200) {
                    peticion.mostrarMensaje('#mensajeConcluirDeshuesar', true, 'Se ha concluido la revisión', 3000);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else {
                    evento.mostrarMensaje('#mensajeConcluirDeshuesar', false, respuesta.message, 3000);
                }
            });
        }
    });

    $('#btnRegresar').on('click', function () {
        $('.cambioVistas').addClass('hidden');
        $('#panelRehabilitacionEquiposTabla').removeClass('hidden');
        idInventario = null;
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
        esActualizarComentario = false;
        idComentario = null;
    });

    function limpiarCamposComentarios() {
        $('#textareaComentario').val('');
        evidenciasComentarios.limpiarElemento();
        $('#existenEvidencias').empty();
    }

});

