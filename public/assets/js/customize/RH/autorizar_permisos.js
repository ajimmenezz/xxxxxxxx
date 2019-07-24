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
                    idUser: idUsuario,
                    archivo: $('#archivoPDF').val()
                }
                
                $("#btnVerPDFAutorizar").on("click", function () {
                    window.open('/storage/Archivos/'+$('#archivoPDF').val(), '_blank');
                });
                $("#btnCancelarPermiso").on("click", function () {
                    var html = '<div class="row m-t-20">\n\
                                <form id="formMotivoRechazo" class="margin-bottom-0" data-parsley-validate="true" enctype="multipart/form-data">\n\
                                    <div class="col-md-1"></div>\n\
                                    <div id="modal-dialogo" class="col-md-9 text-center">\n\
                                        <div class="form-group">\n\
                                            <label>Motivo de rechazo</label>\n\
                                            <select id="motivoRechazo" class="form-control efectoDescuento" name="motivoRechazo" style="width: 100%">\n\
                                                <option value="">Seleccionar...</option>\n\
                                                <option value="No, porque tu “Control de Ausencias de Personal” no cumple con las “Políticas de Asistencia”">No, porque tu “Control de Ausencias de Personal” no cumple con las “Políticas de Asistencia”</option>\n\
                                                <option value="No, porque hay exceso de trabajo">No, porque hay exceso de trabajo</option>\n\
                                                <option value="No, porque tenemos “Proyectos de Trabajo” pendientes">No, porque tenemos “Proyectos de Trabajo” pendientes</option>\n\
                                                <option value="No, porque tu “Aviso de Ausencia” fue presentado después de 48 horas">No, porque tu “Aviso de Ausencia” fue presentado después de 48 horas</option>\n\
                                                <option value="No, porque el permiso para “Trabajo Externo”, no fue presentado con 24 horas de anticipación">No, porque el permiso para “Trabajo Externo”, no fue presentado con 24 horas de anticipación</option>\n\
                                                <option value="No, porque tu nivel de productividad es bajo">No, porque tu nivel de productividad es bajo</option>\n\
                                                <option value="No, porque tienes excesos de retardos durante la quincena">No, porque tienes excesos de retardos durante la quincena</option>\n\
                                                <option value="No, porque tu comportamiento como trabajador es indeseable">No, porque tu comportamiento como trabajador es indeseable</option>\n\
                                                <option value="No, porque tienes adeudos monetarios con la empresa">No, porque tienes adeudos monetarios con la empresa</option>\n\
                                                <option value="No, porque nos has presentado oportunamente tus comprobaciones de gastos">No, porque nos has presentado oportunamente tus comprobaciones de gastos</option>\n\
                                                <option value="No, porque tienes excesos de permisos durante el mes">No, porque tienes excesos de permisos durante el mes</option>\n\
                                                <option value="No, porque has disfrutado de días vacaciones de manera excesiva durante el mes">No, porque has disfrutado de días vacaciones de manera excesiva durante el mes</option>\n\
                                                <option value="No, porque tienes ausencias injustificadas durante la quincena">No, porque tienes ausencias injustificadas durante la quincena</option>\n\
                                                <option value="No, porque no has anexado los comprobantes médicos que justifiquen tu ausencia laboral">No, porque no has anexado los comprobantes médicos que justifiquen tu ausencia laboral</option>\n\
                                                <option value="No, porque no has anexado los comprobantes médicos que justifiquen tu “llegada tarde”">No, porque no has anexado los comprobantes médicos que justifiquen tu “llegada tarde”</option>\n\
                                                <option value="No, porque no has anexado los comprobantes médicos que justifiquen tu “salida anticipada”">No, porque no has anexado los comprobantes médicos que justifiquen tu “salida anticipada”</option>\n\
                                                <option value="No, porque no procede de acuerdo con mi criterio">No, porque no procede de acuerdo con mi criterio</option>\n\
                                            </select>\n\
                                        </div>\n\
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
                        if ($('#motivoRechazo').val() !== "") {
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
