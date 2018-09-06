$(function () {

    var evento = new Base();
    var websocket = new Socket();
    var tabla = new Tabla();
    var servicios = new Servicio();
    var select = new Select();
    var botones = new Botones();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de rh
    tabla.generaTablaPersonal('#data-table-rh', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga la seccion de seguimiento de un servicio de tipo Logistica
    $('#data-table-rh tbody').on('click', 'tr', function () {
        var datos = $('#data-table-rh').DataTable().row(this).data();
        if (datos !== undefined) {
            var servicio = datos[0];
            var operacion = datos[6];
            if (operacion === '1') {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>¿Quieres atender el servicio?</h3>\n\
                            </div>\n\
                      </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnIniciarServicio" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarIniciarServicio" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('Iniciar Servicio', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');

                $('#btnIniciarServicio').on('click', function () {
                    var data = {servicio: servicio, operacion: '1'};
                    evento.enviarEvento('Seguimiento/Servicio_Datos', data, '#modal-dialogo', function (respuesta) {
                        evento.cerrarModal();
                        data = {servicio: servicio, operacion: '2'};
                        cargarFormularioSeguimiento(data, datos);
                        recargandoTablaRH(respuesta.informacion);
                    });
                });
                //Envento para concluir con la cancelacion
                $('#btnCancelarIniciarServicio').on('click', function () {
                    evento.cerrarModal();
                });

            } else if (operacion === '2' || operacion === '12' || operacion === '10') {
                var data = {servicio: servicio, operacion: '2'};
                cargarFormularioSeguimiento(data, datos);
            }
        }
    });

    var cargarFormularioSeguimiento = function () {

        var data = arguments[0];
        var datosTabla = arguments[1];
        var panel = arguments[2];

        evento.enviarEvento('Seguimiento/Servicio_Datos', data, panel, function (respuesta) {
            var datosDelServicio = respuesta.datosServicio;
            var formulario = respuesta.formulario;
            var archivo = respuesta.archivo;
            var avanceServicio = respuesta.avanceServicio;
            var idSucursal = respuesta.idSucursal[0].IdSucursal;
            var datosSD = respuesta.datosSD;

            if (datosDelServicio.tieneSeguimiento === '0') {
                servicios.ServicioSinClasificar(
                        formulario,
                        '#listaServicio',
                        '#seccionSeguimientoServicio',
                        datosTabla[0],
                        datosDelServicio,
                        'Seguimiento',
                        archivo,
                        '#panelSeguimientoRH',
                        datosTabla[1],
                        avanceServicio,
                        idSucursal,
                        datosSD
                        );
            } else {
                if (respuesta) {
                    mostrarSeccionSeguimientoServicio(respuesta);
                    botones.iniciarBotonesGenerales(datosTabla[0], datosDelServicio);
                }
                eventosSeguimientoServicio(respuesta, datosTabla[0]);
            }
        });
    };

    var recargandoTablaRH = function (informacionServicio) {
        tabla.limpiarTabla('#data-table-rh');
        $.each(informacionServicio.serviciosAsignados, function (key, item) {
            tabla.agregarFila('#data-table-rh', [item.Id, item.Ticket, item.Servicio, item.FechaCreacion, item.Descripcion, item.NombreEstatus, item.IdEstatus, item.Folio]);
        });
    };

    //Encargado de mostrar la seccion formulario para el seguimiento del servicio de personal de un proyecto
    var mostrarSeccionSeguimientoServicio = function () {
        var respuesta = arguments[0];

        $('#listaServicio').addClass('hidden');
        $('#seccionServiciosProcesoRH').addClass('hidden');
        $('#seccionSeguimientoServicio').removeClass('hidden').empty().append(respuesta.formulario);
        tabla.generaTablaPersonal('#data-table-eventuales-proyecto', null, null, true);
        select.crearSelect('#selectAsistentesProyecto');
    };

    //Carga los eventos de los botones para el seguimiento del servicio de personal de un proyecto
    var eventosSeguimientoServicio = function () {
        var respuesta = arguments[0];
        var servicio = arguments[1];
        var html = null, titulo = null;

        //Evento para agregar asistentes a la tabla
        $('#btnAgregaAsistente').on('click', function () {
            var idAsistente = $('#selectAsistentesProyecto').val();
            if (idAsistente !== '') {
                var nombreAsistente = $('#selectAsistentesProyecto option:selected').text();
                var datosAsistente = [];
                var filas = $('#data-table-rh').DataTable().rows().data();
                var repetidoAsistente = false;
                if (filas.length > 0) {
                    for (var i = 0; i < filas.length; i++) {
                        if (filas[i][0] === idAsistente) {
                            repetidoAsistente = true;
                        }
                    }
                }
                if (!repetidoAsistente) {
                    var data = {servicio: servicio, operacion: '3', proyecto: respuesta.informacion.datosProyecto.IdProyecto, usuario: idAsistente};
                    evento.enviarEvento('Seguimiento/Actualizar_Servicio', data, '#seccion-datos-seguimiento', function (respuesta) {
                        if (respuesta.informacion) {
                            datosAsistente.push(idAsistente);
                            datosAsistente.push(nombreAsistente);
                            $.merge(datosAsistente, $('#selectAsistentesProyecto option:selected').attr('data-datos').split(','));
                            tabla.agregarFila('#data-table-eventuales-proyecto', datosAsistente);
                            select.cambiarOpcion('#selectAsistentesProyecto', '');
                            html = '<div class="row">\n\
                                    <div class="col-md-12 text-center">\n\
                                        Se agrego con exito al asistente al proyecto.\n\
                                    </div>\n\
                                </div>';
                        } else {
                            html = '<div class="row">\n\
                                    <div class="col-md-12">\n\
                                        No se pudo agregar el asistente al proyecto, por favor vuelva intentarlo.\n\
                                    </div>\n\
                                </div>';
                        }
                        html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnMensaje" type="button" class="btn btn-sm btn-default">Cerrar</button>\n\
                                </div>\n\
                            </div>';
                        titulo = 'Agregar Eventual';
                        modalAsistente(html, titulo);
                    });
                } else {
                    evento.mostrarMensaje('#errorAgregarAsistente', false, 'No puede repetir el asistente', 3000);
                }

            } else {
                evento.mostrarMensaje('#errorAgregarAsistente', false, 'No has seleccionado ningun asistente.', 3000);
            }
        });

        //Evento que permite eliminar un elemento de la tabla de asistentes
        $('#data-table-eventuales-proyecto tbody').on('click', 'tr', function () {
            var datos = $('#data-table-eventuales-proyecto').DataTable().row(this).data();
            var fila = this;
            var data = {servicio: servicio, operacion: '4', proyecto: respuesta.informacion.datosProyecto.IdProyecto, usuario: datos[0]};

            evento.enviarEvento('Seguimiento/Actualizar_Servicio', data, '#seccion-datos-seguimiento', function (respuesta) {

                if (respuesta.informacion === true) {
                    tabla.eliminarFila('#data-table-eventuales-proyecto', fila);
                    html = '<div class="row">\n\
                                    <div class="col-md-12 text-center">\n\
                                        Se elimino con exito el eventual del proyecto.\n\
                                    </div>\n\
                                </div>';
                } else if (respuesta.informacion === 'AsignadoTarea') {
                    html = '<div class="row">\n\
                                    <div class="col-md-12">\n\
                                        No se pudo eliminar lo eventual del proyecto ya que ya fue asignado a una tarea del proyecto.<br>\n\
                                        Por favor de validar con el lider de proyecto para que lo quite de la tarea donde esta asignado.\n\
                                    </div>\n\
                                </div>';
                } else {
                    html = '<div class="row">\n\
                                    <div class="col-md-12">\n\
                                        No se pudo eliminar el asistente del proyecto. Por favor vuelva intentarlo.\n\
                                    </div>\n\
                                </div>';

                }
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnMensaje" type="button" class="btn btn-sm btn-default">Cerrar</button>\n\
                                </div>\n\
                            </div>';
                titulo = 'Eliminar Eventual';
                modalAsistente(html, titulo);
            });

        });

        //Evento para concluir servicio de personal de proyecto
        $('#btnConcluirServicio').on('click', function () {
            var datosTabla = $('#data-table-eventuales-proyecto').DataTable().rows().data();

            if (datosTabla.length > 0) {
                html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                ¿ Estas seguro que quieres concluir el servicio ?\n\
                            </div>\n\
                      </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluir" type="button" class="btn btn-sm btn-success">Aceptar</button>\n\
                                    <button id="btnCancelarConcluir" type="button" class="btn btn-sm btn-danger">Cerrar</button>\n\
                                </div>\n\
                            </div>';
                evento.mostrarModal('Concluir Servicio', html);
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');

                //Envento para concluir el servicio
                $('#btnAceptarConcluir').on('click', function () {
                    var data = {servicio: servicio, operacion: '5'};
                    evento.enviarEvento('Seguimiento/Concluir_Servicio', data, '#modal-dialogo', function (respuesta) {
                        if (respuesta.informacion) {
                            $('#mensaje-modal').empty().append('Se concluyo con exito el servicio.');
                            $('#btnAceptarConcluir').addClass('hidden');
                            tabla.limpiarTabla('#data-table-sevicios-asignados');
                            $.each(respuesta.informacion, function (key, value) {
                                if (value.IdEstatus === '1') {
                                    tabla.agregarFila('#data-table-sevicios-asignados', [value.Id, value.Ticket, value.Sucursal, value.Servicio, value.FechaCreacion]);
                                }
                            });
                            tabla.limpiarTabla('#data-table-sevicios-enproceso');
                            $.each(respuesta.informacion, function (key, value) {
                                if (value.IdEstatus === '2') {
                                    tabla.agregarFila('#data-table-sevicios-enproceso', [value.Id, value.Ticket, value.Sucursal, value.Servicio, value.FechaCreacion]);
                                }
                            });
                            $('#btnRegresarServicios').trigger('click');
                        } else {
                            $('#mensaje-modal').empty().append('No se pudo concluir el servicio. Favor de volver a intentarlo.');
                            $('#btnAceptarConcluir').addClass('hidden');
                        }
                    });
                });

                //Envento para no concluir el servicio
                $('#btnCancelarConcluir').on('click', function () {
                    evento.cerrarModal();
                });
            } else {
                evento.mostrarMensaje('#errorServicio', false, 'No puedes concluir el servicio si no tienes definido al menos un eventual.', 3000);
            }
        });
    };

    //Evento para cerrar modal de alta y baja de asistente
    var modalAsistente = function () {
        var html = arguments[0];
        var titulo = arguments[1];

        evento.mostrarModal(titulo, html);
        $('#btnModalConfirmar').addClass('hidden');
        $('#btnModalAbortar').addClass('hidden');

        //Evento para cerrar el modal del mensaje
        $('#btnMensaje').on('click', function () {
            evento.cerrarModal();
        });
    }

});

