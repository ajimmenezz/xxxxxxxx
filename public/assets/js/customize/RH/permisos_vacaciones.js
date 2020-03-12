
$(function () {

    var evento = new Base();
    var select = new Select();
    var file = new Upload();
    this.calendario = new Fecha();
    var tabla = new Tabla();
    var modal = new Modal();

    //Muestra la hora en el sistema
    evento.horaServidor($('#horaServidor').val());

    //Evento para cerra la session
    evento.cerrarSesion();

    //Evento para mostrar la ayuda del sistema
    evento.mostrarAyuda('Ayuda_Proyectos');

    file.crearUpload('#inputEvidenciaIncapacidad', 'EventoPermisosVacaciones/Permisos', ['jpg', 'bmp', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx'], false, [], '', null, false, 1);
    tabla.generaTablaPersonal('#table-permisos-ausencia', null, null, true, true);
    //Inicializa funciones de la plantilla
    App.init();

    $('#inputFechaDocumento').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('#inputFechaDocumento').datepicker("setDate", new Date());
    $('#inputFechaDesde').datetimepicker({
        format: 'YYYY-MM-DD',
        maxDate: moment().add(1, 'year'),
        minDate: moment().add(-15, 'day')
    });
    $('#selectSolicitudHora').timepicker({showMeridian: false});

    $('#inputFechaHasta').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false
    });
    $("#inputFechaDesde").on("dp.change", function (e) {
        $('#inputFechaHasta').data("DateTimePicker").maxDate(moment(e.date).add(1, 'day'));
        $('#inputFechaHasta').data("DateTimePicker").minDate(e.date);
    });
    $("#inputFechaHasta").on("dp.change", function (e) {
        $('#inputFechaDesde').data("DateTimePicker").maxDate(e.date);
    });


    //evento que activa los campos inputCitaFolio, descripcionAusencia
    $('#selectMotivoAusencia').on('change', function () {
        let dato = $('option:selected', this).attr('data-msg');
        let archivo = $('option:selected', this).attr('data-file');

        if ($('#selectTipoAusencia').val() !== "") {
            $('#inputObservaciones').val(dato);
        } else {
            $('#selectTipoAusencia').on('change', function () {
                $('#inputObservaciones').val(dato);
            });
        }
        if (archivo == 1) {
            $("#citaFolio").css("display", "block");
            $("#archivoCitaIncapacidad").css("display", "block");
            $('#textareaMotivoSolicitudPermiso').attr('data-parsley-required', 'false');
            $('#textareaMotivoSolicitudPermiso').val('');
        } else {
            $('#textareaMotivoSolicitudPermiso').attr('data-parsley-required', 'true');
            $("#citaFolio").css("display", "none");
            $('#inputCitaFolio').val('');
            $("#archivoCitaIncapacidad").css("display", "none");
            file.limpiar('#inputEvidenciaIncapacidad');
        }
    });

    $('#selectTipoAusencia').on('change', function () {
        $('#selectMotivoAusencia').empty().append('<option value="">Seleccionar</option>');
        select.cambiarOpcion('#selectMotivoAusencia', '');
        var tipoAusencia = $(this).val();

        switch ($(this).val()) {
            case '1':
                $("#bloqueHorario").css("display", "block");
                $('#labelHora').text('Hora de Entrada');
                $("#bloqueFechaHasta").css("display", "none");
                $('#diaPermiso').html("Fecha del Permiso  *");
                break;
            case '2':
                $("#bloqueHorario").css("display", "block");
                $('#labelHora').text('Hora de Salida');
                $("#bloqueFechaHasta").css("display", "none");
                $('#diaPermiso').html("Fecha del Permiso  *");
                break;
            case '3':
                $("#bloqueHorario").css("display", "none");
                $("#bloqueFechaHasta").css("display", "block");
                $('#diaPermiso').html("Fecha de Ausencia  *");
                break;
            default:
                $("#bloqueHorario").css("display", "none");
                $("#bloqueFechaHasta").css("display", "none");
        }

        if (tipoAusencia !== '') {
            var data = {tipoAusencia: tipoAusencia};
            evento.enviarEvento('EventoPermisosVacaciones/MostarMotivosAucencia', data, '#panelPermisosVacaciones', function (respuesta) {
                $.each(respuesta, function (key, valor) {
                    $("#selectMotivoAusencia").append('<option value="' + valor.Id + '" data-msg="' + valor.Observaciones + '" data-file="' + valor.Archivo + '">' + valor.Nombre + '</option>');
                });
                $('#selectMotivoAusencia').removeAttr('disabled');
            });
        } else {
            $('#selectMotivoAusencia').attr('disabled', 'disabled');
        }
    });

    //evento para enviar la solicitud de permisos
    $("#btnGenerarSolicitudPermiso").on("click", function () {
        if (evento.validarFormulario('#formSolicitudPermiso')) {
            var data = {
                nombre: $('#inputNombre').val(),
                idUsuario: $('#idUsuario').val(),
                departamento: $('#inputDepartamento').val(),
                puesto: $('#inputPuesto').val(),
                tipoAusencia: $('#selectTipoAusencia').val(),
                textoAusencia: $('#selectTipoAusencia option:selected').text(),
                motivoAusencia: $('#selectMotivoAusencia').val(),
                textoMotivoAusencia: $('#selectMotivoAusencia option:selected').text(),
                archivoMotivoAusencia: $('#selectMotivoAusencia option:selected').attr('data-file'),
                citaFolio: $('#inputCitaFolio').val(),
                descripcionAusencia: $('#textareaMotivoSolicitudPermiso').val(),
                evidenciaIncapacidad: $('#inputEvidenciaIncapacidad').val(),
                fechaPermisoDesde: $('#inputFechaPermisoDesde').val(),
                fechaPermisoHasta: $('#inputFechaPermisoHasta').val(),
                horaAusencia: $('#selectSolicitudHora').val()
            }

            if ($('#inputEvidenciaIncapacidad').val() !== '') {
                file.enviarArchivos('#inputEvidenciaIncapacidad', 'EventoPermisosVacaciones/Permisos', '#panelPermisosVacaciones', data, function (respuesta) {
                    if (respuesta !== 'otraImagen') {
                        window.open(respuesta.ruta, '_blank');
                        location.reload();
                    } else {
                        evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
                    }
                });
            } else {
                if (data.archivoMotivoAusencia == 1) {
                    modal.mostrarModalBasico('Aviso', '<h4>Falta la respectiva documentación para tu permiso.<br>\n\
                                            Recuerda que tienes 3 días hábiles para entregarla o adjuntarla,\n\
                                            en caso de no finalizarse se cancelara de forma automática la presente solicitud</h4>');
                    $('#btnAceptar').on('click', function () {
                        peticionCrearPermiso('#modal-dialogo', data);
                    });
                } else {
                    peticionCrearPermiso('#panelPermisosVacaciones', data);
                }
            }
        }
    });

    function peticionCrearPermiso(panel, informacion) {
        evento.enviarEvento('EventoPermisosVacaciones/Permisos', informacion, panel, function (respuesta) {
            if (respuesta) {
                window.open(respuesta.ruta, '_blank');
                location.reload();
            } else {
                evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la solicitud de permiso.', 3000);
            }
        });
    }

    $('#table-permisos-ausencia tbody').on('click', 'tr', function () {

        var informacionPermisoAusencia = $('#table-permisos-ausencia').DataTable().row(this).data();

        if (informacionPermisoAusencia[7] === "Pendiente por Autorizar") {
            var data = {
                idPermiso: informacionPermisoAusencia[0],
                tipoAusencia: informacionPermisoAusencia[2]
            }
            peticionActualizarPermiso(data);
        } else {
            var informacionPermisoAusencia = $('#table-permisos-ausencia').DataTable().row(this).data();

            if (informacionPermisoAusencia[7] == "Autorizado") {
                if (informacionPermisoAusencia[10] == 1) {
                    var data = {
                        idPermiso: informacionPermisoAusencia[0],
                        tipoAusencia: informacionPermisoAusencia[2]
                    }
                    peticionActualizarPermiso(data)
                } else {
                    window.open('/storage/Archivos/' + informacionPermisoAusencia[9], '_blank');
                }
            } else {
                if (informacionPermisoAusencia[7] == "Rechazado") {
                    window.open('/storage/Archivos/' + informacionPermisoAusencia[9], '_blank');
                }
            }
        }
    });

    function peticionActualizarPermiso(informacion) {
        evento.enviarEvento('EventoPermisosVacaciones/VerModalActualizar', informacion, '#panelPermisosVacaciones', function (respuesta) {
            if (respuesta) {
                if (respuesta != "En revision") {
                    $('#contentActualizar').removeClass('hidden').empty().append(respuesta.formulario);
                    $('#contentPermisosVacaciones').addClass('hidden');
    //********efectos modal Actualizar
                    file.crearUpload('#inputEvidenciaIncapacidadAct', 'EventoPermisosVacaciones/Permisos', ['pdf']);
                    $('#btnCancelarActualizarPermiso').on('click', function () {
                        $('#contentActualizar').empty().addClass('hidden');
                        $('#contentPermisosVacaciones').removeClass('hidden');
                    });
                    $('#inputFechaDocumentoAct').datepicker({
                        format: 'yyyy-mm-dd'
                    });
                    $('#inputFechaDocumentoAct').datepicker("setDate", new Date());
                    $('#inputFechaDesdeAct').datetimepicker({
                        format: 'YYYY-MM-DD',
                        maxDate: moment().add(1, 'year'),
                        minDate: moment().add(-15, 'day')
                    });
                    $('#selectSolicitudHoraAct').timepicker({showMeridian: false});
                    $('#inputFechaHastaAct').datetimepicker({
                        format: 'YYYY-MM-DD',
                        useCurrent: false
                    });
                    $("#inputFechaDesdeAct").on("dp.change", function (e) {
                        $('#inputFechaHastaAct').data("DateTimePicker").maxDate(moment(e.date).add(1, 'day'));
                        $('#inputFechaHastaAct').data("DateTimePicker").minDate(e.date);
    //                            $('#inputDescuentoAct').val('1.17 Dias');
                    });
                    $("#inputFechaHastaAct").on("dp.change", function (e) {
                        $('#inputFechaDesdeAct').data("DateTimePicker").maxDate(e.date);
                    });
                    $('#selectMotivoAusenciaAct').on('change', function () {
                        let archivoAct = $('option:selected', this).attr('data-file');
                        
                        if (archivoAct == 1) {
                            $("#citaFolioAct").css("display", "block");
                            $('#inputCitaFolioAct').attr('data-parsley-required', 'true');
                            $("#archivoCitaIncapacidadAct").css("display", "block");
                            $('#inputEvidenciaIncapacidadAct').attr('data-parsley-required', 'true');
                            $('#textareaMotivoSolicitudPermisoAct').attr('data-parsley-required', 'true');
                            $('#textareaMotivoSolicitudPermisoAct').val('');
                            $("#archivoCitaIncapacidadAct").css("display", "block");
                        } else {
                            $('#textareaMotivoSolicitudPermisoAct').attr('data-parsley-required', 'true');
                            $("#citaFolioAct").css("display", "none");
                            $('#inputCitaFolioAct').attr('data-parsley-required', 'false');
                            $('#inputCitaFolioAct').val('');
                            $("#archivoCitaIncapacidadAct").css("display", "none");
                            $('#inputEvidenciaIncapacidadAct').attr('data-parsley-required', 'false');
                            file.limpiar('#inputEvidenciaIncapacidadAct');
                        }
                    });
                    $('#selectTipoAusenciaAct').on('change', function () {
                        $('#selectMotivoAusenciaAct').empty().append('<option value="">Seleccionar</option>');
                        select.cambiarOpcion('#selectMotivoAusenciaAct', '');
                        var tipoAusencia = $(this).val();

                        switch ($(this).val()) {
                            case '1':
                                $("#bloqueHorarioAct").css("display", "block");
                                $('#labelHoraAct').text('Hora de Entrada');
                                break;
                            case '2':
                                $("#bloqueHorarioAct").css("display", "block");
                                $('#labelHoraAct').text('Hora de Salida');
                                break;
                            default:
                                $("#bloqueHorarioAct").css("display", "none");
                        }

                        if (tipoAusencia !== '') {
                            var data = {tipoAusencia: tipoAusencia};
                            evento.enviarEvento('EventoPermisosVacaciones/MostarMotivosAucencia', data, '#panelActualizarPermisos', function (respuesta) {
                                $.each(respuesta, function (key, valor) {
                                    $("#selectMotivoAusenciaAct").append('<option value="' + valor.Id + '" data-msg="' + valor.Observaciones + '" data-file="' + valor.Archivo + '">' + valor.Nombre + '</option>');
                                });
                                $('#selectMotivoAusenciaAct').removeAttr('disabled');
                            });
                        } else {
                            $('#selectMotivoAusenciaAct').attr('disabled', 'disabled');
                        }
                    });

                    $("#btnVerPDFAutorizar").on("click", function () {
                        window.open('/storage/Archivos/' + $('#archivoPDF').val(), '_blank');
                    });
    //actualizar permisos
                    $("#btnCancelarPermiso").on("click", function () {
                        var html = '<div class="row m-t-20">\n\
                                    <form id="formCancelarPermiso" class="margin-bottom-0" enctype="multipart/form-data">\n\
                                        <div id="modal-dialogo" class="col-md-12 text-center">\n\
                                            <label>¿Estas seguro de Cancelar tu solicitud?</label><br>\n\
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
                            var dataActualizar = {
                                idPermiso: $('#idPermisoAct').val()
                            }
                            evento.enviarEvento('EventoPermisosVacaciones/Cancelar', dataActualizar, '#formCancelarPermiso', function (respuesta) {
                                if (respuesta) {
                                    location.reload();
                                } else {
                                    evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la solicitud de permiso.', 3000);
                                }
                            });
                        });
                    });
                    $("#btnActualizarPermiso").on("click", function () {
                        if (evento.validarFormulario('#formActualizarPermiso')) {
                            var dataActualizar = {
                                nombre: $('#inputNombreAct').val(),
                                idUsuario: "",
                                idPermiso: $('#idPermisoAct').val(),
                                departamento: $('#inputDepartamentoAct').val(),
                                puesto: $('#inputPuestoAct').val(),
                                tipoAusencia: $('#selectTipoAusenciaAct').val(),
                                textoAusencia: $('#selectTipoAusenciaAct option:selected').text(),
                                motivoAusencia: $('#selectMotivoAusenciaAct').val(),
                                textoMotivoAusencia: $('#selectMotivoAusenciaAct option:selected').text(),
                                citaFolio: $('#inputCitaFolioAct').val(),
                                descripcionAusencia: $('#textareaMotivoSolicitudPermisoAct').val(),
                                evidenciaIncapacidad: $('#inputEvidenciaIncapacidadAct').val(),
                                fechaPermisoDesde: $('#inputFechaPermisoDesdeAct').val(),
                                fechaPermisoHasta: $('#inputFechaPermisoHastaAct').val(),
                                horaAusencia: $('#selectSolicitudHoraAct').val(),
                                pdf: $('#archivoPDF').val()
                            }
                            if (dataActualizar.evidenciaIncapacidad !== "") {
                                evento.enviarEvento('EventoPermisosVacaciones/ActualizarPermisoArchivo', dataActualizar, '#panelActualizarPermisos', function (respuesta) {
                                    if (respuesta !== 'otraImagen') {
                                        window.open(respuesta.ruta, '_blank');
                                        location.reload();
                                    } else {
                                        evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la imagen selecciona otra distinta.', 3000);
                                    }
                                });
                            } else {
                                evento.enviarEvento('EventoPermisosVacaciones/ActualizarPermiso', dataActualizar, '#panelActualizarPermisos', function (respuesta) {
                                    if (respuesta) {
                                        window.open(respuesta.ruta, '_blank');
                                        location.reload();
                                    } else {
                                        evento.mostrarMensaje('.mensajeSolicitudPermisos', false, 'Hubo un problema con la solicitud de permiso.', 3000);
                                    }
                                });
                            }
                        }
                    });
                    //fin
                } else {
                    evento.mostrarMensaje('.mensajeSolicitudPermisosV1', true, 'El permiso esta en revisión', 3000);
                }
            } else {
                evento.mostrarMensaje('.mensajeSolicitudPermisosV1', false, 'Hubo un problema con la solicitud de permiso.', 3000);
            }
        });
    }
});
