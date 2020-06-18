//Super clase que define cuando se envia un evento
function Base() {

    //Metodo que regresa los datos.
    this.regresarDatos = function (callback, datos) {
        if (callback !== null) {
            callback(datos);
        }
    };

    //Se muestra el icono de carga
    this.empezarCargando = function (objeto) {
        var cuerpo;
        if ($(objeto).hasClass('panel')) {
            cuerpo = $(objeto).find('.panel-body');
            var spinnerHtml = '<div class="panel-loader"><span class="spinner-small"></span></div>';
            if (!$(objeto).hasClass('panel-loading')) {
                $(objeto).addClass('panel-loading');
                $(cuerpo).prepend(spinnerHtml);
            }
        } else if ($(objeto).hasClass('modal')) {
            var contenido = $(objeto).find('.modal-content');
            cuerpo = $(contenido).find('.modal-body');
            var spinnerHtml = '<div class="modal-loader"><span class="spinner-small"></span></div>';
            if (!$(contenido).hasClass('modal-loading')) {
                $(contenido).addClass('modal-loading');
                $(contenido).prepend(spinnerHtml);
            }
        } else {
            var recargando = '<div class="alert fade in m-b-15 text-center " id="iconCargando">\n\
                                    <i class="fa fa-2x fa-refresh fa-spin"></i></div><div class="hidden-xs">\n\
                                </div>';
            $(objeto).before(recargando);
        }
    };

    //Quita el incono de carga
    this.finalizarCargando = function (objeto) {
        if ($(objeto).hasClass('panel')) {
            $(objeto).removeClass('panel-loading');
            $(objeto).find('.panel-loader').remove();
        } else if ($(objeto).hasClass('modal')) {
            var contenido = $(objeto).find('.modal-content');
            $(contenido).removeClass('modal-loading');
            $(contenido).find('.modal-loader').remove();
        } else {
            $('#iconCargando').remove();
        }
    };
    this.plasmarInformacionSD();

    this.eventosVueltasMantenimiento();
}

//Envia peticiones al servidor
Base.prototype.enviarEvento = function () {
    var _this = this;
    var callback;
    var objeto;
    var url = null;
    var datos = null;

    if (arguments.length >= 1 && arguments.length < 5) {
        url = arguments[0] || '';
        datos = arguments[1] || {};
        objeto = arguments[2] || null;
        callback = arguments[3] || null;
        $.ajax({
            url: url,
            method: 'post',
            data: datos,
            dataType: 'json',
            beforeSend: function () {
                if (objeto !== null) {
                    _this.empezarCargando(objeto);
                }
            }
        }).done(function (data) {
            _this.finalizarCargando(objeto);
            if (callback !== null) {
                callback(data);
            }
        }).fail(function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
            console.log(textStatus);
            console.log(errorThrown);
            _this.finalizarCargando(objeto);
        });
    } else if (arguments.length === 0 || arguments.length >= 5) {
        console.log('Error: en definir los argumentos');
    }
};

//Direcciona a pagina
Base.prototype.enviarPagina = function () {

    if (arguments.length === 0) {
        window.location.href = "Logout";
    } else {
        var web = arguments[0];
        window.location.href = web;
    }
};

//Recibe la hora del servifor y actualiza la hora en la pagina
Base.prototype.horaServidor = function (horaServidor) {
    var horaActual = new Date(horaServidor);
    setInterval(function () {
        var hora = horaActual.getHours();
        var minuto = horaActual.getMinutes();
        var segundos = horaActual.getSeconds();
        var meridiano = "am";
        if (hora > 12 && hora < 24) {
            meridiano = "pm";
        }
        var horaActualizada = "Hora : " + hora + ":" + minuto + ":" + segundos + " " + meridiano;
        $('#hora').empty().append(horaActualizada);
        horaActual.setSeconds(horaActual.getSeconds() + 1);
    }, 1000);
};

//Cierra la sesion del usuario
Base.prototype.cerrarSesion = function () {
    var _this = this;
    $('#cerrar-sesion').on('click', function () {
        var data = {tipoServicio: 'salir'};
        _this.enviarEvento('/Api/reportar', data, this, function (respuesta) {
            _this.enviarPagina('/Logout');
        });
    });
};

//Valida el formulario
Base.prototype.validarFormulario = function (objeto) {
    $(objeto).parsley().validate();
    if ($(objeto).parsley().isValid() !== false) {
        return true;
    }
};

//Limpia el formulario
Base.prototype.limpiarFormulario = function (objeto) {
    $(objeto + ' select').val('').trigger('change');
    $(objeto).parsley().reset();
    $(objeto)[0].reset();
};

//Muestra el mensaje de error o exito
Base.prototype.mostrarMensaje = function (objeto, tipo, mensaje, duración) {
    switch (tipo) {
        case false:
            var error = '<div class="alert alert-danger fade in m-b-15" id="mensajeError">\n\
                                <strong>Error: </strong>\n\
                               ' + mensaje + '\n\
                            </div>';
            $(objeto).empty().append(error);
            setTimeout(function () {
                $('#mensajeError').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, duración);

            $('html,body').animate({
                scrollTop: $(objeto).offset().top - 100
            }, 'slow');
            break;
        case true:
            var exito = '<div class="alert alert-success fade in m-b-15" id="mensajeExito">\n\
                                <strong>Éxito: </strong>\n\
                               ' + mensaje + '\n\
                            </div>';
            $(objeto).empty().append(exito);
            setTimeout(function () {
                $('#mensajeExito').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, duración);
            $('html,body').animate({
                scrollTop: $("#mensajeExito").offset().top
            }, 'slow');
            break;
        default:
            break;
    }
};

//Manejador del modal
Base.prototype.mostrarModal = function () {
    var titulo = arguments[0] || 'Titulo Modal';
    var contenido = arguments[1] || 'Contenido del modal';
    var ajustarTexto = arguments[2] || 'text-center';
    //Inicia modal
    $('#modal-dialogo').modal({
        backdrop: 'static',
        keyboard: true
    });

    //Ingresa datos de modal
    if (arguments.length >= 1 && arguments.length < 4) {
        $('#modal-dialogo .modal-title').empty().append(titulo).addClass(ajustarTexto);
        $('#modal-dialogo .modal-body').empty().append(contenido);
    } else if (arguments.length === 0 || arguments.length >= 4) {
        $('#modal-dialogo .modal-title').empty().append('Titulo Modal').addClass(ajustarTexto);
        $('#modal-dialogo .modal-body').empty().append('Contenido del modal');
    }

    //Cierra modal
    $('#modal-dialogo').on('hidden.bs.modal', function () {
        $('#modal-dialogo .modal-title').empty();
        $('#modal-dialogo .modal-body').empty();
        $('#btnModalConfirmar').empty().append('Aceptar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cancelar').removeClass('hidden');
    });
};

Base.prototype.iniciarModal = function () {
    var object = arguments[0];
    var title = arguments[1];
    var content = arguments[2];

    $(object).modal();
    if (typeof title != 'undefined') {
        $(object + ' .modal-title').empty().append(title).addClass('text-center');
    } else {
        $(object + ' .modal-title').empty().append('Titulo Modal').addClass('text-center');
    }

    if (typeof content != 'undefined') {
        $(object + ' .modal-body').empty().append(content);
    } else {
        $(object + ' .modal-body').empty().append('Contenido Modal');
    }

    $(object).on('hidden.bs.modal', function () {
        $(object + ' .modal-title').empty();
        $(object + ' .modal-body').empty();
        $(object + ' #error-in-modal').empty();
        $("#btnGuardarCambiosModal").show();
    });
}

Base.prototype.terminarModal = function () {
    $(arguments[0]).modal('hide');
}

//Metodo para cerrar el modal
Base.prototype.cerrarModal = function () {
    $('#modal-dialogo').modal('hide');
};

//Metodo para borrar y agregar contenido al modal
Base.prototype.cargaContenidoModal = function (contenido) {
    $('.modal-body').empty().append(contenido);
};

//Muestra la seccion de ayuda
Base.prototype.mostrarAyuda = function (informacion) {
    var _this = this;
    //mostrando la seccion de ayuda
    $('#btnAyudaSistema').on('click', function () {
        $('#seccion-ayuda').addClass('active');
        _this.enviarEvento('/Ayuda/' + informacion, {}, '.btn-cerrar-proyecto', function (respuesta) {
            $('#ayuda-contenido').empty().append(respuesta.informacion);
        });
    });

    //Oculta la seccion de ayuda
    $('#btnCerrarSeccionAyuda').on('click', function () {
        $('#seccion-ayuda').removeClass('active');
        $('#ayuda-contenido').empty();
    });

};

/*
 * Desbloquear formulario de elementos que tienen disabled
 * @param {objeto} objeto El nombre del formulario para recorrer por cada unos de los campos que lo conforman.
 * @returns {array} Regresa la lista de Id de los elementos que se les quito la propiedad disable.
 */


Base.prototype.desbloquearFormulario = function (objeto) {
    var selectores = ['select', 'input', 'table', 'textarea', 'a'];
    var id = [];
    $.each(selectores, function (key, value) {
        var etiquetas = $(objeto).find(value);

        if (value === 'a') {
            $.each(etiquetas, function (key, etiqueta) {
                if (!$(this).hasClass('paginate_button')) {
                    id.push('#' + etiquetas.attr('id'));
                    $('#' + etiquetas.attr('id')).removeClass('disabled');
                }
            });
        } else if (value === 'table') {
            $.each(etiquetas, function (key, etiqueta) {
                id.push('#' + $(this).attr('id'));
                if (!$(this).attr('data-editar')) {
                    $(this).attr('data-editar', 'true');
                } else {
                    $(this).removeAttr('data-editar');
                    $(this).attr('data-editar', 'true');
                }
            });
        } else {
            $.each(etiquetas, function (key, etiqueta) {
                if (typeof $(this).attr('id') !== 'undefined') {
                    id.push('#' + $(this).attr('id'));
                    $('#' + $(this).attr('id')).removeAttr('disabled');
                }
            });
        }

    });
    return id;
};

/*
 * Bloquea los elementos de un formulario
 * @param {array} Ids Solicita el arreglo de los elementos del formulario (los Id de los elementos)
 */
Base.prototype.bloquearFormulario = function (Ids) {
    $.each(Ids, function (key, value) {
        var etiqueta = $(value)[0].tagName.toLowerCase();
        if (etiqueta === 'a') {
            if (!$(value).hasClass('disabled')) {
                $(value).addClass('disabled');
            }
        } else if (etiqueta === 'table') {
            if ($(value).attr('data-editar') === 'true') {
                $(value).removeAttr('data-editar');
                $(value).attr('data-editar', 'false');
            }
        } else {
            if (!$(value).attr('disabled')) {
                $(value).attr('disabled', 'disabled');
            }
        }
    });
};

/*
 * obtiene los datos de un formulario
 * @param {array} Ids Solicita el arreglo de los elementos del formulario (los Id de los elementos)
 * @returns {array} Regresa los valores de los campos de los formularios. Este array esta conformado por arreglos.
 * 
 * Nota: es importante que los elementos del formulario esten definido la propiedad name en cada uno de ellos.
 */

Base.prototype.datosFormulario = function (elementos) {
    var datos = [];
    $.each(elementos, function (key, value) {
        var etiqueta = $(value)[0].tagName.toLowerCase();
        if (etiqueta !== 'a' && etiqueta !== 'table') {
            datos.push({name: $(value).attr('name'), valor: $(value).val()});
        } else if (etiqueta === 'table') {
            var filas = $(value).DataTable().rows().data();
            var datosTabla = [];
            for (var i = 0; i < filas.length; i++) {
                datosTabla.push(filas[i]);
            }
            datos.push({datosTabla: datosTabla});
        }
    });
    return datos;
};

/*
 * Carga la informacion que tenian anteriorimente los Ids del formulario
 * 
 * @param {array} arguments[0] Recibe la lista de ids del formulario 
 * @param {array} arguments[1] Recibe la lista de valores que anteriormente tenia el formulario
 * @param {object} arguments[2] recibe el objeto tabla para cargar la informacion de la tabla.
 */

Base.prototype.cargarDatosAntiguosFormulario = function () {
    var listaId = arguments[0];
    var valoresAntiguos = arguments[1];
    var tabla = arguments[2];
    $.each(valoresAntiguos, function (key, datosAntiguos) {
        $.each(listaId, function (key, id) {
            var etiqueta = $(id)[0].tagName.toLowerCase();
            if (etiqueta !== 'a' && etiqueta !== 'table') {
                if ($(id).attr('name') === datosAntiguos.name && typeof datosAntiguos.name !== 'undefined') {
                    $(id).val(datosAntiguos.valor);
                }
            } else if (etiqueta === 'table') {
                if (typeof datosAntiguos.datosTabla !== 'undefined') {
                    tabla.limpiarTabla(id);
                    $.each(datosAntiguos.datosTabla, function (key, fila) {
                        tabla.agregarFila(id, fila);
                    });
                }
            }
        });
    });
};

Base.prototype.mensajeValidar = function () {
    var mensaje = arguments[0];
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').addClass('hidden');
    var html = '<div id="seccionConfirmacion" >\n\
                        <div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>' + mensaje + '</h3>\n\
                            </div>\n\
                      </div>';
    html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConfirmacion" type="button" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarConfirmacion" type="button" class="btn btn-sm btn-warning"><i class="fa fa-times"></i> Cancelar</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>';
    return html;
};

Base.prototype.mensajeConfirmacion = function () {
    var _this = this;
    var mensaje = arguments[0];
    var titulo = arguments[1];
    var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>' + mensaje + '</h3>\n\
                            </div>\n\
                      </div>';
    _this.mostrarModal(titulo, html);
    $('#btnModalConfirmar').addClass('hidden');
    $('#btnModalAbortar').removeClass('hidden');
    $('#btnModalAbortar').empty().append('Cerrar');
    $('#btnModalAbortar').on('click', function () {
        location.reload();
    });
};

Base.prototype.plasmarInformacionSD = function () {
    var _this = this;
    //mostrando la seccion para modificar SD

    $('#btnInformacionSD').off("click");
    $('#btnInformacionSD').on('click', function () {
        var html = '<div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Servicio *</label>\n\
                                    <input id="inputServicioSD" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div id="errorServicioSD"></div>\n\
                            </div>\n\
                        </div>';
        _this.mostrarModal('Administrar SD', html);
        $('#btnModalConfirmar').on('click', function () {
            var servicio = $('#inputServicioSD').val();

            if (servicio !== '') {
                var data = {servicio: servicio};
                _this.enviarEvento('/Generales/ServiceDesk/ValidarServicio', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta === 'noExisteServicio') {
                        _this.mostrarMensaje('#errorServicioSD', false, 'No existe el servicio.', 3000);
                    } else if (respuesta === 'noTieneFolio') {
                        _this.mostrarMensaje('#errorServicioSD', false, 'No tiene folio asociado al servicio.', 3000);
                    } else {
                        _this.mensajeConfirmacion('Datos colocados en SD con exito', 'Correcto');
                    }
                });
            } else {
                _this.mostrarMensaje('#errorServicioSD', false, 'Debes colocar el servicio.', 3000);

            }
        });
    });
};

Base.prototype.eventosVueltasMantenimiento = function () {
    var _this = this;
    //mostrando la seccion para modificar SD

    $('#btnAgregarVueltaMantenimiento').off("click");
    $('#btnAgregarVueltaMantenimiento').on('click', function () {
        var html = '<div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Servicio *</label>\n\
                                    <input id="inputServicioVueltaMantenimiento" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                      <div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Folio *</label>\n\
                                    <input id="inputFolioVueltaMantenimiento" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div id="errorServicioAgregarVueltaMantemiento"></div>\n\
                            </div>\n\
                        </div>';
        _this.mostrarModal('Agregar vuelta mantenimiento', html);

        $('#btnModalConfirmar').off("click");
        $('#btnModalConfirmar').on('click', function () {
            var servicio = $('#inputServicioVueltaMantenimiento').val();
            var folio = $('#inputFolioVueltaMantenimiento').val();

            if (servicio !== '') {
                var data = {servicio: servicio, folio: folio};
                _this.enviarEvento('/Generales/Servicio/AgregarVueltaAsociadoMantenimiento', data, '#modal-dialogo', function (respuesta) {
                    _this.mensajeConfirmacion('Se agrego la vuelta correctamente.', 'Correcto');
                });
            } else {
                _this.mostrarMensaje('#errorServicioAgregarVueltaMantemiento', false, 'Debes colocar el servicio.', 3000);

            }
        });
    });

    $('#btnCrearPDFVueltaMantenimiento').off("click");
    $('#btnCrearPDFVueltaMantenimiento').on('click', function () {
        var html = '<div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Servicio *</label>\n\
                                    <input id="inputServicioVueltaMantenimiento" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                      <div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Folio *</label>\n\
                                    <input id="inputFolioVueltaMantenimiento" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                      <div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Ticket *</label>\n\
                                    <input id="inputTicketVueltaMantenimiento" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div id="errorServicioAgregarVueltaMantemiento"></div>\n\
                            </div>\n\
                        </div>';
        _this.mostrarModal('Crear PDF vuelta mantenimiento', html);

        $('#btnModalConfirmar').off("click");
        $('#btnModalConfirmar').on('click', function () {
            var servicio = $('#inputServicioVueltaMantenimiento').val();
            var folio = $('#inputFolioVueltaMantenimiento').val();
            var ticket = $('#inputTicketVueltaMantenimiento').val();

            if (servicio !== '') {
                var data = {servicio: servicio, folio: folio, ticket: ticket};
                _this.enviarEvento('/Generales/Servicio/CrearPDFVueltaAsociadoMantenimiento', data, '#modal-dialogo', function (respuesta) {
                    _this.mensajeConfirmacion('Se creo el archivo correctamente.', 'Correcto');
                });
            } else {
                _this.mostrarMensaje('#errorServicioAgregarVueltaMantemiento', false, 'Debes colocar el servicio.', 3000);
            }
        });
    });

    $('#btnAgregarVueltaCorrectivo').off("click");
    $('#btnAgregarVueltaCorrectivo').on('click', function () {
        var html = '<div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Servicio *</label>\n\
                                    <input id="inputServicioVueltaCorrectivo" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div id="errorServicioAgregarVueltaMantemiento"></div>\n\
                            </div>\n\
                        </div>';
        _this.mostrarModal('Agregar vuelta mantenimiento', html);

        $('#btnModalConfirmar').off("click");
        $('#btnModalConfirmar').on('click', function () {
            var servicio = $('#inputServicioVueltaCorrectivo').val();

            if (servicio !== '') {
                var data = {servicio: servicio};
                _this.enviarEvento('/Generales/Servicio/GuardarVueltaAsociadoSinFirma', data, '#modal-dialogo', function (respuesta) {
                    _this.mensajeConfirmacion('Se agrego la vuelta correctamente.', 'Correcto');
                });
            } else {
                _this.mostrarMensaje('#errorServicioAgregarVueltaMantemiento', false, 'Debes colocar el servicio.', 3000);

            }
        });
    });
    
    $('#btnAgregarVueltaChecklist').off("click");
    $('#btnAgregarVueltaChecklist').on('click', function () {
        var html = '<div class="row">\n\
                        <div class="col-md-12">\n\
                                <div class="form-group">\n\
                                    <label>Servicio *</label>\n\
                                    <input id="inputServicioVueltaChecklist" type="text" class="form-control" data-parsley-type="number"/>\n\
                                </div>\n\
                            </div>\n\
                      </div>\n\
                        <div class="row m-t-10">\n\
                            <div class="col-md-12">\n\
                                <div id="errorServicioAgregarVueltaChecklist"></div>\n\
                            </div>\n\
                        </div>';
        _this.mostrarModal('Agregar vuelta checklist', html);

        $('#btnModalConfirmar').off("click");
        $('#btnModalConfirmar').on('click', function () {
            var servicio = $('#inputServicioVueltaChecklist').val();

            if (servicio !== '') {
                var data = {servicio: servicio};
                _this.enviarEvento('/Generales/Servicio/GuardarVueltaAsociadoSinFirma', data, '#modal-dialogo', function (respuesta) {
                    _this.mensajeConfirmacion('Se agrego la vuelta correctamente.', 'Correcto');
                });
            } else {
                _this.mostrarMensaje('#errorServicioAgregarVueltaChecklist', false, 'Debes colocar el servicio.', 3000);

            }
        });
    });
};

Base.prototype.cambiarDiv = function () {
    var _div1 = arguments[0];
    var _div2 = arguments[1];
    var _div3 = arguments[2] || "";
    $(_div1).fadeOut(400, function () {
        $(_div2).fadeIn(400, arguments[2]);
    });

    $(_div2 + " #btnRegresar").off("click");
    $(_div2 + " #btnRegresar").on("click", function () {
        if (_div3 === "") {
            $(_div2).fadeOut(400, function () {
                $(_div1).fadeIn(400, function () {
                    $(_div2).empty();
                });
            });
        } else {
            $(_div2).fadeOut(400, function () {
                $(_div3).fadeIn(400, function () {
                    $(_div2).empty();
                });
            });
        }
    });
}

Base.prototype.mostraDiv = function () {
    var _div = arguments[0];
    if (arguments.length > 1) {
        $(_div).fadeIn(400, arguments[1]);
    } else {
        $(_div).fadeIn(400);
    }
}

Base.prototype.ocultarDiv = function () {
    var _div = arguments[0];
    if (arguments.length > 1) {
        $(_div).fadeOut(400, arguments[1]);
    } else {
        $(_div).fadeOut(400);
    }
}

Base.prototype.validarCampo = function () {
    var _this = this;
    var arrayCampos = arguments[0];
    var divError = arguments[1];
    var objeto = arrayCampos.objeto;
    var mensajeError = arrayCampos.mensajeError;
    var campoValidar = $(objeto).val();

    if (campoValidar !== undefined) {
        if (campoValidar !== '') {
            return true;
        } else {
            _this.mostrarMensaje(divError, false, mensajeError, 3000);
            return false;
        }
    } else {
        _this.mostrarMensaje(divError, false, mensajeError, 3000);
        return false;
    }
}

Base.prototype.validarCamposObjetos = function () {
    var _this = this;
    var arrayCampos = arguments[0];
    var divError = arguments[1];
    var resultado = true;
    $.each(arrayCampos, function (k, v) {
        if (resultado) {
            if (!_this.validarCampo(v, divError)) {
                resultado = false;
            }
        }
    });
    return resultado;
}
