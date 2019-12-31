$(function () {
    //Objetos
    var evento = new Base();
//    var websocket = new Socket();
    var tabla = new Tabla();
    var select = new Select();
    var servicios = new Servicio();

    //Evento que maneja las peticiones del socket
//    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Inicializa funciones de la plantilla
    App.init();

    //Creando tabla de solicitudes generadas
    tabla.generaTablaPersonal('#data-table-solicitudes-asignadas', null, null, true, true, [[0, 'desc']]);

    //Obteniendo informacion de la solictud
    //Evento donde se muestra la informacion de la solicitud
    $('#data-table-solicitudes-asignadas tbody').on('click', 'tr', function () {
        var _fila = this;
        var datos = $('#data-table-solicitudes-asignadas').DataTable().row(this).data();
        var data = {solicitud: datos[0], operacion: '1'};

        evento.enviarEvento('Solicitud/Solicitud_Datos', data, '#seccionSolicitudesAsignadas', function (respuesta) {
            if (respuesta.datosResolucionSD !== undefined) {
                var descripcionSolicitud = '';

                if (respuesta.datosResolucionSD === 'Sin respuesta con ServiceDesk') {
                    descripcionSolicitud = respuesta.datosResolucionSD;
                }

                evento.mostrarModal('Solicitud ' + datos[0], respuesta.formularioSolicitud);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                select.crearSelect('#selectCliente');
                select.crearSelect('#selectSucursal');
                select.crearSelect('#selectServicioDepartamento');
                select.crearSelect('#selectAtiendeServicio');
                select.crearSelect('#selectClasificacion');
                select.crearSelect('#selectReasignarParsonal');
                select.crearSelect('#selectReasignarArea');
                select.crearSelect('#selectReasignarDepartamento');
                tabla.generaTablaPersonal('#data-table-servicio', null, null, null, true);

                //Tabla de solicitud de material de proyecto
                tabla.generaTablaPersonal('#data-table-materiales', null, null, null, true);

                $('#ocultarMostrarSD').on('click', function () {
                    if ($('#contenidoSD').hasClass('hidden')) {
                        $('#contenidoSD').removeClass('hidden');
                        $('#ocultarMostrarSD span').empty().append('- Menos detalles');
                    } else {
                        $('#contenidoSD').addClass('hidden');
                        $('#ocultarMostrarSD span').empty().append('+ Mostrar detalles');
                    }
                });

                $("#selectCliente").on("change", function () {
                    var creator = $("#creator-sd").val();
                    var requester = $("#requester-sd").val();
                    var sucursal_interna = $("#id-sucursal-interna").val();
                    var _cliente = $(this).val();

                    if (_cliente !== "") {
                        evento.enviarEvento('/Proyectos2/Planeacion/SucursalesByCliente', {'id': _cliente}, '#modal-dialogo', function (respuesta) {
                            $("#selectSucursal").empty().append("<option value=''>Selecciona . . .</option>");
                            var _option = 'XX';
                            $.each(respuesta, function (k, v) {
                                if ((creator !== '' && requester !== '' && (v.NombreCinemex == creator || v.NombreCinemex == requester)) || (sucursal_interna !== '' && v.Id == sucursal_interna)) {
                                    _option = v.Id;
                                }
                                $("#selectSucursal").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                            });
                            select.cambiarOpcion("#selectSucursal", _option);
                            $("#selectSucursal").removeAttr("disabled");

                        });
                    } else {
                        $("#selectSucursal").empty().append("<option value=''>Selecciona . . .</option>");
                        $("#selectSucursal").attr("disabled", "disabled");
                        select.cambiarOpcion("#selectSucursal", '');
                    }
                });

                if (respuesta.datos.IdCliente !== null) {
                    select.cambiarOpcion('#selectCliente', respuesta.datos.IdCliente);
                }

                //Evento para select para mostrar el campo selectClasificacion
                $('#selectServicioDepartamento').on('change', function () {
                    if ($(this).val() !== '' && $(this).val() === '5') {
                        $('#selectAtiendeServicio').val('42').trigger('change');
                    } else {
                        if ($('#selectAtiendeServicio').val() === '') {
                            $('#selectAtiendeServicio').val('').trigger('change');
                        }
                    }
                });

                //Evento elimina registro de la tabla servicio
                $('#data-table-servicio tbody').on('click', 'tr', function () {
                    var tabla = $('#data-table-servicio').DataTable();
                    tabla.row(this).remove().draw(false);
                });

                //Agrega el servicio en la tabla 
                $('#btnAgregarServicio').on('click', function () {
                    if (evento.validarFormulario('#formAgregarSservicio')) {
                        tabla.agregarFila('#data-table-servicio', [
                            $('#selectServicioDepartamento option:selected').text(),
                            $('#selectSucursal option:selected').text(),
                            $('#selectAtiendeServicio option:selected').text(),
                            $('#inputDescripcionServicio').val(),
                            $('#selectServicioDepartamento').val(),
                            $('#selectSucursal').val(),
                            $('#selectAtiendeServicio').val()
                        ]);
                        $('#formAgregarSservicio').parsley().reset();
                        select.cambiarOpcion("#selectServicioDepartamento");
                    }
                });

                //Muestra la seccion de reasignar solicitud
                $('#btnReasignarSolicitud').on('click', function () {
                    $('#seccionSeguimiento').addClass('hidden');
                    $('#seccionReasignar').removeClass('hidden');
                });

                //Muestra cierra el modal
                $('#btnCancelarSeguimiento').on('click', function () {
                    evento.cerrarModal();
                });

                //Genera el ticket y los servicios de la solicitud
                $('#btnConfirmarSeguimientoSolicitud').on('click', function () {
                    var cliente = $('#selectCliente').val();
                    if (cliente !== '') {
                        var datosTabla = $('#data-table-servicio').DataTable().rows().data();
                        var servicios = [];
                        if (datosTabla.length > 0) {
                            for (var i = 0; i < datosTabla.length; i++) {
                                servicios.push({servicio: datosTabla[i][4], sucursal: datosTabla[i][5], atiende: datosTabla[i][6], descripcion: datosTabla[i][3], nombreServicio: datosTabla[i][0], nombreSucursal: datosTabla[i][1]});
                            }
                            var data = {solicitud: datos[0], ticket: datos[3], servicios: servicios, cliente: cliente, descripcion: descripcionSolicitud}
                            evento.enviarEvento('Solicitud/Generar_Ticket', data, '#modal-dialogo', function (respuesta) {
                                var fila = [];
                                if (typeof respuesta === 'object') {
                                    if (respuesta.responce === true) {
                                        var numeroTicket = respuesta.ticket;
//                                        eventoCalendario(numeroTicket);
                                        evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12 text-center">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>Se genero el ticket <strong>' + respuesta.ticket + '</strong>  con exito y el personal encargado de brindar \n\
                                                                           seguimiento al servicio ya fue notificado para su seguimiento. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                        $('#data-table-solicitudes-asignadas').DataTable().row(this).remove().draw();
                                    } else {
                                        let htmlfolio = '';
                                        if (respuesta.folios != null && respuesta.folios != 0) {
                                            htmlfolio = ' y el folio <strong>' + respuesta.folios + '</strong>'
                                        }
                                        let html = '<div class="row">\n\
                                                <div class="col-md-12 text-center">\n\
                                                    <div class="form-group">\n\
                                                        <p>Existe el Ticket <strong>' + respuesta.ticket + '</strong>' + htmlfolio + ' para esta solicitud</p>\n\
                                                        <p>¿Quieres generar otro?</p>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>';
                                        evento.cargaContenidoModal(html);
                                        $('#btnModalConfirmar').removeClass('hidden').empty().append('Aceptar');
                                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                        $('#btnModalConfirmar').on('click', function () {
                                            $('#btnModalConfirmar').off('click');
                                            data['generaOtro'] = true;
                                            evento.enviarEvento('Solicitud/Generar_Ticket', data, '#modal-dialogo', function (respuesta) {
                                                if (typeof respuesta === 'object') {
                                                    var numeroTicket = respuesta.ticket;
                                                    evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12 text-center">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>Se genero el ticket <strong>' + respuesta.ticket + '</strong>  con exito y el personal encargado de brindar \n\
                                                                           seguimiento al servicio ya fue notificado para su seguimiento. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                                    $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                                    $('#data-table-solicitudes-asignadas').DataTable().row(this).remove().draw();
                                                } else {
                                                    evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>No se pudo generar el ticket por favor de volver a intentarlo. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                                    $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                                }
                                            });
                                        });
                                    }
                                } else {
                                    evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>No se pudo generar el ticket por favor de volver a intentarlo. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                    $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                }
                            });
                        } else {
                            evento.mostrarMensaje('#errorGenerarTicket', false, 'Debes definir almenos un servicio de la solicitud.', 3000);
                        }
                    } else {
                        evento.mostrarMensaje('#errorGenerarTicket', false, 'Debes definir el cliente para el servicio.', 3000);
                    }
                });

                //Evento de select personal para seleccionar su area y departamento
                $('#selectReasignarParsonal').on('change', function () {
                    var perfil = $(this).val();
                    var dataPerfil = {perfil: perfil};
                    evento.enviarEvento('Solicitud/BuscarAreaDepartamento', dataPerfil, '#seccionSeguimiento', function (respuesta) {
                        if (respuesta) {
                            select.cambiarOpcion('#selectReasignarArea', respuesta[0].Area);
                            select.cambiarOpcion('#selectReasignarDepartamento', respuesta[0].Departamento);
                        }
                    });
                });

                //Cambia las opciones de select departamento apartir del area
                $('#selectReasignarArea').on('change', function () {
                    $('#selectReasignarDepartamento').removeAttr('disabled');
                    select.setOpcionesSelect('#selectReasignarDepartamento', respuesta.departamentos, $('#selectReasignarArea').val(), 'IdArea');
                });

                //Muestra la seccion de seguimiento
                $('#btnCancelarReasignar').on('click', function () {
                    $('#seccionSeguimiento').removeClass('hidden');
                    $('#seccionReasignar').addClass('hidden');
                });

                //Se confirma la reasignacion de la solicitud
                $('#btnConfirmarReasignar').on('click', function () {
                    if (evento.validarFormulario('#formReasignarSolicitud')) {
                        var data = {solicitud: datos[0], operacion: '5', departamento: $('#selectReasignarDepartamento').val(), descripcion: $('#textareaDescripcionReasignacion').val()};
                        evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                            var fila = [];
                            if (typeof respuesta === 'object') {
                                evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    <div class="form-group">\n\
                                        <p>Se a reasignado con exito la solicitud. <br>Se le notifico al departamento asignado para que le brinde el seguimiento. </p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                                $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                tabla.limpiarTabla('#data-table-solicitudes-asignadas');
                                if (respuesta.solicitudes.solicitudes.length > 0) {
                                    $.each(respuesta.solicitudes.solicitudes, function (indice, item) {
                                        fila = [];
                                        fila.push(item.Numero);
                                        fila.push(item.Asunto);
                                        fila.push(item.Tipo);
                                        fila.push(item.Ticket);
                                        if (respuesta.solicitudes.SolicitudesSD.length > 0) {
                                            $.each(respuesta.solicitudes.SolicitudesSD, function (key, value) {
                                                if (value.solicitud === item.Numero) {
                                                    fila.push(value.datos.Solicitante);
                                                    fila.push(value.datos.Asunto);
                                                } else {
                                                    fila.push(item.Solicita);
                                                    fila.push('');
                                                }
                                            });
                                        } else {
                                            fila.push(item.Solicita);
                                            fila.push('');
                                        }
                                        fila.push(item.Fecha);
                                        fila.push(item.Estatus);
                                        tabla.agregarFila('#data-table-solicitudes-asignadas', fila);
                                    });
                                }
                            } else {
                                evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12">\n\
                                    <div class="form-group">\n\
                                        <p>No se pudo reasignar la solicitud por favor de volver a intentarlo. </p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                                $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                            }
                        });
                    }
                });

                //Muestra la seccion de solicitud rechazada
                $('#btnRechazarSolicitud').on('click', function () {
                    if (datos[2] === 'SERVICE DESK') {
                        var data = {solicitud: datos[0], operacion: '3'};
                        evento.enviarEvento('Solicitud/Datos_SistemaExterno', data, '#modal-dialogo', function (respuesta) {
                            var data = [];
                            $.each(respuesta.tecnicosSD.operation.details, function (key, value) {
                                data.push({id: value.TECHNICIANID, text: value.TECHNICIANNAME});
                            })
                            select.crearSelect('#selectTecnicosSD');
                            select.cargaDatos('#selectTecnicosSD', data);
                            $('#seccionSeguimiento').addClass('hidden');
                            $('#seccionRechazar').removeClass('hidden');
                        });
                    } else {
                        $('#seccionSeguimiento').addClass('hidden');
                        $('#seccionRechazar').removeClass('hidden');
                    }
                });

                $('#btnRechazarSolicitudSD').on('click', function () {
                    evento.enviarEvento('Solicitud/FormularioRechazarSolicitud', {}, '#modal-dialogo', function (respuesta) {
                        evento.cargaContenidoModal(respuesta.html);
                        $('#btnRechazarSD').on('click', function () {
                            var descripcion = $('#inputDescripcionRechazo').val();

                            if (descripcion !== '') {
                                var data = {solicitud: datos[0], descripcion: descripcion};
                                evento.enviarEvento('Solicitud/RechazarSolicitudSD', data, '#modal-dialogo', function (respuesta) {
                                    if (respuesta.code === 200) {
                                        location.reload();
                                    } else {
                                        evento.mostrarMensaje('#errorRechazar', false, respuesta.message, 3000);
                                    }
                                });
                            } else {
                                evento.mostrarMensaje('#errorRechazar', false, 'Debes escribir la descripción del rechazo.', 3000);
                            }
                        });

                        $('#btnCancelarRechazar').on('click', function () {
                            evento.cerrarModal();
                        });
                    });
                });

                //Muestra la seccion de seguimiento cuando se cancela el rechazo
                $('#btnCancelarRechazo').on('click', function () {
                    $('#seccionSeguimiento').removeClass('hidden');
                    $('#seccionRechazar').addClass('hidden');
                });

                //Se confirma el rechazo de la solicitud
                $('#btnConfirmarRechazo').on('click', function () {
                    if (evento.validarFormulario('#formRechazarSolicitud')) {
                        if (datos[2] === 'SERVICE DESK') {
                            var data = {solicitud: datos[0], operacion: '4', tecnicoSD: $('#selectTecnicosSD').val(), descripcion: $('#textareaDescripcionRechazada').val()};
                            var mensajeExito = 'Se a reasignado el folio con exito.';
                            var mensajeError = 'No se pudo reasignar el folio Service Desk por favor de volver a intentarlo.';
                        } else {
                            var data = {solicitud: datos[0], operacion: '4', descripcion: $('#textareaDescripcionRechazada').val()};
                            var mensajeExito = 'Se a rechazado con exito la solicitud. <br>Se le notifico al solicitante del por que no sea brindado el seguimiento.';
                            var mensajeError = 'No se pudo generar el rechazo de la solicitud por favor de volver a intentarlo.';
                        }
                        evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                            var fila = [];
                            if (typeof respuesta === 'object') {
                                evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    <div class="form-group">\n\
                                        <p>' + mensajeExito + '</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                                $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                tabla.limpiarTabla('#data-table-solicitudes-asignadas');
                                if (respuesta.solicitudes.solicitudes.length > 0) {
                                    $.each(respuesta.solicitudes.solicitudes, function (indice, item) {
                                        fila = [];
                                        fila.push(item.Numero);
                                        fila.push(item.Asunto);
                                        fila.push(item.Tipo);
                                        fila.push(item.Ticket);
                                        fila.push(item.Sucursal);
                                        if (respuesta.solicitudes.SolicitudesSD.length > 0) {
                                            $.each(respuesta.solicitudes.SolicitudesSD, function (key, value) {
                                                if (value.solicitud === item.Numero) {
                                                    fila.push(value.datos.Solicitante);
                                                    fila.push(value.datos.Asunto);
                                                } else {
                                                    fila.push(item.Solicita);
                                                    fila.push('');
                                                }
                                            });
                                        } else {
                                            fila.push(item.Solicita);
                                            fila.push('');
                                        }
                                        fila.push(item.Fecha);
                                        fila.push(item.Estatus);
                                        tabla.agregarFila('#data-table-solicitudes-asignadas', fila);
                                    });
                                }
                            } else {
                                evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12">\n\
                                    <div class="form-group">\n\
                                        <p>' + mensajeError + '</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                                $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                            }
                        });
                    }
                });

                if (respuesta.autorizacionAtenderServicio) {
                    $('#btnAtenderSolicitud').removeClass('hidden');
                    $('#btnAtenderSolicitud').on('click', function () {
                        var data = {solicitud: datos[0]};
                        evento.enviarEvento('Solicitud/Atencion_Solcitud', data, '#modal-dialogo', function (respuesta) {
                            if (respuesta) {
                                evento.mensajeConfirmacion('Solicitud atendida', 'Correcto');
                            } else {
                                evento.mensajeConfirmacion('Lo sentimos se produjo un error favor de contactar al área correspondiente', 'Error');
                            }
                        });
                    });
                }
//                }
            } else {
                eventosSolicitudesAsiganadas(respuesta, datos);
            }
        });
    });

    var eventosSolicitudesAsiganadas = function () {
        var respuesta = arguments[0];
        var datos = arguments[1];
        var descripcionSolicitud;
        if (typeof respuesta.datosSD === 'object') {
            descripcionSolicitud = respuesta.datosSD.DESCRIPTION;
        } else {
            descripcionSolicitud = respuesta.datos.detalles[0].Descripcion;
        }

        evento.mostrarModal('Solicitud ' + datos[0], respuesta.formularioSolicitud);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');
        select.crearSelect('#selectCliente');
        select.crearSelect('#selectSucursal');
        select.crearSelect('#selectServicioDepartamento');
        select.crearSelect('#selectAtiendeServicio');
        select.crearSelect('#selectClasificacion');
        select.crearSelect('#selectReasignarParsonal');
        select.crearSelect('#selectReasignarArea');
        select.crearSelect('#selectReasignarDepartamento');
        tabla.generaTablaPersonal('#data-table-servicio', null, null, null, true);

        //Tabla de solicitud de material de proyecto
        tabla.generaTablaPersonal('#data-table-materiales', null, null, null, true);

        $('#ocultarMostrarSD').on('click', function () {
            if ($('#contenidoSD').hasClass('hidden')) {
                $('#contenidoSD').removeClass('hidden');
                $('#ocultarMostrarSD span').empty().append('- Menos detalles');
            } else {
                $('#contenidoSD').addClass('hidden');
                $('#ocultarMostrarSD span').empty().append('+ Mostrar detalles');
            }
        });

        $("#selectCliente").on("change", function () {
            var creator = $("#creator-sd").val();
            var requester = $("#requester-sd").val();
            var sucursal_interna = $("#id-sucursal-interna").val();

            var _cliente = $(this).val();
            if (_cliente !== "") {
                evento.enviarEvento('/Proyectos2/Planeacion/SucursalesByCliente', {'id': _cliente}, '#modal-dialogo', function (respuesta) {
                    $("#selectSucursal").empty().append("<option value=''>Selecciona . . .</option>");
                    var _option = 'XX';
                    $.each(respuesta, function (k, v) {
                        if ((creator !== '' && requester !== '' && (v.NombreCinemex == creator || v.NombreCinemex == requester)) || (sucursal_interna !== '' && v.Id == sucursal_interna)) {
                            _option = v.Id;
                        }
                        $("#selectSucursal").append('<option value="' + v.Id + '">' + v.Nombre + '</option>');
                    });
                    select.cambiarOpcion("#selectSucursal", _option);
                    $("#selectSucursal").removeAttr("disabled");

                });
            } else {
                $("#selectSucursal").empty().append("<option value=''>Selecciona . . .</option>");
                $("#selectSucursal").attr("disabled", "disabled");
                select.cambiarOpcion("#selectSucursal", '');
            }
        });

        if (respuesta.datos.IdCliente !== null) {
            select.cambiarOpcion('#selectCliente', respuesta.datos.IdCliente);
        }

        //Evento para select para mostrar el campo selectClasificacion
        $('#selectServicioDepartamento').on('change', function () {
            if ($(this).val() !== '' && $(this).val() === '5') {
                $('#selectAtiendeServicio').val('42').trigger('change');
            } else {
                if ($('#selectAtiendeServicio').val() === '') {
                    $('#selectAtiendeServicio').val('').trigger('change');
                }
            }
        });

        //Evento elimina registro de la tabla servicio
        $('#data-table-servicio tbody').on('click', 'tr', function () {
            var tabla = $('#data-table-servicio').DataTable();
            tabla.row(this).remove().draw(false);
        });

        //Agrega el servicio en la tabla 
        $('#btnAgregarServicio').on('click', function () {
            if (evento.validarFormulario('#formAgregarSservicio')) {
                tabla.agregarFila('#data-table-servicio', [
                    $('#selectServicioDepartamento option:selected').text(),
                    $('#selectSucursal option:selected').text(),
                    $('#selectAtiendeServicio option:selected').text(),
                    $('#inputDescripcionServicio').val(),
                    $('#selectServicioDepartamento').val(),
                    $('#selectSucursal').val(),
                    $('#selectAtiendeServicio').val()
                ]);
                $('#formAgregarSservicio').parsley().reset();
//                select.cambiarOpcion("#selectServicioDepartamento");
            }
        });

        //Muestra la seccion de reasignar solicitud
        $('#btnReasignarSolicitud').on('click', function () {
            $('#seccionSeguimiento').addClass('hidden');
            $('#seccionReasignar').removeClass('hidden');
        });

        //Muestra cierra el modal
        $('#btnCancelarSeguimiento').on('click', function () {
            evento.cerrarModal();
        });

        //Genera el ticket y los servicios de la solicitud
        $('#btnConfirmarSeguimientoSolicitud').on('click', function () {
            var cliente = $('#selectCliente').val();
            if (cliente !== '') {
                var datosTabla = $('#data-table-servicio').DataTable().rows().data();
                var servicios = [];
                if (datosTabla.length > 0) {
                    for (var i = 0; i < datosTabla.length; i++) {
                        servicios.push({servicio: datosTabla[i][4], sucursal: datosTabla[i][5], atiende: datosTabla[i][6], descripcion: datosTabla[i][3], nombreServicio: datosTabla[i][0], nombreSucursal: datosTabla[i][1]});
                    }
                    var data = {solicitud: datos[0], ticket: datos[3], servicios: servicios, cliente: cliente, descripcion: descripcionSolicitud}
                    evento.enviarEvento('Solicitud/Generar_Ticket', data, '#modal-dialogo', function (respuesta) {
                        var fila = [];
                        if (typeof respuesta === 'object') {
                            if (respuesta.responce === true) {
                                var numeroTicket = respuesta.ticket;
//                              eventoCalendario(numeroTicket);
                                evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12 text-center">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>Se genero el ticket <strong>' + respuesta.ticket + '</strong>  con exito y el personal encargado de brindar \n\
                                                                           seguimiento al servicio ya fue notificado para su seguimiento. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                $('#data-table-solicitudes-asignadas').DataTable().row(this).remove().draw();
                            } else {
                                let htmlfolio = '';
                                if (respuesta.folios != null && respuesta.folios != 0) {
                                    htmlfolio = ' y el folio <strong>' + respuesta.folios + '</strong>'
                                }
                                let html = '<div class="row">\n\
                                                <div class="col-md-12 text-center">\n\
                                                    <div class="form-group">\n\
                                                        <p>Existe el Ticket <strong>' + respuesta.ticket + '</strong>' + htmlfolio + ' para esta solicitud</p>\n\
                                                        <p>¿Quieres generar otro?</p>\n\
                                                    </div>\n\
                                                </div>\n\
                                            </div>';
                                evento.cargaContenidoModal(html);
                                $('#btnModalConfirmar').removeClass('hidden').empty().append('Aceptar');
                                $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                $('#btnModalConfirmar').on('click', function () {
                                    $('#btnModalConfirmar').off('click');
                                    data['generaOtro'] = true;
                                    evento.enviarEvento('Solicitud/Generar_Ticket', data, '#modal-dialogo', function (respuesta) {
                                        if (typeof respuesta === 'object') {
                                            var numeroTicket = respuesta.ticket;
                                            evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12 text-center">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>Se genero el ticket <strong>' + respuesta.ticket + '</strong>  con exito y el personal encargado de brindar \n\
                                                                           seguimiento al servicio ya fue notificado para su seguimiento. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                            $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                            $('#data-table-solicitudes-asignadas').DataTable().row(this).remove().draw();
                                        } else {
                                            evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>No se pudo generar el ticket por favor de volver a intentarlo. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                                            $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                                        }
                                    });
                                });
                            }
                        } else {
                            evento.cargaContenidoModal('<div class="row">\n\
                                                                <div class="col-md-12">\n\
                                                                    <div class="form-group">\n\
                                                                        <p>No se pudo generar el ticket por favor de volver a intentarlo. </p>\n\
                                                                    </div>\n\
                                                                </div>\n\
                                                            </div>');
                            $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                        }
                    });
                } else {
                    evento.mostrarMensaje('#errorGenerarTicket', false, 'Debes definir almenos un servicio de la solicitud.', 3000);
                }
            } else {
                evento.mostrarMensaje('#errorGenerarTicket', false, 'Debes definir el cliente para el servicio.', 3000);
            }
        });

        //crear un evento en calendario
//        var eventoCalendario = function () {
//            var ticketCalendario = arguments[0];
//            var atiende = $('#selectAtiendeServicio option:selected').text();
//            var tipoServicio = $('#selectServicioDepartamento option:selected').text();
//            var sucursal = $('#selectSucursal option:selected').text();
//            var emailCorporativo = $('#selectAtiendeServicio option:selected').attr('data');
//            var descripcion = $('#inputDescripcionServicio').val();
//            var now = new Date();
//            today = now.toISOString();
//
//            resource = {
//                "summary": "Atención a ticket",
//                "description": "Nuevo servicio. Se a agregado para su atención del ticket " + ticketCalendario + " con la siguiente descripcion " + descripcion,
//                "location": sucursal,
//                "timeZone": "America/Mexico_City",
//                "start": {
//                    "dateTime": now
//                },
//                "end": {
//                    "dateTime": now
//                },
//                "attendees": [
//                    {
//                        "email": emailCorporativo,
//                        "displayName": atiende,
//                        "organizer": false,
//                        "self": false,
//                        "resource": false,
//                        "optional": false,
//                        "responseStatus": "accepted"
//                    }],
//                "colorId": "4"
//
//            };
//            handleClientLoad(resource, true);
//        };

        //Evento de select personal para seleccionar su area y departamento
        $('#selectReasignarParsonal').on('change', function () {
            var perfil = $(this).val();
            var dataPerfil = {perfil: perfil};
            evento.enviarEvento('Solicitud/BuscarAreaDepartamento', dataPerfil, '#seccionSeguimiento', function (respuesta) {
                if (respuesta) {
                    select.cambiarOpcion('#selectReasignarArea', respuesta[0].Area);
                    select.cambiarOpcion('#selectReasignarDepartamento', respuesta[0].Departamento);
                }
            });
        });

        //Cambia las opciones de select departamento apartir del area
        $('#selectReasignarArea').on('change', function () {
            $('#selectReasignarDepartamento').removeAttr('disabled');
            select.setOpcionesSelect('#selectReasignarDepartamento', respuesta.departamentos, $('#selectReasignarArea').val(), 'IdArea');
        });

        //Muestra la seccion de seguimiento
        $('#btnCancelarReasignar').on('click', function () {
            $('#seccionSeguimiento').removeClass('hidden');
            $('#seccionReasignar').addClass('hidden');
        });

        //Se confirma la reasignacion de la solicitud
        $('#btnConfirmarReasignar').on('click', function () {
            if (evento.validarFormulario('#formReasignarSolicitud')) {
                var data = {solicitud: datos[0], operacion: '5', departamento: $('#selectReasignarDepartamento').val(), descripcion: $('#textareaDescripcionReasignacion').val()};
                evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                    var fila = [];
                    if (typeof respuesta === 'object') {
                        evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    <div class="form-group">\n\
                                        <p>Se a reasignado con exito la solicitud. <br>Se le notifico al departamento asignado para que le brinde el seguimiento. </p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                        tabla.limpiarTabla('#data-table-solicitudes-asignadas');
                        if (respuesta.solicitudes.solicitudes.length > 0) {
                            $.each(respuesta.solicitudes.solicitudes, function (indice, item) {
                                fila = [];
                                fila.push(item.Numero);
                                fila.push(item.Asunto);
                                fila.push(item.Tipo);
                                fila.push(item.Ticket);
                                if (respuesta.solicitudes.SolicitudesSD.length > 0) {
                                    $.each(respuesta.solicitudes.SolicitudesSD, function (key, value) {
                                        if (value.solicitud === item.Numero) {
                                            fila.push(value.datos.Solicitante);
                                            fila.push(value.datos.Asunto);
                                        } else {
                                            fila.push(item.Solicita);
                                            fila.push('');
                                        }
                                    });
                                } else {
                                    fila.push(item.Solicita);
                                    fila.push('');
                                }
                                fila.push(item.Fecha);
                                fila.push(item.Estatus);
                                tabla.agregarFila('#data-table-solicitudes-asignadas', fila);
                            });
                        }
                    } else {
                        evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12">\n\
                                    <div class="form-group">\n\
                                        <p>No se pudo reasignar la solicitud por favor de volver a intentarlo. </p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                    }
                });
            }
        });

        //Muestra la seccion de solicitud rechazada
        $('#btnRechazarSolicitud').on('click', function () {
            if (datos[2] === 'SERVICE DESK') {
                var data = {solicitud: datos[0], operacion: '3'};
                evento.enviarEvento('Solicitud/Datos_SistemaExterno', data, '#modal-dialogo', function (respuesta) {
                    var data = [];
                    $.each(respuesta.tecnicosSD.operation.details, function (key, value) {
                        data.push({id: value.TECHNICIANID, text: value.TECHNICIANNAME});
                    })
                    select.crearSelect('#selectTecnicosSD');
                    select.cargaDatos('#selectTecnicosSD', data);
                    $('#seccionSeguimiento').addClass('hidden');
                    $('#seccionRechazar').removeClass('hidden');
                });
            } else {
                $('#seccionSeguimiento').addClass('hidden');
                $('#seccionRechazar').removeClass('hidden');
            }
        });

        //Muestra la seccion de seguimiento cuando se cancela el rechazo
        $('#btnCancelarRechazo').on('click', function () {
            $('#seccionSeguimiento').removeClass('hidden');
            $('#seccionRechazar').addClass('hidden');
        });

        //Se confirma el rechazo de la solicitud
        $('#btnConfirmarRechazo').on('click', function () {
            if (evento.validarFormulario('#formRechazarSolicitud')) {
                if (datos[2] === 'SERVICE DESK') {
                    var data = {solicitud: datos[0], operacion: '4', tecnicoSD: $('#selectTecnicosSD').val(), descripcion: $('#textareaDescripcionRechazada').val()};
                    var mensajeExito = 'Se a reasignado el folio con exito.';
                    var mensajeError = 'No se pudo reasignar el folio Service Desk por favor de volver a intentarlo.';
                } else {
                    var data = {solicitud: datos[0], operacion: '4', descripcion: $('#textareaDescripcionRechazada').val()};
                    var mensajeExito = 'Se a rechazado con exito la solicitud. <br>Se le notifico al solicitante del por que no sea brindado el seguimiento.';
                    var mensajeError = 'No se pudo generar el rechazo de la solicitud por favor de volver a intentarlo.';
                }
                evento.enviarEvento('Solicitud/Solicitud_Actualizar', data, '#modal-dialogo', function (respuesta) {
                    var fila = [];
                    if (typeof respuesta === 'object') {
                        evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12 text-center">\n\
                                    <div class="form-group">\n\
                                        <p>' + mensajeExito + '</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                        tabla.limpiarTabla('#data-table-solicitudes-asignadas');
                        if (respuesta.solicitudes.solicitudes.length > 0) {
                            $.each(respuesta.solicitudes.solicitudes, function (indice, item) {
                                fila = [];
                                fila.push(item.Numero);
                                fila.push(item.Asunto);
                                fila.push(item.Tipo);
                                fila.push(item.Ticket);
                                fila.push(item.Sucursal);
                                if (respuesta.solicitudes.SolicitudesSD.length > 0) {
                                    $.each(respuesta.solicitudes.SolicitudesSD, function (key, value) {
                                        if (value.solicitud === item.Numero) {
                                            fila.push(value.datos.Solicitante);
                                            fila.push(value.datos.Asunto);
                                        } else {
                                            fila.push(item.Solicita);
                                            fila.push('');
                                        }
                                    });
                                } else {
                                    fila.push(item.Solicita);
                                    fila.push('');
                                }
                                fila.push(item.Fecha);
                                fila.push(item.Estatus);
                                tabla.agregarFila('#data-table-solicitudes-asignadas', fila);
                            });
                        }
                    } else {
                        evento.cargaContenidoModal('<div class="row">\n\
                                <div class="col-md-12">\n\
                                    <div class="form-group">\n\
                                        <p>' + mensajeError + '</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>');
                        $('#btnModalAbortar').removeClass('hidden').empty().append('Cerrar');
                    }
                });
            }
        });

        if (respuesta.autorizacionAtenderServicio) {
            $('#btnAtenderSolicitud').removeClass('hidden');
            $('#btnAtenderSolicitud').on('click', function () {
                var data = {solicitud: datos[0]};
                evento.enviarEvento('Solicitud/Atencion_Solcitud', data, '#modal-dialogo', function (respuesta) {
                    if (respuesta) {
                        evento.mensajeConfirmacion('Solicitud atendida', 'Correcto');
                    } else {
                        evento.mensajeConfirmacion('Lo sentimos se produjo un error favor de contactar al área correspondiente', 'Error');
                    }
                });
            });
        }
    }
});