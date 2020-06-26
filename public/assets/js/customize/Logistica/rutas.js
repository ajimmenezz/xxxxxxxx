$(function () {
    //Objetos
    var evento = new Base();
    var websocket = new Socket();
    var calendario = new Fecha();
    var tabla = new Tabla();
    var select = new Select();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Creando tabla de areas
    tabla.generaTablaPersonal('#data-table-rutas', null, null, true, true, [[0, 'desc']]);

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    //Inicializa funciones de la plantilla
    App.init();

    //Evento que carga formilaio para nueva ruta
    $('#btnAgregarRuta').on('click', function () {
        evento.enviarEvento('EventoRutas/MostrarFormularioRutas', '', '#seccionRutas', function (respuesta) {
            $('#listaRutas').addClass('hidden');
            $('#formularioRuta').removeClass('hidden').empty().append(respuesta.formulario);
            calendario.crearFecha('.calendario');
            select.crearSelect('#selectChoferRutas');
            //Evento que genera un nueva Ruta
            $('#btnNuevaRuta').on('click', function () {
                var fecha = $('#inputFechaRutas').val();
                var chofer = $('#selectChoferRutas').val();
                var data = {fecha: fecha, chofer: chofer};
                if (evento.validarFormulario('#formNuevaRuta')) {
                    if ($('#inputFechaRutas').val() != '') {
                        evento.enviarEvento('EventoRutas/NuevaRuta', data, '#seccionRutas', function (respuesta) {
                            if (respuesta instanceof Array) {
                                tabla.limpiarTabla('#data-table-rutas');
                                $.each(respuesta, function (key, valor) {
                                    tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                                });
                                evento.limpiarFormulario('#formNuevaRuta');
                                $('#formularioRuta').addClass('hidden');
                                $('#listaRutas').removeClass('hidden');
                                evento.mostrarMensaje('.errorListaRutas', true, 'Datos insertados correctamente', 3000);
                            } else {
                                evento.mostrarMensaje('.errorRuta', false, 'No se pudo generar la ruta vuelva a intentarlo', 3000);
                            }
                        });
                    } else {
                        evento.mostrarMensaje('.errorRuta', false, 'Debes llenar el campo Fecha de Ingreso', 3000);
                    }
                }
            });
            $('#btnCancelarRuta').on('click', function () {
                $('#formularioRuta').empty().addClass('hidden');
                $('#listaRutas').removeClass('hidden');
            });
        });
    });
    //Evento que carga el formulario para actualizar la ruta
    $('#data-table-rutas tbody').on('click', 'tr', function () {
        $('#btnRegresarActualizarRuta').removeClass('hidden');
        var datos = $('#data-table-rutas').DataTable().row(this).data();
        var data = {Ruta: datos[0]};
        evento.enviarEvento('EventoRutas/MostrarFormularioRutas', data, '#seccionRutas', function (respuesta) {
            $('#listaRutas').addClass('hidden');
            $('#formularioRuta').removeClass('hidden').empty().append(respuesta.formulario);
            if (datos[4] != 'En tránsito') {
                $('#empezarRuta').removeClass('hidden');
            }
            $('#datosActualizar').removeClass('hidden');
            $('#fechaNueva').addClass('hidden');
            $('#botonesNuevaRuta').addClass('hidden');
            $('#botonesActualizarRuta').removeClass('hidden');
            calendario.crearFecha('.calendario');
            select.crearSelect('#selectChoferRutas');
            var idChofer = respuesta.datos.idChofer[0].IdUsuarioAsignado;
            $('#selectChoferRutas').val(idChofer).trigger('change');
            $('#textCodigoRuta').html('<h5><strong>' + datos[1] + '</strong></h5>');
            $('#textFechaRuta').html('<h5><strong>' + datos[2] + '</strong></h5>');
            //Evento que actualiza Ruta
            $('#btnActualizarRuta').on('click', function () {
                var chofer = $('#selectChoferRutas').val();
                var data = {id: datos[0], chofer: chofer};
                if (evento.validarFormulario('#formNuevaRuta')) {
                    evento.enviarEvento('EventoRutas/ActualizarRuta', data, '#seccionRutas', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-rutas');
                            $.each(respuesta, function (key, valor) {
                                tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                            });
                            evento.limpiarFormulario('#formNuevaRuta');
                            $('#empezarRuta').addClass('hidden');
                            $('#formularioRuta').addClass('hidden');
                            $('#listaRutas').removeClass('hidden');
                            evento.mostrarMensaje('.errorListaRutas', true, 'Datos actualizados correctamente', 3000);
                        } else {
                            evento.cerrarModal();
                            evento.mostrarMensaje('.errorRuta', false, 'No se pudo actulizar la ruta vuelva a intentarlo', 3000);
                        }
                    });
                }
            });
            //Evento que cancela Ruta
            $('#btnCancelarActualizarRuta').on('click', function () {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>"¿Realmente cancelar la Ruta?"</h3>\n\
                            </div>\n\
                      </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluir" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    <button id="btnCancelarConcluir" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('"Advertencia"', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');
                $('#btnAceptarConcluir').on('click', function () {
                    var data = {id: datos[0], cancelacion: '6'};
                    evento.enviarEvento('EventoRutas/CancelarRuta', data, '#seccionRutas', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-rutas');
                            $.each(respuesta, function (key, valor) {
                                tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                            });
                            $('#empezarRuta').addClass('hidden');
                            $('#formularioRuta').addClass('hidden');
                            $('#listaRutas').removeClass('hidden');
                            $('#modal-dialogo').modal('hide');
                            evento.mostrarMensaje('.errorListaRutas', true, 'Datos actualizados correctamente', 3000);
                        } else {
                            evento.cerrarModal();
                            evento.mostrarMensaje('.errorRuta', false, 'La Ruta no puede cancelar, verifique servicios deben estar cancelados o concluidos', 6000);
                        }
                    });
                });
                //Envento para no concluir con la cancelacion
                $('#btnCancelarConcluir').on('click', function () {
                    evento.cerrarModal();
                });
            });

            //Evento para regrear al resumen de Rutas
            $('#btnRegresarActualizarRuta').on('click', function () {
                $('#formularioRuta').empty().addClass('hidden');
                $('#btnRegresarActualizarRuta').addClass('hidden');
                $('#listaRutas').removeClass('hidden');
            });
            
            //Evento para empezar la Ruta
            $('#btnEmpezarRuta').on('click', function () {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>"¿Realmente quieres empezar la Ruta?"</h3>\n\
                            </div>\n\
                      </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluir" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"> Aceptar</i></button>\n\
                                    <button id="btnCancelarConcluir" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"> Cerrar</i></button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('"Advertencia"', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');
                $('#btnAceptarConcluir').on('click', function () {
                    evento.enviarEvento('EventoRutas/EmpezarRuta', data, '#seccionRutas', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-rutas');
                            $.each(respuesta, function (key, valor) {
                                tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                            });
                            $('#empezarRuta').addClass('hidden');
                            $('#formularioRuta').addClass('hidden');
                            $('#listaRutas').removeClass('hidden');
                            $('#modal-dialogo').modal('hide');
                            evento.mostrarMensaje('.errorListaRutas', true, 'La ruta esta en Tránsito correctamente', 3000);
                        } else if (respuesta === 'faltaServicio') {
                            evento.cerrarModal();
                            evento.mostrarMensaje('.errorRuta', false, 'No se pudo empezar el tránsito de la ruta "Debe tener al menos un servicio seleccionado "', 5000);
                        } else {
                            evento.cerrarModal();
                            evento.mostrarMensaje('.errorRuta', false, 'No se pudo empezar el tránsito de la ruta', 3000);
                        }
                    });
                });
                //Envento para no concluir con la cancelacion
                $('#btnCancelarConcluir').on('click', function () {
                    evento.cerrarModal();
                });
            });
            //Evento para empezar la Ruta
            $('#btnConcliurRuta').on('click', function () {
                var html = '<div class="row">\n\
                            <div id="mensaje-modal" class="col-md-12 text-center">\n\
                                <h3>"¿Realmente quieres concluir la Ruta?"</h3>\n\
                            </div>\n\
                      </div>';
                html += '<div class="row m-t-20">\n\
                                <div class="col-md-12 text-center">\n\
                                    <button id="btnAceptarConcluir" type="button" class="btn btn-sm btn-success">Aceptar</button>\n\
                                    <button id="btnCancelarConcluir" type="button" class="btn btn-sm btn-danger">Cerrar</button>\n\
                                </div>\n\
                            </div>';
                $('#btnModalConfirmar').addClass('hidden');
                $('#btnModalAbortar').addClass('hidden');
                evento.mostrarModal('"Advertencia"', html);
                $('#btnModalConfirmar').empty().append('Eliminar');
                $('#btnModalConfirmar').off('click');
                $('#btnAceptarConcluir').on('click', function () {
                    evento.enviarEvento('EventoRutas/ConcluirRuta', data, '#seccionRutas', function (respuesta) {
                        if (respuesta instanceof Array) {
                            tabla.limpiarTabla('#data-table-rutas');
                            $.each(respuesta, function (key, valor) {
                                tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                            });
                            $('#empezarRuta').addClass('hidden');
                            $('#formularioRuta').addClass('hidden');
                            $('#listaRutas').removeClass('hidden');
                            $('#modal-dialogo').modal('hide');
                            evento.mostrarMensaje('.errorListaRutas', true, 'Se concluyo la ruta correctamente', 3000);
                        } else {
                            evento.cerrarModal();
                            evento.mostrarMensaje('.errorRuta', false, 'La Ruta no puede concluir verifique servicios deben estar cancelados o concluidos, y la ruta debe estar en seguimiento', 8000);
                        }
                    });
                });
                //Envento para no concluir con la cancelacion
                $('#btnCancelarConcluir').on('click', function () {
                    evento.cerrarModal();
                });
            });
        });
    });
    //Evento para buscar las rutas dependiendo los campos desde y hasta
    $('#btnBuscarRuta').on('click', function () {
        var desde = $('#inputDesdeRutas').val();
        var hasta = $('#inputHastaRutas').val();
        var data = {desde: desde, hasta: hasta};
        if ($('#inputDesdeRutas').val() != '') {
            if ($('#inputHastaRutas').val() != '') {
                evento.enviarEvento('EventoRutas/BuscarRuta', data, '#seccionRutas', function (respuesta) {
                    if (respuesta instanceof Array) {
                        tabla.limpiarTabla('#data-table-rutas');
                        $.each(respuesta, function (key, valor) {
                            tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                        });
                        if (respuesta == '') {
                            evento.mostrarMensaje('.errorListaRutas', false, 'No hay datos encontrados', 3000);
                        } else {
                            evento.mostrarMensaje('.errorListaRutas', true, 'Datos encontrados correctamente', 3000)
                        }

                    } else {
                        evento.mostrarMensaje('.errorListaRutas', false, 'No se pudo generar la ruta vuelva a intentarlo', 3000);
                    }
                });
            } else {
                evento.mostrarMensaje('.errorListaRutas', false, 'Debes llenar el campo Hasta', 3000);
            }
        } else {
            evento.mostrarMensaje('.errorListaRutas', false, 'Debes llenar el campo Desde', 3000);
        }
    });
    //Evento para cargar de nuevo toda la lista de la rutas
    $('#btnMostrarRutas').on('click', function () {
        evento.enviarEvento('EventoRutas/BuscarRuta', '', '#seccionRutas', function (respuesta) {
            if (respuesta instanceof Array) {
                tabla.limpiarTabla('#data-table-rutas');
                $.each(respuesta, function (key, valor) {
                    tabla.agregarFila('#data-table-rutas', [valor.Id, valor.Codigo, valor.FechaRuta, valor.Nombre + ' ' + valor.ApPaterno, valor.Estatus]);
                });
                evento.limpiarFormulario('#formBuscarRutas');
                evento.mostrarMensaje('.errorListaRutas', true, 'Datos mostrados correctamente', 3000)
            } else {
                evento.mostrarMensaje('.errorListaRutas', false, 'No se pudo generar la ruta vuelva a intentarlo', 3000);
            }
        });
    });
});


