$(function () {

    var evento = new Base();
    var select = new Select();
    var file = new Upload();
    this.calendario = new Fecha();
    var tabla = new Tabla();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    tabla.generaTablaPersonal('#data-table-autorizar-permisos-ausencia', null, null, true, true);

    //Inicializa funciones de la plantilla
    App.init();

    $('.input-daterange').datepicker({
        autoclose: true,
        language: 'es',
        endDate: '0d',
        format: 'yyyy-mm-dd'
    });

    $('#data-table-autorizar-permisos-ausencia tbody').on('click', 'tr', function () {
        var perfilUsuario = $('#idPerfil').val();
        var idUsuario = $('#idUsuarioRev').val();
        var informacionPermisoAusencia = $('#data-table-autorizar-permisos-ausencia').DataTable().row(this).data();
        var datosPermiso = {
            idPermiso: informacionPermisoAusencia[0],
            estatus: informacionPermisoAusencia[8],
            perfilUsuario: perfilUsuario
        }
        evento.enviarEvento('EventoPermisosVacaciones/Autorizar', datosPermiso, '#panelAutorizarPermisos', function (respuesta) {
            if (respuesta) {
                $('#contentRevisarPermiso').removeClass('hidden').empty().append(respuesta.formulario);
                $('#contentPermisosPendientes').addClass('hidden');
                if (respuesta.consulta.datosAusencia['0'].IdUsuarioJefe !== null) {
                    $('.ocultarPermiso').addClass('hidden');
                    if (respuesta.consulta.datosAusencia['0'].IdUsuarioRH == null && perfilUsuario == '21') {
                        $('.ocultarPermiso').removeClass('hidden');
                    } else {
                        if (respuesta.consulta.datosAusencia['0'].IdUsuarioContabilidad == null && perfilUsuario == '37') {
                            $('.ocultarPermiso').removeClass('hidden');
                        }
                    }
                }
                if (datosPermiso.estatus === 'Cancelado') {
                    $('.ocultarPermiso').addClass('hidden');
                }
                let hoy = moment().format('YYYY-MM-DD');
                if (respuesta.consulta.datosAusencia['0'].Cancelacion == 1 && hoy <= respuesta.consulta.datosAusencia['0'].FechaAusenciaDesde) {
                    $('#btnPeticionCancelar').removeClass('hidden');
                }
                $('#btnCancelarRevisarPermiso').on('click', function () {
                    $('#contentRevisarPermiso').empty().addClass('hidden');
                    $('#contentPermisosPendientes').removeClass('hidden');
                });
                var data = {
                    idPermiso: $('#idPermisoRevisar').val(),
                    idPerfil: perfilUsuario,
                    idUser: idUsuario,
                    archivo: $('#archivoPDF').val()
                }

                $("#btnVerPDFAutorizar").on("click", function () {
                    window.open('/storage/Archivos/' + $('#archivoPDF').val(), '_blank');
                });
                $("#btnCancelarPermiso").on("click", function () {
                    $('#btnAceptarRechazo').on('click', function () {
                        if ($('#motivoRechazo').val() !== "") {
                            data[0] = {
                                motivoRechazo: $('#motivoRechazo').val(),
                                textoRechazo: $(`#motivoRechazo option:selected`).text()
                            };
                            evento.enviarEvento('EventoPermisosVacaciones/CancelarPermisos', data, '#modalRechazo', function (respuesta) {
                                if (respuesta) {
                                    location.reload();
                                } else {
                                    evento.mostrarMensaje('.mensajeSolicitudPermisosRevisar', false, 'Hubo un problema con la cancelación del permiso.', 3000);
                                }
                            });
                        } else {
                            evento.mostrarMensaje('.mensajeCancelarPermiso', false, 'Falta motivo de rechazo', 3000);
                        }
                    });
                });
                $("#btnAutorizarPermiso").on("click", function () {
                    evento.enviarEvento('EventoPermisosVacaciones/AutorizarPermiso', data, '#panelRevisarPermisos', function (respuesta) {
                        if (respuesta) {
                            location.reload();
                        } else {
                            evento.mostrarMensaje('.mensajeSolicitudPermisosRevisar', false, 'Hubo un problema con la autorización del permiso.', 3000);
                        }
                    });
                });
                $("#btnConluirAutorizacion").on("click", function () {
                    evento.enviarEvento('EventoPermisosVacaciones/ConluirAutorizacion', data, '#panelRevisarPermisos', function (respuesta) {
                        if (respuesta) {
                            location.reload();
                        } else {
                            evento.mostrarMensaje('.mensajeSolicitudPermisosRevisar', false, 'Hubo un problema con la autorización del permiso.', 3000);
                        }
                    });
                });
                $('#btnAceptarCancelarPeticion').on('click', function () {
                    if (evento.validarFormulario('#motivoSolicitudCancelacion')) {
                        var data = {
                            idPermiso: $('#idPermisoRevisar').val(),
                            idUserRev: idUsuario,
                            motivoCancelacion: $('#motivoCancelarPermiso option:selected').text(),
                            idMotivoCancelacion: $('#motivoCancelarPermiso').val(),
                            nombreUsuario: $('#inputNombreRevisar').val(),
                            fechaAusencia: $('#inputFechaPermisoDesdeRevisar').val(),
                            MotivoAusencia: $('#inputMotivoAusencia').val()
                        }
                        evento.enviarEvento('EventoPermisosVacaciones/cancelarPermisoAutorizado', data, '#modal-dialogo', function (respuesta) {
                            if (respuesta) {
                                location.reload();
                            } else {
                                evento.mostrarMensaje('.mensajeCancelarAutorizacion', false, 'Hubo un problema con el servidor, intenta mas tarde.', 3000);
                            }
                        });
                    }
                });
            } else {
                evento.mostrarMensaje('.mensajeAutorizarPermisos', false, 'Hubo un problema con la solicitud.', 3000);
            }
        });
    });

    $('#btnExcel').on('click', function () {
        if (evento.validarFormulario('#fechasReportePermisos')) {
            var filtroFechas = {
                comienzo: $('#comienzo').val(),
                fin: $('#fin').val()
            }
            evento.enviarEvento('EventoPermisosVacaciones/exportarExcel', filtroFechas, '#modal-dialogo', function (respuesta) {
                $('#modalExportar').modal('hide');
                if (respuesta !== false) {
                    window.open(respuesta.ruta, '_blank');
                } else {
                    evento.mostrarMensaje('.mensajeErrorExcel', false, 'No existen registros para este periodo.', 3000);
                }
            });
        }
    });
});
