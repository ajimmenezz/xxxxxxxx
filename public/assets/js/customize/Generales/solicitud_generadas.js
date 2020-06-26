$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var file = new Upload();
    var nota = new Nota();
    var calendario = new Fecha();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    //Creando tabla de solicitudes generadas
    tabla.generaTablaPersonal('#data-table-solicitudes-generadas', null, null, true, true);

    //Evento donde se muestra la informacion de la solicitud
    $('#data-table-solicitudes-generadas tbody').on('click', 'tr', function () {
        var datos = $('#data-table-solicitudes-generadas').DataTable().row(this).data();
        var solicitud = datos[0];
        var data = {solicitud: solicitud, operacion: '1'};
        var areas = [];
        var clientes = [];
        var opcionArea = null;
        var opcionPrioridad = null;
        var notas = '';
        evento.enviarEvento('Solicitud/Solicitud_Datos', data, '#tablaSolicitudes', function (respuesta) {

            evento.limpiarFormulario("#formActualizarSolicitud");
            $('#tablaSolicitudes').addClass('hidden');
            $('#informacionSolicitud').removeClass('hidden').attr('data-solicitud', datos[0]);
            $('#numeroSolicitudInterna h4').empty().append('Solicitud ' + datos[0]);

            $.each(respuesta.areas, function (key, valor) {
                areas.push({id: valor.Id, text: valor.Nombre});
            });
            $.each(respuesta.departamentos, function (key, valor) {
                if (valor.Id === respuesta.datos.IdDepartamento) {
                    opcionArea = valor.IdArea;
                }
            });

            $.each(respuesta.cliente, function (key, valor) {
                clientes.push({id: valor.Id, text: valor.Nombre});
            });

            $.each(respuesta.prioridades, function (key, valor) {
                if (valor.id === respuesta.datos.IdPrioridad) {
                    opcionPrioridad = valor.id;
                }
            });

            select.cargaDatos('#selectAreasSolicitud', areas, {id: 'sinArea', text: 'No conozco el área'});
            select.cargaDatos('#selectClienteSolicitudGeneradas', clientes);
            select.cargaDatos('#selectPrioridadSolicitud', respuesta.prioridades);
            select.cambiarOpcion('#selectAreasSolicitud', opcionArea);
            select.cambiarOpcion('#selectPrioridadSolicitud', opcionPrioridad);
            $('#inputAsuntoSolicitudGeneradas').val(datos[1]);
            $('#textareaDescripcionSolicitud').empty().append(respuesta.datos.detalles[0].Descripcion);
            $('#inputFolioSolicitud').val(respuesta.datos.Folio);
            select.setOpcionesSelect('#selectDepartamentoSolicitud', respuesta.departamentos, $('#selectAreasSolicitud').val(), 'IdArea');
            select.cambiarOpcion('#selectDepartamentoSolicitud', respuesta.datos.IdDepartamento);

            if (respuesta.datos.FechaTentativa === '0000-00-00 00:00:00') {
                respuesta.datos.FechaTentativa = '';
            }

            if (respuesta.datos.FechaLimite === '0000-00-00 00:00:00') {
                respuesta.datos.FechaLimite = '';
            }

            $('#inputProgramada').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            }).val(respuesta.datos.FechaTentativa);

            $('#inputLimiteAtencion').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            }).val(respuesta.datos.FechaLimite);

            $('#selectAreasSolicitud').on('change', function () {
                if ($(this).val() !== '' && $(this).val() !== 'sinArea') {
                    select.setOpcionesSelect('#selectDepartamentoSolicitud', respuesta.departamentos, $('#selectAreasSolicitud').val(), 'IdArea');
                } else {
                    select.setOpcionesSelect('#selectDepartamentoSolicitud', respuesta.departamentos, $('#selectAreasSolicitud').val(), 'IdArea', {id: 'sinDepartamento', text: 'Sin Departamento'});
                    select.cambiarOpcion('#selectDepartamentoSolicitud', 'sinDepartamento');
                }
            });

            $("#btnAgregarNotaSolicitud").off("click");
            $("#btnAgregarNotaSolicitud").on("click", function () {
                $(this).addClass('hidden');
                $("#divFormAgregarNotaSolicitud").removeClass('hidden');
                $('#txtAgregarNotasSolicitud').removeAttr('disabled');
            });

            $("#btnCancelarAgregarNotaSolicitud").off("click");
            $("#btnCancelarAgregarNotaSolicitud").on("click", function () {
                $("#divFormAgregarNotaSolicitud").addClass('hidden');
                $("#btnAgregarNotaSolicitud").removeClass('hidden');
                evento.limpiarFormulario("#formAgregarNotasSolicitud");
            });

            $('#btnConfirmarAgregarNotaSolicitud').off('click');
            $('#btnConfirmarAgregarNotaSolicitud').on('click', function () {
                var observaciones = $('#txtAgregarNotasSolicitud').val();
                var data = {solicitud: solicitud, observaciones: observaciones};
                if (observaciones !== '') {
                    evento.enviarEvento('Solicitud/GuardarNotaSolicitud', data, '#informacionSolicitud', function (respuesta) {
                        if (respuesta) {
                            evento.limpiarFormulario("#formAgregarNotasSolicitud");
                            $("#divFormAgregarNotaSolicitud").addClass('hidden');
                            $("#btnAgregarNotaSolicitud").removeClass('hidden');
                            $('#listaNotas').empty().append('');
                            cargarNotas(respuesta);
                        } else {
                            evento.mostrarMensaje('#errorAgregarNotaSolicitud', false, 'No se pudo agregar la nota. Intente de nuevo por favor.', 3000);
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorAgregarNotaSolicitud', false, 'Debe capturar una nota para guardarlo.', 3000);
                }
            });

            $('#inputEvidencias').empty().append('\
                            <div class="form-group">\n\
                                <label for="evidenciaSolicitud">Evidencia</label>\n\
                                <input id="inputEvidenciasSolicitud" name="evidenciasSolicitud[]" type="file" multiple>\n\
                        </div>');
            file.crearUpload(
                    '#inputEvidenciasSolicitud',
                    'Solicitud/Solicitud_Actualizar',
                    null,
                    null,
                    respuesta.evidenciasUrl,
                    'Solicitud/Solicitud_EliminarEvidencia',
                    datos[0]);


            if (typeof respuesta.notas === 'object') {
                cargarNotas(respuesta.notas);
            } else {
                notas = '<li class="media media-sm">No hay conversaciones disponibles.</li>';
                $('#listaNotas').empty().append(notas);
            }

            $("select, textarea, input").prop("disabled", false);
            $('#inputEvidenciasSolicitud').fileinput('enable');
            $("#btnActualizarSolicitud, #btnCancelarSolicitud").show();

            $(".tab-option").removeClass("active");
            $("#tab-menu-Solicitud").addClass("active");
            $(".tab-pane").removeClass("active").removeClass("in");
            $("#Solicitud").addClass("active").addClass("in");
            $("#tab-menu-Seguimiento").addClass("hidden");

            if (respuesta.datos.IdEstatus === 2 || respuesta.datos.IdEstatus === "2") {
                $("select, textarea, input").prop("disabled", true);
                $('#inputEvidenciasSolicitud').fileinput('disable');
                $("#btnActualizarSolicitud, #btnCancelarSolicitud").hide();
                $("#tab-menu-Seguimiento").removeClass("hidden");
            }

            $("#divSeguimiento").empty().append(respuesta.htmlSeguimiento);
            tabla.generaTablaPersonal("#data-table-servicios-relacionados");

            $('#data-table-servicios-relacionados tbody').on('click', 'tr', function () {
                var datos = $('#data-table-servicios-relacionados').DataTable().row(this).data();
                var data = {'servicio': datos[0], solicitud: solicitud};
                evento.enviarEvento('Servicio/Servicio_Detalles', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta.ids.IdEstatus === '5') {
                        $('#btnValidarServicio').removeClass('hidden');
                        $('#btnRechazarServicio').removeClass('hidden');
                        $('#btnValidarServicio').on('click', function () {
                            var modalMensaje = mensajeConfirmacion("¿Realmente quiere Concluir el Servicio?");
                            $('#btnModalConfirmar').addClass('hidden');
                            $('#btnModalAbortar').addClass('hidden');
                            evento.mostrarModal('"Advertencia"', modalMensaje);
                            $('#btnModalConfirmar').off('click');
                            $('#btnAceptarConcluirServicio').on('click', function () {
                                $('#btnAceptarConcluirServicio').attr('disabled', 'disabled');
                                $('#btnCancelar').attr('disabled', 'disabled');
                                var data = {'servicio': datos[0], ticket: datos[1], idSolicitud: respuesta.ids.IdSolicitud, servicioConcluir: false};
                                evento.enviarEvento('Servicio/Verificar_Servicio', data, '#seccionValidarServicio', function (respuesta) {
                                    if (respuesta === true) {
                                        mensajeModal('Se Valido con Exito', 'Correcto', true);
                                    } else {
                                        mensajeModal('Ocurrió el error "' + respuesta + '" Por favor contacte al administrador del Sistema AdIST.', 'Error', true);
                                    }
                                });
                            });
                            //Envento para cerrar el modal
                            $('#btnCancelar').on('click', function () {
                                evento.cerrarModal();
                            });
                        });
                        $('#btnRechazarServicio').on('click', function () {
                            var modalMensaje = mensajeConfirmacion("¿Realmente quiere Rechazar el Servicio?");
                            $('#btnModalConfirmar').addClass('hidden');
                            $('#btnModalAbortar').addClass('hidden');
                            evento.mostrarModal('Advertencia', modalMensaje);
                            $('#btnModalConfirmar').off('click');
                            $('#btnAceptarConcluirServicio').on('click', function () {
                                var formularioRechazarServicio = modalRecharServicio();
                                $('#btnModalConfirmar').addClass('hidden');
                                $('#btnModalAbortar').addClass('hidden');
                                evento.mostrarModal('"Rechazar Servicio"', formularioRechazarServicio);
                                $('#btnModalConfirmar').off('click');
                                $('#btnGuardarDescripionServicio').on('click', function () {
                                    if (evento.validarFormulario('#formRechazarFormulario')) {
                                        var descripcion = $('#inputDescripcionRecharzarServicio').val();
                                        var data = {'servicio': datos[0], idSolicitud: respuesta.ids.IdSolicitud, descripcion: descripcion, atiende: respuesta.ids.Atiende, ticket: datos[1]};
                                        $('#btnGuardarDescripionServicio').attr('disabled', 'disabled');
                                        $('#btnCancelarRechazarServicio').attr('disabled', 'disabled');
                                        evento.enviarEvento('Servicio/Rechazar_Servicio', data, '#seccionRechazarServicio', function (respuesta) {
                                            evento.cerrarModal();
                                            $("#informacionSolicitud").removeClass('hidden');
                                            $("#divDetallesServicio").addClass('hidden');
                                            if (respuesta instanceof Array) {
                                                recargandoTablaServiciosRelacionados(respuesta);
                                            } else {
                                                evento.mostrarMensaje('.errorDetallesServicio', false, 'Vuelta a intentarlo', 3000);
                                            }
                                        });
                                    }
                                });
                                $('#btnCancelarRechazarServicio').on('click', function () {
                                    evento.cerrarModal();
                                });
                            });
                            //Envento para cerrar el modal
                            $('#btnCancelar').on('click', function () {
                                evento.cerrarModal();
                            });
                        });
                    } else {
                        $('#btnValidarServicio').addClass('hidden');
                        $('#btnRechazarServicio').addClass('hidden');
                    }
                    $("#informacionSolicitud").addClass('hidden');
                    $("#divDetallesServicio").removeClass('hidden');
                    $("#divDetallesServicio > .panel-body").empty().append(respuesta.html);

                    tabla.generaTablaPersonal("#data-table-detalle-items");
                    $("#divNotasServicio").slimScroll({height: '400px'});
                    nota.initButtons(data, 'Notas');
                    $("#btnGeneraPdfServicio").off("click");
                    $("#btnGeneraPdfServicio").on("click", function () {
                        evento.enviarEvento('Servicio/Servicio_ToPdf', data, '#divDetallesServicio', function (respuesta) {
                            window.open(respuesta.link);
                        });
                    });

                    $('#detallesInformacionServicio').on('click', function (e) {
                        if ($('#detallesServicio').hasClass('hidden')) {
                            $('#detallesServicio').removeClass('hidden');
                            $('#detallesInformacionServicio').empty().html('<h4><a>- Información del Servicio</a></h4>');
                        } else {
                            $('#detallesServicio').addClass('hidden');
                            $('#detallesInformacionServicio').empty().html('<h4><a>+ Información del Servicio</a></h4>');
                        }
                    });
                });
            });


            if (respuesta.datos.IdSucursal !== null) {
                select.cambiarOpcion('#selectClienteSolicitudGeneradas', respuesta.datos.IdCliente);
                mostrarSucursalesCliente(respuesta.sucursales);
                select.cambiarOpcion('#selectSucursalSolicitudGeneradas', respuesta.datos.IdSucursal);
            }


            $('#selectClienteSolicitudGeneradas').on('change', function () {
                mostrarSucursalesCliente(respuesta.sucursales);
            });
        });
    });

    //Regresar panel de seguimiento de solicitud.
    $("#btnRegresarDetalles").on("click", function () {
        $('#tablaSolicitudes').addClass('hidden');
        $('#informacionSolicitud').removeClass('hidden');
        $('#divDetallesServicio').addClass('hidden');
    });

    //Regresar tabla solicitudes
    $('#btnCerrarActualizarSolicitud').on('click', function () {
        $('#tablaSolicitudes').removeClass('hidden');
        $('#informacionSolicitud').addClass('hidden').removeAttr('data-solicitud');
        $('#divDetallesServicio').addClass('hidden');
    });

    //Actualizar Solicitud
    $('#btnActualizarSolicitud').on('click', function () {
        var verificarFechas;
        var data = {
            solicitud: $('#informacionSolicitud').attr('data-solicitud'),
            operacion: '1',
            departamento: $('#selectDepartamentoSolicitud').val(),
            prioridad: $('#selectPrioridadSolicitud').val(),
            descripcion: $('#textareaDescripcionSolicitud').val(),
            asunto: $('#inputAsuntoSolicitudGeneradas').val(),
            folio: $('#inputFolioSolicitud').val(),
            sucursal: $('#selectSucursalSolicitudGeneradas').val(),
            fechaProgramada: $('#inputProgramada').val(),
            fechaLimiteAtencion: $('#inputLimiteAtencion').val()};

        if ($('#inputProgramada').val() !== '' && $('#inputLimiteAtencion').val() !== '') {
            verificarFechas = false;
        } else {
            verificarFechas = true;
        }

        if (evento.validarFormulario('#formActualizarSolicitud')) {
            if (verificarFechas) {
                file.enviarArchivos('#inputEvidenciasSolicitud', 'Solicitud/Solicitud_Actualizar', '#informacionSolicitud', data, function (respuesta) {
                    $('#btnModalConfirmar').addClass('hidden');
                    $('#btnModalAbortar').empty().append('Cerrar');
                    if (respuesta.solicitudes) {
                        evento.mostrarModal('Actaulización Exitosa', '<div class="row"><div class="col-md-12 text-center">Se actualizó con éxito la información.</div></div>');
                        tabla.limpiarTabla('#data-table-solicitudes-generadas');
                        if (respuesta.solicitudes.length > 0) {
                            $.each(respuesta.solicitudes, function (indice, item) {
                                var colorBandera = '';
                                switch (item.IdPrioridad) {
                                    case '0':
                                        colorBandera = 'text-default';
                                        break;
                                    case '1':
                                        colorBandera = 'text-danger';
                                        break;
                                    case '2':
                                        colorBandera = 'text-warning';
                                        break;
                                    case '3':
                                        colorBandera = 'text-success';
                                        break;
                                }
                                var celda = '<i class="fa fa-2x fa-flag fa-inverse ' + colorBandera + '"></i>';
                                tabla.agregarFila('#data-table-solicitudes-generadas', [item.Numero, item.Asunto, item.Ticket, item.Departamento, item.Fecha, item.Estatus, celda]);
                            });
                        }
                    } else {
                        evento.mostrarModal('Error de Actualización', '<div class="row"><div class="col-md-12 text-center">No se pudo actualizar la información por favor de volver a intentarlo.</div></div>');
                    }
                    $('#btnCerrarActualizarSolicitud').trigger('click');
                });
            } else {
                evento.mostrarModal('Error de Actualización', '<div class="row"><div class="col-md-12 text-center">Debe seleccionar solo una fecha.</div></div>');
            }
        }
    });

    //Cancelar Solicitud
    $('#btnCancelarSolicitud').on('click', function () {
        var contenidoformulario = '';
        contenidoformulario += '<form id="formCancelarSolicitud" class="margin-bottom-0 formCancelarSolicitud" data-parsley-validate="true" enctype="multipart/form-data">\n\
                    <div class="row">\n\
                            <div  class="col-md-12 ">\n\
                                <label>Indique la causa de la cancelacion: </label>\n\
                                <div >\n\
                                    <textarea id="textareaDescripcionCancelar" class="form-control textareaDescripcionCancelar" name="descricpcionSolicitud" placeholder="Ingresa la causa de la cancelacióin... " rows="3" data-parsley-required="true" ></textarea>\n\
                                </div>\n\
                            </div>\n\
                    </div>\n\
                    <div class="row">\n\
                            <div  class="col-md-12 ">\n\
                                <div class="text-danger">\n\
                                    Al cancelar la solicitud ya no podrea volver a abrirla.\n\
                                </div>\n\
                            </div>\n\
                        </div></form>\n\
                    <div class="row">\n\
                            <div  class="col-md-12 ">\n\
                                <div class="text-danger">\n\
                                    <div class="mensajeErrorCancelar"></div>\n\
                                </div>\n\
                            </div>\n\
                        </div></form>';
        evento.mostrarModal('Cancelar Solicitud', contenidoformulario);
        $('#btnModalConfirmar').empty().append('Aceptar').addClass('btn-danger');
        $('#btnModalAbortar').empty().append('Cancelar');


        //Se realiza la cancelacion de la solicitud
        $('#btnModalConfirmar').off('click');
        $('#btnModalConfirmar').on('click', function () {
            if (evento.validarFormulario('#formCancelarSolicitud')) {
                var data = {solicitud: $('#informacionSolicitud').attr('data-solicitud'), operacion: '6', descripcion: $('#textareaDescripcionCancelar').val()};
                evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                    $('#btnModalConfirmar').removeClass('btn-danger').addClass('hidden');
                    $('#btnModalAbortar').empty().append('Cerrar');
                    if (typeof respuesta === 'object') {
                        evento.cargaContenidoModal('<div class="row"><div class="col-md-12 text-center">Se cancelo con exito la solicitud.</div></div>');
                        tabla.limpiarTabla('#data-table-solicitudes-generadas');
                        if (respuesta.solicitudes.length > 0) {
                            $.each(respuesta.solicitudes, function (indice, item) {
                                var colorBandera = '';
                                switch (item.IdPrioridad) {
                                    case '0':
                                        colorBandera = 'text-default';
                                        break;
                                    case '1':
                                        colorBandera = 'text-danger';
                                        break;
                                    case '2':
                                        colorBandera = 'text-warning';
                                        break;
                                    case '3':
                                        colorBandera = 'text-success';
                                        break;
                                }
                                var celda = '<i class="fa fa-2x fa-flag fa-inverse ' + colorBandera + '"></i>';
                                tabla.agregarFila('#data-table-solicitudes-generadas', [item.Numero, item.Asunto, item.Ticket, item.Departamento, item.Fecha, item.Estatus, celda]);
                            });
                        }
                    } else {
                        evento.cargaContenidoModal('<div class="row"><div class="col-md-12 text-center">No se pudo cancelar la solicitud. Favor de volver intentarlo.</div></div>');
                    }
                    $('#btnCerrarActualizarSolicitud').trigger('click');
                });
            }

        });

        //Anula la cancelacion de la solitud
        $('#btnModalAbortar').on('click', function () {
            $('#btnModalConfirmar').removeClass('btn-danger');
        });
    });

    var mensajeConfirmacion = function () {
        var mensaje = arguments[0];
        var html = '<div id="seccionValidarServicio" >\n\
                        <div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>' + mensaje + '</h3>\n\
                            </div>\n\
                      </div>';
        html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluirServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelar" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>';
        return html;
    };

    var modalRecharServicio = function () {
        var html = '<div id="seccionRechazarServicio" > ';
        html += '       <div class="row">';
        html += '           <form class="margin-bottom-0" id="formRechazarFormulario" data-parsley-validate="true" >';
        html += '               <div class="col-md-12">';
        html += '                   <div class="form-group">';
        html += '                       <label for="rechazarServicio">Descripción del Rechazo *</label> ';
        html += '                       <input type="text" class="form-control" id="inputDescripcionRecharzarServicio" placeholder="Descripción del por que esta rechazando el servicio" data-parsley-required="true"/> ';
        html += '                   </div>';
        html += '               </div>';
        html += '               <div class="col-md-12">';
        html += '                   <div class="errorRechazarServicio"></div>';
        html += '               </div>';
        html += '               <div class="row m-t-20">';
        html += '                   <div class="col-md-12 text-center">';
        html += '                       <button id="btnGuardarDescripionServicio" type="button" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Aceptar</button>';
        html += '                       <button id="btnCancelarRechazarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cancelar</button>';
        html += '                   </div>';
        html += '               </div>';
        html += '           </form>'
        html += '       </div>';
        html += '</div>';
        html += '';

        return html;
    };

    var recargandoTablaServiciosRelacionados = function (respuesta) {
        tabla.limpiarTabla('#data-table-servicios-relacionados');
        $.each(respuesta, function (key, item) {
            tabla.agregarFila('#data-table-servicios-relacionados', [item.Id, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.Solicita, item.Atiende]);
        });
    };

    var cargarNotas = function (notasSolicitud) {
        var notas = '';
        $.each(notasSolicitud, function (key, value) {
            if (value.Foto === null) {
                value.Foto = '/assets/img/user-5.jpg';
            }
            notas += '<li class="media media-sm">\n\
                                    <a href="javascript:;" class="pull-left">\n\
                                        <img src="' + value.Foto + '" alt="" class="media-object rounded-corner" />\n\
                                    </a>\n\
                                    <div class="media-body">\n\
                                        <h5 class="media-heading">' + value.Nombre + '</h5>\n\
                                        <h6 class="media-heading estatusNota"><span>Estatus Solicitud: <strong id="estatusNota">' + value.Estatus + '</strong></span><span >Fecha: <strong id="fechaNota">' + value.Fecha + '</strong></span></h6>\n\
                                        <h6>Comentario:</h6>\n\
                                        <p><strong>' + value.Nota + '</strong></p>\n\
                                    </div>\n\
                                </li>';
        });
        $('#listaNotas').empty().append(notas);
    }

    var mensajeModal = function () {
        var mensaje = arguments[0];
        var titulo = arguments[1];
        var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>' + mensaje + '</h3>\n\
                            </div>\n\
                      </div>';
        evento.mostrarModal(titulo, html);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').removeClass('hidden');
        $('#btnModalAbortar').empty().append('Cerrar');
        $('#btnModalAbortar').on('click', function () {
            location.reload();
        });
    };

    var mostrarSucursalesCliente = function () {
        var sucursales = arguments[0];

        select.setOpcionesSelect('#selectSucursalSolicitudGeneradas', sucursales, $('#selectClienteSolicitudGeneradas').val(), 'IdCliente');
    }
});