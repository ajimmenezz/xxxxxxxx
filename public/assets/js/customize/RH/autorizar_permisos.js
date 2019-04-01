$(function () {
    
    var evento = new Base();
    var websocket = new Socket();
    var select = new Select();
    var file = new Upload();
    this.calendario = new Fecha();
    var tabla = new Tabla();

    //Evento que maneja las peticiones del socket
    websocket.socketMensaje();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');
    tabla.generaTablaPersonal('#data-table-autorizar-permisos-ausencia', null, null, true, true);

    //Inicializa funciones de la plantilla
    App.init();

    $('#data-table-autorizar-permisos-ausencia tbody').on('click', 'tr', function () {
        var perfilUsuario = $('#idPerfil').val();
        var idUsuario = $('#idUsuarioRev').val();
        var informacionPermisoAusencia = $('#data-table-autorizar-permisos-ausencia').DataTable().row(this).data();
        var datosPermiso = {
            idPermiso: informacionPermisoAusencia[0],
            perfilUsuario: perfilUsuario
        }
        evento.enviarEvento('EventoPermisosVacaciones/Autorizar', datosPermiso, '#panelAutorizarPermisos', function (respuesta) {
            if (respuesta) {
                $('#contentRevisarPermiso').removeClass('hidden').empty().append(respuesta.formulario);
                $('#contentPermisosPendientes').addClass('hidden');
                $('#btnCancelarRevisarPermiso').on('click', function () {
                    $('#contentRevisarPermiso').empty().addClass('hidden');
                    $('#contentPermisosPendientes').removeClass('hidden');
                });
                var data ={
                    idPermiso: $('#idPermisoRevisar').val(),
                    idPerfil: perfilUsuario,
                    idUser: idUsuario
                }
                $("#btnVerPDFAutorizar").on("click", function () {
                    window.open('/storage/Archivos/'+$('#archivoPDF').val(), '_blank');
                });
                $("#btnCancelarPermiso").on("click", function () {
                    var html = '<div class="row m-t-20">\n\
                                <form id="formMotivoRechazo" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">\n\
                                    <div id="modal-dialogo" class="col-md-12 text-center">\n\
                                        <label>Motivo de rechazo</label>\n\
                                        <textarea id="motivoRechazo" class="form-control" name="motivoRechazo" rows="3" data-parsley-required="true"></textarea><br>\n\
                                        <button id="btnCancelarRechazo" type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Cerrar</button>\n\
                                        <button id="btnAceptarRechazo" type="button" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Aceptar</button>\n\
                                    </div>\n\
                                </form>\n\
                                </div>';
                    $('#btnModalConfirmar').addClass('hidden');
                    $('#btnModalAbortar').addClass('hidden');
                    evento.mostrarModal('Rachazar Permiso', html);
                    $('#btnCancelarRechazo').on('click', function () {
                        evento.cerrarModal();
                    });
                    $('#btnAceptarRechazo').on('click', function () {
                        if (evento.validarFormulario('#formMotivoRechazo')) {
                            data[0]={motivoRechazo: $('#motivoRechazo').val()};
                            evento.enviarEvento('EventoPermisosVacaciones/CancelarPermisos', data, '#modal-dialogo', function (respuesta) {
                                if (respuesta) {
                                    location.reload();
                                } else {
                                    evento.mostrarMensaje('.mensajeSolicitudPermisosRevisar', false, 'Hubo un problema con la cancelación del permiso.', 3000);
                                }
                            });
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
            }else {
                evento.mostrarMensaje('.mensajeAutorizarPermisos', false, 'Hubo un problema con la solicitud.', 3000);
            }
        });
    });
   
});
